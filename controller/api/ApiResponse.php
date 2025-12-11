<?php
/**
 * API Response Helper
 * Provides consistent JSON response formatting
 */

class ApiResponse
{
    /**
     * Send success response
     * 
     * @param mixed $data Response data
     * @param string $message Success message
     * @param int $code HTTP status code
     */
    public static function success($data = null, $message = 'Success', $code = 200)
    {
        http_response_code($code);
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ], JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Send error response
     * 
     * @param string $message Error message
     * @param int $code HTTP status code
     * @param array $errors Validation errors
     */
    public static function error($message = 'Error', $code = 400, $errors = null)
    {
        http_response_code($code);
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Send validation error response
     * 
     * @param array $errors Validation errors
     * @param string $message Error message
     */
    public static function validationError($errors, $message = 'Validation failed')
    {
        self::error($message, 422, $errors);
    }
    
    /**
     * Send not found response
     * 
     * @param string $message Error message
     */
    public static function notFound($message = 'Resource not found')
    {
        self::error($message, 404);
    }
    
    /**
     * Send unauthorized response
     * 
     * @param string $message Error message
     */
    public static function unauthorized($message = 'Unauthorized')
    {
        self::error($message, 401);
    }
}
