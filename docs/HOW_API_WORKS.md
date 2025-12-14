# 🚀 How This API Works - Simple & Clean

## The 4-Step Journey

```
Request → Route → Logic → Response
```

---

## Real Example: Create Profile

**User sends:**

```
POST /api/candidate-profile
{ "full_name": "John", "email": "john@example.com" }
```

---

### Step 1: Route (`/api/index.php`)

**What happens:** Figure out where to send this request

```php
// Get endpoint from URL
$endpoint = 'candidate-profile';  // Extracted from URL

// Get method
$method = 'POST';  // POST, GET, PUT, DELETE

// Get data
$data = json_decode(request_body);  // { "full_name": "John", ... }

// Send to controller
switch ($endpoint) {
    case 'candidate-profile':
        $controller = new CandidateProfileController();
        $controller->createProfile($data);
        break;
}
```

**In human:** "Hey, someone wants to create a profile. Send it to ProfileController!"

---

### Step 2: Validate (`Controller`)

**What happens:** Check if data is correct

```php
public function createProfile($data)
{
    // Check required fields
    if (empty($data['full_name'])) {
        return error("Name is required");
    }

    if (empty($data['email'])) {
        return error("Email is required");
    }

    // Data is good, continue...
}
```

**In human:** "Let me check if you sent everything we need."

---

### Step 3: Logic (`Controller`)

**What happens:** Do the actual work

```php
public function createProfile($data)
{
    // ... validation done

    // Save to database
    $id = $this->insert(
        ['full_name', 'email'],
        [$data['full_name'], $data['email']],
        'candidate_profiles'
    );

    // Get saved data back
    $profile = $this->select('*', 'candidate_profiles', ['id' => $id]);

    // Return success
    return ApiResponse::success($profile);
}
```

**In human:** "Okay, let me save this to database and get it back to confirm."

---

### Step 4: Format Response (`ApiResponse.php`)

**What happens:** Make it pretty and send back

```php
public static function success($data)
{
    $response = [
        'success' => true,
        'message' => 'Profile created',
        'data' => $data
    ];

    echo json_encode($response);
}
```

**Output:**

```json
{
  "success": true,
  "message": "Profile created",
  "data": {
    "id": 1,
    "full_name": "John",
    "email": "john@example.com"
  }
}
```

**In human:** "Here's your profile, all saved successfully!"

---

## Complete Flow

```
Request → Router → Validate → Logic → Response

POST /api/candidate-profile
    ↓
index.php finds "candidate-profile"
    ↓
Calls CandidateProfileController
    ↓
Checks if name, email exist
    ↓
Saves to database
    ↓
ApiResponse formats JSON
    ↓
{ "success": true, "data": {...} }
```

---

## Add Your Own Endpoint

### 1. Add Route (`/api/index.php`)

```php
case 'weather':
    $controller = new WeatherController();
    $controller->getWeather();
    break;
```

### 2. Create Logic (`/controller/api/WeatherController.php`)

```php
class WeatherController extends Application
{
    public function getWeather()
    {
        $data = ['temperature' => 25, 'condition' => 'Sunny'];
        ApiResponse::success($data);
    }
}
```

### 3. Test

```
GET /api/weather
→ { "success": true, "data": { "temperature": 25 } }
```

**Done! That's it!**

---

## Files You'll Touch

- **Route:** `/api/index.php` - Add your endpoint here
- **Logic:** `/controller/api/YourController.php` - Write your code here
- **Response:** Automatic (handled by `ApiResponse.php`)

---

## How Files Connect

```php
// 1. index.php imports Controller
require_once __DIR__ . '/../controller/api/CandidateProfileController.php';

// 2. Controller imports ApiResponse
class CandidateProfileController {
    // Uses ApiResponse for all responses
    ApiResponse::success($data);
    ApiResponse::error($message);
}

// 3. ApiResponse formats everything
class ApiResponse {
    // Makes pretty JSON
}
```

**In simple words:**

- `index.php` → Calls your Controller
- `Controller` → Does work, uses ApiResponse
- `ApiResponse` → Makes everything JSON

---

## Quick Tips

✅ **Route** decides which controller to call  
✅ **Controller** validates and does the work  
✅ **ApiResponse** formats everything as JSON automatically

---

**That's all you need to know! Simple, right?** 🎉
