<?php
/**
 * Candidate Profile API Controller
 * Handles all candidate profile related operations
 */

class CandidateProfileController extends Application
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get single candidate profile by ID
     */
    public function getProfile($candidate_id)
    {
        if (!is_numeric($candidate_id)) {
            ApiResponse::error('Invalid candidate ID', 400);
        }
        
        // Get profile
        $profile = $this->select(
            ['all'],
            'candidate_profiles',
            ['id' => $candidate_id],
            'first'
        );
        
        if (empty($profile)) {
            ApiResponse::notFound('Candidate profile not found');
        }
        
        // Get work experience
        $work_experience = $this->select(
            ['all'],
            'candidate_work_experience',
            ['candidate_id' => $candidate_id]
        );
        
        // Get skills
        $skills = $this->select(
            ['all'],
            'candidate_skills',
            ['candidate_id' => $candidate_id]
        );
        
        // Combine data
        $profile['work_experience'] = $work_experience ?: [];
        $profile['skills'] = $skills ?: [];
        
        // Remove sensitive data
        unset($profile['password']);
        
        ApiResponse::success($profile, 'Profile retrieved successfully');
    }
    
    /**
     * Get all candidate profiles with pagination
     */
    public function getAllProfiles($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        
        // Get total count
        $total = $this->selectcustom(
            'SELECT COUNT(*) as total FROM candidate_profiles',
            'first'
        );
        
        // Get profiles
        $profiles = $this->selectcustom(
            "SELECT * FROM candidate_profiles ORDER BY created_at DESC LIMIT $limit OFFSET $offset"
        );
        
        // Remove sensitive data
        foreach ($profiles as &$profile) {
            unset($profile['password']);
        }
        
        ApiResponse::success([
            'profiles' => $profiles,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => (int)$total['total'],
                'total_pages' => ceil($total['total'] / $limit)
            ]
        ], 'Profiles retrieved successfully');
    }
    
    /**
     * Create new candidate profile
     */
    public function createProfile($data, $files = [])
    {
        // Validate required fields
        $validation = $this->validateProfileData($data);
        if (!$validation['valid']) {
            ApiResponse::validationError($validation['errors']);
        }
        
        // Check if email already exists
        $existing = $this->select(
            ['id'],
            'candidate_profiles',
            ['email' => $data['email']],
            'first'
        );
        
        if (!empty($existing)) {
            ApiResponse::error('Email already exists', 409);
        }
        
        // Handle file uploads
        $profile_photo = null;
        $resume_file = null;
        
        if (!empty($files['profile_photo']) && $files['profile_photo']['error'] == 0) {
            $profile_photo = $this->uploadFile($files['profile_photo'], 'profile_photo');
        }
        
        if (!empty($files['resume']) && $files['resume']['error'] == 0) {
            $resume_file = $this->uploadFile($files['resume'], 'resume');
        }
        
        // Prepare data for insertion
        // Handle dates properly - convert empty strings to null
        $date_of_birth = !empty($data['date_of_birth']) ? $data['date_of_birth'] : null;
        $availability_start = !empty($data['availability_start']) ? $data['availability_start'] : null;
        $availability_end = !empty($data['availability_end']) ? $data['availability_end'] : null;
        
        // Handle gender - capitalize first letter for database ENUM (Male, Female, Other)
        $gender = '';
        if (!empty($data['gender'])) {
            $gender = ucfirst(strtolower($data['gender']));  // Convert to proper case
        }
        
        $fields = [
            'full_name', 'surname', 'email', 'mobile_number', 'gender',
            'date_of_birth', 'address', 'country', 'state', 'city',
            'position', 'experienced', 'fresher', 'expected_salary',
            'job_category', 'current_location', 'interview_availability',
            'availability_start', 'availability_end', 'preferred_shift',
            'profile_photo', 'resume', 'ip_address', 'status'
        ];
        
        $values = [
            $data['full_name'] ?? '',
            $data['surname'] ?? '',
            $data['email'],
            $data['mobile_number'],
            $gender,  // Use the properly formatted gender
            $date_of_birth,
            $data['address'] ?? '',
            $data['country'] ?? '',
            $data['state'] ?? '',
            $data['city'] ?? '',
            $data['position'] ?? '',
            isset($data['experienced']) ? 1 : 0,
            isset($data['fresher']) ? 1 : 0,
            $data['expected_salary'] ?? '',
            $data['job_category'] ?? '',
            $data['current_location'] ?? '',
            $data['interview_availability'] ?? '',
            $availability_start,
            $availability_end,
            $data['preferred_shift'] ?? '',
            $profile_photo,
            $resume_file,
            $_SERVER['REMOTE_ADDR'],
            'Active'
        ];
        
        // Insert profile
        $candidate_id = $this->insert($fields, $values, 'candidate_profiles');
        
        // Insert work experience if provided
        if (!empty($data['work_experience']) && is_array($data['work_experience'])) {
            foreach ($data['work_experience'] as $experience) {
                $this->insertWorkExperience($candidate_id, $experience);
            }
        }
        
        // Insert skills if provided
        if (!empty($data['skills']) && is_array($data['skills'])) {
            foreach ($data['skills'] as $skill) {
                $this->insertSkill($candidate_id, $skill);
            }
        }
        
        // Get the created profile
        $profile = $this->select(
            ['all'],
            'candidate_profiles',
            ['id' => $candidate_id],
            'first'
        );
        
        unset($profile['password']);
        
        ApiResponse::success($profile, 'Profile created successfully', 201);
    }
    
    /**
     * Update candidate profile
     */
    public function updateProfile($candidate_id, $data, $files = [])
    {
        if (!is_numeric($candidate_id)) {
            ApiResponse::error('Invalid candidate ID', 400);
        }
        
        // Check if profile exists
        $existing = $this->select(
            ['id'],
            'candidate_profiles',
            ['id' => $candidate_id],
            'first'
        );
        
        if (empty($existing)) {
            ApiResponse::notFound('Candidate profile not found');
        }
        
        // Handle file uploads
        if (!empty($files['profile_photo']) && $files['profile_photo']['error'] == 0) {
            $data['profile_photo'] = $this->uploadFile($files['profile_photo'], 'profile_photo');
        }
        
        if (!empty($files['resume']) && $files['resume']['error'] == 0) {
            $data['resume'] = $this->uploadFile($files['resume'], 'resume');
        }
        
        // Prepare update data
        $fields = [];
        $values = [];
        
        $allowed_fields = [
            'full_name', 'surname', 'email', 'mobile_number', 'gender',
            'date_of_birth', 'address', 'country', 'state', 'city',
            'position', 'experienced', 'fresher', 'expected_salary',
            'job_category', 'current_location', 'interview_availability',
            'availability_start', 'availability_end', 'preferred_shift',
            'profile_photo', 'resume', 'status'
        ];
        
        foreach ($allowed_fields as $field) {
            if (isset($data[$field])) {
                $fields[] = $field;
                $values[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            ApiResponse::error('No valid fields to update', 400);
        }
        
        // Update profile
        $this->update($fields, $values, 'candidate_profiles', ['id' => $candidate_id]);
        
        // Get updated profile
        $profile = $this->select(
            ['all'],
            'candidate_profiles',
            ['id' => $candidate_id],
            'first'
        );
        
        unset($profile['password']);
        
        ApiResponse::success($profile, 'Profile updated successfully');
    }
    
    /**
     * Delete candidate profile
     */
    public function deleteProfile($candidate_id)
    {
        if (!is_numeric($candidate_id)) {
            ApiResponse::error('Invalid candidate ID', 400);
        }
        
        // Check if profile exists
        $existing = $this->select(
            ['id'],
            'candidate_profiles',
            ['id' => $candidate_id],
            'first'
        );
        
        if (empty($existing)) {
            ApiResponse::notFound('Candidate profile not found');
        }
        
        // Delete related records
        $this->delete('candidate_work_experience', ['candidate_id' => $candidate_id]);
        $this->delete('candidate_skills', ['candidate_id' => $candidate_id]);
        
        // Delete profile
        $this->delete('candidate_profiles', ['id' => $candidate_id]);
        
        ApiResponse::success(null, 'Profile deleted successfully');
    }
    
    /**
     * Get work experience for a candidate
     */
    public function getWorkExperience($candidate_id)
    {
        if (!is_numeric($candidate_id)) {
            ApiResponse::error('Invalid candidate ID', 400);
        }
        
        $experience = $this->select(
            ['all'],
            'candidate_work_experience',
            ['candidate_id' => $candidate_id]
        );
        
        ApiResponse::success($experience ?: [], 'Work experience retrieved successfully');
    }
    
    /**
     * Add work experience
     */
    public function addWorkExperience($data)
    {
        if (empty($data['candidate_id']) || !is_numeric($data['candidate_id'])) {
            ApiResponse::error('Valid candidate ID is required', 400);
        }
        
        $experience_id = $this->insertWorkExperience($data['candidate_id'], $data);
        
        $experience = $this->select(
            ['all'],
            'candidate_work_experience',
            ['id' => $experience_id],
            'first'
        );
        
        ApiResponse::success($experience, 'Work experience added successfully', 201);
    }
    
    /**
     * Update work experience
     */
    public function updateWorkExperience($experience_id, $data)
    {
        if (!is_numeric($experience_id)) {
            ApiResponse::error('Invalid experience ID', 400);
        }
        
        $fields = [];
        $values = [];
        
        $allowed_fields = ['position', 'company', 'start_date', 'end_date', 'salary_period', 'is_current'];
        
        foreach ($allowed_fields as $field) {
            if (isset($data[$field])) {
                $fields[] = $field;
                $values[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            ApiResponse::error('No valid fields to update', 400);
        }
        
        $this->update($fields, $values, 'candidate_work_experience', ['id' => $experience_id]);
        
        $experience = $this->select(
            ['all'],
            'candidate_work_experience',
            ['id' => $experience_id],
            'first'
        );
        
        ApiResponse::success($experience, 'Work experience updated successfully');
    }
    
    /**
     * Delete work experience
     */
    public function deleteWorkExperience($experience_id)
    {
        if (!is_numeric($experience_id)) {
            ApiResponse::error('Invalid experience ID', 400);
        }
        
        $this->delete('candidate_work_experience', ['id' => $experience_id]);
        
        ApiResponse::success(null, 'Work experience deleted successfully');
    }
    
    /**
     * Get candidate skills
     */
    public function getCandidateSkills($candidate_id)
    {
        if (!is_numeric($candidate_id)) {
            ApiResponse::error('Invalid candidate ID', 400);
        }
        
        $skills = $this->select(
            ['all'],
            'candidate_skills',
            ['candidate_id' => $candidate_id]
        );
        
        ApiResponse::success($skills ?: [], 'Skills retrieved successfully');
    }
    
    /**
     * Add candidate skills
     */
    public function addCandidateSkills($data)
    {
        if (empty($data['candidate_id']) || !is_numeric($data['candidate_id'])) {
            ApiResponse::error('Valid candidate ID is required', 400);
        }
        
        $skill_id = $this->insertSkill($data['candidate_id'], $data);
        
        $skill = $this->select(
            ['all'],
            'candidate_skills',
            ['id' => $skill_id],
            'first'
        );
        
        ApiResponse::success($skill, 'Skill added successfully', 201);
    }
    
    /**
     * Delete candidate skill
     */
    public function deleteCandidateSkill($skill_id)
    {
        if (!is_numeric($skill_id)) {
            ApiResponse::error('Invalid skill ID', 400);
        }
        
        $this->delete('candidate_skills', ['id' => $skill_id]);
        
        ApiResponse::success(null, 'Skill deleted successfully');
    }
    
    /**
     * Universal file upload for existing candidate
     * Supports uploading profile_photo and/or resume (both at once or one at a time)
     * Automatically deletes old file when uploading new one (only keeps latest)
     * 
     * POST /api/candidate-profile/{id}/upload
     * Form-data (can send one or both): 
     *   - profile_photo: image file (optional)
     *   - resume: document file (optional)
     */
    public function uploadDocument($candidate_id, $files, $request_data)
    {
        if (!is_numeric($candidate_id)) {
            ApiResponse::error('Invalid candidate ID', 400);
        }
        
        // Get candidate with existing file paths
        $candidate = $this->select(
            ['id', 'full_name', 'profile_photo', 'resume'], 
            'candidate_profiles', 
            ['id' => $candidate_id], 
            'first'
        );
        
        if (empty($candidate)) {
            ApiResponse::notFound('Candidate not found');
        }
        
        // Check if at least one file is provided
        $has_photo = !empty($files['profile_photo']) && $files['profile_photo']['error'] == 0;
        $has_resume = !empty($files['resume']) && $files['resume']['error'] == 0;
        
        if (!$has_photo && !$has_resume) {
            ApiResponse::error('No file provided. Send profile_photo and/or resume', 400);
        }
        
        $uploaded_files = [];
        $update_fields = [];
        $update_values = [];
        
        // Upload profile photo if provided
        if ($has_photo) {
            // Delete old photo if exists
            if (!empty($candidate['profile_photo'])) {
                $old_photo_path = __DIR__ . '/../../uploads/profile_photo/' . $candidate['profile_photo'];
                if (file_exists($old_photo_path)) {
                    unlink($old_photo_path);
                }
            }
            
            $photo_path = $this->uploadFileWithId($files['profile_photo'], 'profile_photo', $candidate_id);
            if ($photo_path) {
                $uploaded_files['profile_photo'] = $photo_path;
                $update_fields[] = 'profile_photo';
                $update_values[] = $photo_path;
            }
        }
        
        // Upload resume if provided
        if ($has_resume) {
            // Delete old resume if exists
            if (!empty($candidate['resume'])) {
                $old_resume_path = __DIR__ . '/../../uploads/resume/' . $candidate['resume'];
                if (file_exists($old_resume_path)) {
                    unlink($old_resume_path);
                }
            }
            
            $resume_path = $this->uploadFileWithId($files['resume'], 'resume', $candidate_id);
            if ($resume_path) {
                $uploaded_files['resume'] = $resume_path;
                $update_fields[] = 'resume';
                $update_values[] = $resume_path;
            }
        }
        
        // Check if any file was uploaded successfully
        if (empty($uploaded_files)) {
            ApiResponse::error('File upload failed', 500);
        }
        
        // Update candidate profile with file paths
        if (!empty($update_fields)) {
            $this->update($update_fields, $update_values, 'candidate_profiles', ['id' => $candidate_id]);
        }
        
        // Build success message
        $uploaded_types = array_keys($uploaded_files);
        $message = count($uploaded_types) === 2 
            ? 'Profile photo and resume uploaded successfully' 
            : (in_array('profile_photo', $uploaded_types) 
                ? 'Profile photo uploaded successfully' 
                : 'Resume uploaded successfully');
        
        ApiResponse::success($uploaded_files, $message);
    }
    
    /**
     * Get candidate documents (profile photo & resume)
     */
    public function getCandidateDocuments($candidate_id)
    {
        if (!is_numeric($candidate_id)) {
            ApiResponse::error('Invalid candidate ID', 400);
        }
        
        // Get candidate with file paths
        $candidate = $this->select(
            ['id', 'full_name', 'profile_photo', 'resume'], 
            'candidate_profiles', 
            ['id' => $candidate_id], 
            'first'
        );
        
        if (empty($candidate)) {
            ApiResponse::notFound('Candidate not found');
        }
        
        $documents = [
            'profile_photo' => [
                'path' => $candidate['profile_photo'],
                'url' => $candidate['profile_photo'] ? 'http://localhost/admin.rojgariindia.com/uploads/profile_photo/' . $candidate['profile_photo'] : null,
                'exists' => !empty($candidate['profile_photo'])
            ],
            'resume' => [
                'path' => $candidate['resume'],
                'url' => $candidate['resume'] ? 'http://localhost/admin.rojgariindia.com/uploads/resume/' . $candidate['resume'] : null,
                'exists' => !empty($candidate['resume'])
            ]
        ];
        
        ApiResponse::success($documents, 'Documents retrieved successfully');
    }
    
    /**
     * Helper: Insert work experience
     * Uses prepared statement with proper NULL handling
     */
    private function insertWorkExperience($candidate_id, $data)
    {
        // Handle dates - convert empty strings or null to actual NULL for database
        $start_date = (!empty($data['start_date']) && $data['start_date'] !== null) ? $data['start_date'] : null;
        
        // Handle end_date - null, empty string, or missing should all be NULL
        $end_date = null;
        if (isset($data['end_date']) && $data['end_date'] !== '' && $data['end_date'] !== null) {
            $end_date = $data['end_date'];
        }
        
        // If is_current is true, end_date MUST be null
        if (!empty($data['is_current'])) {
            $end_date = null;
        }
        
        // Use direct PDO query with prepared statements for proper NULL handling
        $sql = "INSERT INTO candidate_work_experience 
                (candidate_id, position, company, start_date, end_date, salary_period, is_current) 
                VALUES (:candidate_id, :position, :company, :start_date, :end_date, :salary_period, :is_current)";
        
        $stmt = $this->dbpdo->prepare($sql);
        $stmt->execute([
            ':candidate_id' => $candidate_id,
            ':position' => $data['position'] ?? '',
            ':company' => $data['company'] ?? '',
            ':start_date' => $start_date,
            ':end_date' => $end_date,  // Proper NULL handling
            ':salary_period' => $data['salary_period'] ?? '',
            ':is_current' => !empty($data['is_current']) ? 1 : 0
        ]);
        
        return $this->dbpdo->lastInsertId();
    }
    
    /**
     * Helper: Insert skill
     */
    private function insertSkill($candidate_id, $data)
    {
        $fields = ['candidate_id', 'skill_name', 'years_of_experience'];
        $values = [
            $candidate_id,
            is_array($data) ? ($data['skill_name'] ?? '') : $data,
            is_array($data) ? ($data['years_of_experience'] ?? '') : ''
        ];
        
        return $this->insert($fields, $values, 'candidate_skills');
    }
    
    /**
     * Helper: Validate profile data
     */
    private function validateProfileData($data)
    {
        $errors = [];
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        
        if (empty($data['mobile_number'])) {
            $errors['mobile_number'] = 'Mobile number is required';
        }
        
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Full name is required';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Helper: Upload file (used during profile creation without ID)
     */
    private function uploadFile($file, $type = 'profile_photo')
    {
        $upload_dir = __DIR__ . '/../../uploads/' . $type . '/';
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '_' . time() . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            return $file_name;
        }
        
        return null;
    }
    
    /**
     * Helper: Upload file with candidate ID (creates folder structure: uploads/type/candidate_id/)
     */
    private function uploadFileWithId($file, $type, $candidate_id)
    {
        // Create folder structure: uploads/profile_photo/123/ or uploads/resume/123/
        $upload_dir = __DIR__ . '/../../uploads/' . $type . '/' . $candidate_id . '/';
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = $type . '_' . $candidate_id . '_' . time() . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Return relative path: candidate_id/filename
            return $candidate_id . '/' . $file_name;
        }
        
        return null;
    }
}
