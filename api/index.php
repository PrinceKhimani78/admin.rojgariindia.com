<?php
/**
 * REST API Router
 * Handles all API requests for the Rojgari India application
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Start session
@session_start();
date_default_timezone_set('Asia/Calcutta');

// Include dependencies
require_once __DIR__ . '/../controller/application.php';
require_once __DIR__ . '/../controller/api/ApiResponse.php';
require_once __DIR__ . '/../controller/api/CandidateProfileController.php';

// Parse the request
$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_parts = explode('/', trim($request_uri, '/'));

// Get the API endpoint (after 'api')
$api_index = array_search('api', $uri_parts);
$endpoint = isset($uri_parts[$api_index + 1]) ? $uri_parts[$api_index + 1] : '';
$resource_id = isset($uri_parts[$api_index + 2]) ? $uri_parts[$api_index + 2] : null;
$action = isset($uri_parts[$api_index + 3]) ? $uri_parts[$api_index + 3] : null;

// Get request body for POST/PUT
$request_body = file_get_contents('php://input');
$request_data = json_decode($request_body, true) ?: [];

// Merge with POST data for multipart/form-data
if (!empty($_POST)) {
    $request_data = array_merge($request_data, $_POST);
}

// Route the request
try {
    $controller = new CandidateProfileController();
    
    switch ($endpoint) {
        case 'candidate-profile':
            // Check if there's an action parameter (e.g., /api/candidate-profile/123/upload)
            if ($resource_id && $action) {
                switch ($action) {
                    case 'upload':
                        // POST /api/candidate-profile/{id}/upload
                        // Universal file upload - handles both profile_photo and resume
                        // Form-data: file_type (profile_photo/resume), file (the actual file)
                        if ($request_method === 'POST') {
                            $controller->uploadDocument($resource_id, $_FILES, $_POST);
                        } else {
                            ApiResponse::error('Method not allowed', 405);
                        }
                        break;
                    
                    case 'documents':
                        // GET /api/candidate-profile/{id}/documents
                        if ($request_method === 'GET') {
                            $controller->getCandidateDocuments($resource_id);
                        } else {
                            ApiResponse::error('Method not allowed', 405);
                        }
                        break;
                    
                    default:
                        ApiResponse::error('Invalid action', 404);
                }
            } else {
                // Standard CRUD operations
                switch ($request_method) {
                    case 'POST':
                        // Create complete profile (Personal + Work Experience + Skills + Availability)
                        // Matches single form with one submit button
                        $controller->createProfile($request_data, $_FILES);
                        break;
                        
                    case 'GET':
                        if ($resource_id) {
                            // Get single user's complete profile by user ID
                            $controller->getProfile($resource_id);
                        } else {
                            // Get all profiles with pagination
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                            $controller->getAllProfiles($page, $limit);
                        }
                        break;
                        
                    case 'PUT':
                        // Update user's profile by user ID
                        if ($resource_id) {
                            $controller->updateProfile($resource_id, $request_data, $_FILES);
                        } else {
                            ApiResponse::error('User ID is required for update', 400);
                        }
                        break;
                        
                    case 'DELETE':
                        // Delete user's profile by user ID (cascading delete for work, skills, etc.)
                        if ($resource_id) {
                            $controller->deleteProfile($resource_id);
                        } else {
                            ApiResponse::error('User ID is required for delete', 400);
                        }
                        break;
                        
                    default:
                        ApiResponse::error('Method not allowed', 405);
                }
            }
            break;
            
        case 'health':
            // Health check endpoint
            ApiResponse::success([
                'status' => 'healthy',
                'timestamp' => date('Y-m-d H:i:s'),
                'version' => '1.0.0'
            ], 'API is running');
            break;
            
        default:
            ApiResponse::error('Endpoint not found', 404);
    }
    
} catch (Exception $e) {
    ApiResponse::error('Internal server error: ' . $e->getMessage(), 500);
}
