# 📚 API Guide - Understanding Your Rojgari India API

## Your API Structure

Here's how your project is organized:

```
admin.rojgariindia.com/
│
├── api/                          ← Main API folder
│   ├── index.php                 ← The main router (handles all requests)
│   └── .htaccess                 ← Makes URLs clean (no index.php in URL)
│
├── controller/                   ← The brain of your API
│   ├── api/
│   │   ├── ApiResponse.php       ← Formats all responses nicely
│   │   └── CandidateProfileController.php  ← Handles profile operations
│   │
│   ├── application.php           ← Main application logic
│   └── [other controllers...]
│
├── rojgar-india/
│   └── controller/
│       └── databaseclass.php     ← Connects to database
│
│
├── uploads/                     ← Where files are stored
│   ├── profile_photo/           ← Profile pictures
│   └── resume/                  ← Resume files
│
└── rojgar_india.sql             ← Main database (use this one!)
```

---

## How the API Works

### 1. Request Flow

When someone makes an API request, here's what happens:

```
User Request → .htaccess → index.php → Controller → Database → Response
```

**Step by step:**

1. User sends a request (like: GET /api/health)
2. `.htaccess` cleans the URL and sends it to `index.php`
3. `index.php` figures out which endpoint was called
4. Appropriate controller handles the request
5. Controller talks to database if needed
6. Response is formatted and sent back as JSON

### 2. Response Format

Every API response looks like this:

**Success Response:**

```json
{
  "success": true,
  "message": "What happened (in simple words)",
  "data": {
    // The actual data you asked for
  },
  "timestamp": "2025-12-12 12:00:00"
}
```

**Error Response:**

```json
{
  "success": false,
  "message": "What went wrong",
  "timestamp": "2025-12-12 12:00:00"
}
```

---

## Available API Endpoints

Your API has these endpoints available:

### 1. Health Check

**What it does:** Checks if the API is running

**URL:** `GET /api/health`

**When to use:** To quickly check if your API server is alive

**Response:**

```json
{
  "success": true,
  "message": "API is running",
  "data": {
    "status": "healthy",
    "timestamp": "2025-12-12 12:00:00",
    "version": "1.0.0"
  }
}
```

---

### 2. Create Candidate Profile

**What it does:** Creates a new candidate in the system

**URL:** `POST /api/candidate-profile`

**When to use:** When a new user signs up or creates their profile

**What you need to send:**

```json
{
  "full_name": "John Doe", // Required - Person's full name
  "email": "john@example.com", // Required - Email address
  "mobile_number": "9876543210", // Required - 10 digit mobile
  "gender": "male", // Optional - male/female/other
  "date_of_birth": "1995-05-15", // Optional - Format: YYYY-MM-DD
  "current_location": "Mumbai, Maharashtra", // Optional - Where they live now
  "expected_salary": "12 LPA", // Optional - Salary expectation
  "availability_start": "2025-01-15", // Required - When they can start
  "availability_end": "2025-12-31" // Required - Until when available
}
```

**What you get back:**

```json
{
  "success": true,
  "message": "Profile created successfully",
  "data": {
    "id": 1, // New profile ID (important!)
    "full_name": "John Doe",
    "email": "john@example.com",
    // ... all other fields
    "created_at": "2025-12-12 12:00:00"
  }
}
```

**Important Notes:**

- Email must be unique (can't use same email twice)
- Mobile number should be 10 digits
- Dates must be in YYYY-MM-DD format
- `availability_start` and `availability_end` are required!

---

### 3. Get All Profiles

**What it does:** Gets a list of all candidate profiles

**URL:** `GET /api/candidate-profile`

**When to use:** When you want to show a list of all candidates (like in admin panel)

**You can add these to URL:**

- `?page=1` - Which page you want (default: 1)
- `?limit=10` - How many results per page (default: 10)

**Example:** `GET /api/candidate-profile?page=2&limit=20`

**What you get back:**

```json
{
  "success": true,
  "message": "Profiles retrieved successfully",
  "data": {
    "profiles": [
      {
        "id": 1,
        "full_name": "John Doe",
        "email": "john@example.com"
        // ... other fields
      },
      {
        "id": 2,
        "full_name": "Jane Smith",
        "email": "jane@example.com"
        // ... other fields
      }
    ],
    "pagination": {
      "current_page": 1, // Which page you're on
      "per_page": 10, // How many per page
      "total": 25, // Total number of profiles
      "total_pages": 3 // How many pages total
    }
  }
}
```

---

### 4. Get Single Profile

**What it does:** Gets details of one specific candidate

**URL:** `GET /api/candidate-profile/{id}`

**Example:** `GET /api/candidate-profile/1`

**When to use:** When you want to show complete details of one candidate

**What you get back:**

```json
{
  "success": true,
  "message": "Profile retrieved successfully",
  "data": {
    "id": 1,
    "full_name": "John Doe",
    "email": "john@example.com",
    "mobile_number": "9876543210",
    // ... all profile fields
    "work_experience": [], // Array of work experiences
    "skills": [] // Array of skills
  }
}
```

**If profile doesn't exist:**

```json
{
  "success": false,
  "message": "Profile not found"
}
```

---

### 5. Update Profile

**What it does:** Updates an existing candidate profile

**URL:** `PUT /api/candidate-profile/{id}`

**Example:** `PUT /api/candidate-profile/1`

**When to use:** When a candidate wants to edit their profile

**What you can send (all optional):**

```json
{
  "full_name": "John Updated", // Update name
  "mobile_number": "9999999999", // Update phone
  "expected_salary": "15 LPA", // Update salary
  "current_location": "Bangalore" // Update location
  // ... any other field you want to update
}
```

**Important:**

- You only need to send fields you want to change
- Can't change email (it's unique identifier)
- Can't change the ID

**What you get back:**

```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "id": 1,
    // ... all fields with updated values
    "updated_at": "2025-12-12 12:30:00" // New timestamp
  }
}
```

---

### 6. Delete Profile

**What it does:** Removes a candidate profile completely

**URL:** `DELETE /api/candidate-profile/{id}`

**Example:** `DELETE /api/candidate-profile/1`

**When to use:** When a candidate wants to delete their account

**No data needed** - just the ID in the URL

**What you get back:**

```json
{
  "success": true,
  "message": "Profile deleted successfully"
}
```

**Important:**

- This also deletes related work experience and skills
- This action cannot be undone!
- If profile doesn't exist, you'll get an error

---

### 7. Upload Files (Universal Upload)

**What it does:** Uploads profile photo and/or resume for an existing candidate

**URL:** `POST /api/candidate-profile/{id}/upload`

**Example:** `POST /api/candidate-profile/1/upload`

**When to use:** After creating a profile, upload files separately using the candidate ID

**Content-Type:** `multipart/form-data`

**What you can send (one or both):**

- `profile_photo` (file) - Image file (JPG, PNG, GIF - max 2MB) - **OPTIONAL**
- `resume` (file) - Document file (PDF, DOC, DOCX - max 5MB) - **OPTIONAL**

**Upload Options:**

**Option 1: Upload only photo**

```
Form-data:
  - profile_photo: [image.jpg]
```

**Option 2: Upload only resume**

```
Form-data:
  - resume: [resume.pdf]
```

**Option 3: Upload both together**

```
Form-data:
  - profile_photo: [image.jpg]
  - resume: [resume.pdf]
```

**What you get back:**

**If uploading photo only:**

```json
{
  "success": true,
  "message": "Profile photo uploaded successfully",
  "data": {
    "profile_photo": "1/profile_photo_1_1702345678.jpg"
  }
}
```

**If uploading resume only:**

```json
{
  "success": true,
  "message": "Resume uploaded successfully",
  "data": {
    "resume": "1/resume_1_1702345678.pdf"
  }
}
```

**If uploading both:**

```json
{
  "success": true,
  "message": "Profile photo and resume uploaded successfully",
  "data": {
    "profile_photo": "1/profile_photo_1_1702345678.jpg",
    "resume": "1/resume_1_1702345678.pdf"
  }
}
```

**How files are organized:**

- Files stored in: `uploads/{type}/{candidate_id}/`
- Example photo: `uploads/profile_photo/1/profile_photo_1_1702345678.jpg`
- Example resume: `uploads/resume/1/resume_1_1702345678.pdf`
- Each candidate gets their own folder
- Paths stored in database: `candidate_profiles.profile_photo` and `candidate_profiles.resume`

**Important Notes:**

- ✅ **Old files are automatically deleted** when uploading new ones
- ✅ Each candidate has only **ONE photo and ONE resume** (latest version)
- ✅ Flexible - upload one or both files in single request
- ✅ If file upload fails for any reason, you get a clear error message

---

### 8. Get Candidate Documents

**What it does:** Retrieves all document URLs (photo & resume) for a candidate

**URL:** `GET /api/candidate-profile/{id}/documents`

**Example:** `GET /api/candidate-profile/1/documents`

**When to use:** To display or download candidate's uploaded files

**What you get back:**

```json
{
  "success": true,
  "message": "Documents retrieved successfully",
  "data": {
    "profile_photo": {
      "path": "1/profile_photo_1_1702345678.jpg",
      "url": "http://localhost/admin.rojgariindia.com/uploads/profile_photo/1/profile_photo_1_1702345678.jpg",
      "exists": true
    },
    "resume": {
      "path": "1/resume_1_1702345678.pdf",
      "url": "http://localhost/admin.rojgariindia.com/uploads/resume/1/resume_1_1702345678.pdf",
      "exists": true
    }
  }
}
```

**Use the `url` field to:**

- Display images in `<img>` tags
- Create download links for resumes
- Check if files exist with `exists` field

---

## Recommended File Upload Workflow

**Best Practice:** Upload files AFTER creating the profile, not during profile creation.

### Step-by-Step Process:

1. **Create Profile (JSON only)**

   ```
   POST /api/candidate-profile
   {
     "full_name": "John Doe",
     "email": "john@example.com",
     // ... other fields
   }
   ```

   **Response:** Get back `candidate_id` (e.g., `"id": 1`)

2. **Upload Files (one or both using the ID)**

   **Option A: Upload photo only**

   ```
   POST /api/candidate-profile/1/upload
   FormData: { profile_photo: [file] }
   ```

   **Option B: Upload resume only**

   ```
   POST /api/candidate-profile/1/upload
   FormData: { resume: [file] }
   ```

   **Option C: Upload both together**

   ```
   POST /api/candidate-profile/1/upload
   FormData: {
     profile_photo: [photo_file],
     resume: [resume_file]
   }
   ```

3. **Get Complete Profile with Documents**
   ```
   GET /api/candidate-profile/1
   GET /api/candidate-profile/1/documents
   ```

**Why this approach?**

- ✅ Cleaner API calls (JSON vs multipart)
- ✅ Better error handling (files separate from data)
- ✅ Organized folder structure (files grouped by candidate ID)
- ✅ Easier to retry uploads if they fail
- ✅ Can update files independently
- ✅ **ONE endpoint** for all file uploads (photo/resume/both)
- ✅ **Automatic cleanup** - old files deleted when uploading new ones

---

## Understanding HTTP Methods

Your API uses different "methods" for different actions:

| Method     | What it means           | Example                    |
| ---------- | ----------------------- | -------------------------- |
| **GET**    | "Give me data"          | Getting a list of profiles |
| **POST**   | "Create something new"  | Creating a new profile     |
| **PUT**    | "Update existing thing" | Editing a profile          |
| **DELETE** | "Remove this"           | Deleting a profile         |

Think of it like this:

- **GET** = Reading a book
- **POST** = Writing a new book
- **PUT** = Editing your book
- **DELETE** = Throwing away the book

---

## Status Codes Explained

When the API responds, it also sends a "status code" that tells you what happened:

| Code    | What it means                     | Example                    |
| ------- | --------------------------------- | -------------------------- |
| **200** | OK - Everything worked!           | Successfully got data      |
| **201** | Created - New thing made!         | New profile created        |
| **400** | Bad Request - You sent wrong data | Missing required field     |
| **404** | Not Found - Thing doesn't exist   | Profile ID doesn't exist   |
| **500** | Server Error - Something broke    | Database connection failed |

---

## Error Handling

When something goes wrong, the API tells you clearly:

**Missing Required Field:**

```json
{
  "success": false,
  "message": "Missing required fields: email, mobile_number"
}
```

**Invalid Data:**

```json
{
  "success": false,
  "message": "Invalid email format"
}
```

**Not Found:**

```json
{
  "success": false,
  "message": "Profile not found"
}
```

**Server Error:**

```json
{
  "success": false,
  "message": "Internal server error: Database connection failed"
}
```

---

## Database Tables

Your API uses these main tables from `rojgar_india.sql`:

### 1. candidates

**What it stores:** Main candidate information

**Important fields:**

- `id` - Unique ID for each candidate
- `fullname` - Candidate's full name
- `email` - Email address (must be unique)
- `phone_no` - Mobile number
- `status` - Active/Inactive
- `resume` - Path to resume file
- `profile_photo` - Path to profile picture

### 2. candidate_profiles

**What it stores:** Extended profile details

**Why separate?** Because not all info is always needed. This keeps the main table fast!

### 3. candidate_skills

**What it stores:** Skills each candidate has

**Structure:** One row per skill per candidate

### 4. candidate_work_experience

**What it stores:** Work history

**Structure:** One row per job per candidate

### 5. cities, states, countries

**What it stores:** Location data

**Why useful?** For dropdowns and location selection

---

## File Uploads

Your API can handle file uploads for:

### Profile Photos

- **Stored in:** `uploads/profile_photo/`
- **Accepted formats:** JPG, PNG, GIF
- **Max size:** 10MB
- **Naming:** `{id}_{name}_{timestamp}.{extension}`

### Resumes

- **Stored in:** `uploads/resume/`
- **Accepted formats:** PDF, DOC, DOCX
- **Max size:** 10MB
- **Naming:** `{gender}_{position}_{name}_{month}_{year}.{extension}`

---

## Security Features

Your API has built-in security:

### 1. CORS Headers

Allows your frontend (website/app) to talk to the API even if they're on different domains.

### 2. SQL Injection Protection

Uses PDO prepared statements - prevents hackers from injecting malicious SQL.

### 3. XSS Protection

Cleans data before storing to prevent malicious scripts.

### 4. Input Validation

Checks that required fields are present and in correct format.

---

## Best Practices

### When Building Your Frontend:

1. **Always check `success` field**

   ```javascript
   if (response.success) {
     // Handle success
   } else {
     // Show error message
   }
   ```

2. **Show user-friendly errors**

   - Don't show technical errors to users
   - Use the `message` field from API response

3. **Handle loading states**

   - Show a spinner while API is working
   - Disable buttons to prevent double-clicks

4. **Validate before sending**

   - Check required fields on frontend first
   - Saves unnecessary API calls

5. **Store the ID**
   - When you create something, save the `id` you get back
   - You'll need it for update/delete operations

---

## Testing Your API

### Quick Test Checklist:

✅ Health check works  
✅ Can create a profile  
✅ Can get list of profiles  
✅ Can get single profile  
✅ Can update profile  
✅ Can delete profile  
✅ Can upload photo only  
✅ Can upload resume only  
✅ Can upload both files together  
✅ Can retrieve document URLs  
✅ Old files are deleted when uploading new ones  
✅ Error messages are clear  
✅ Files are organized in candidate-specific folders

---

## Common Questions

**Q: Can I change the response format?**  
A: Yes! Edit `ApiResponse.php` in `controller/api/` folder

**Q: How do I add a new endpoint?**  
A: Add a new case in `api/index.php` and create logic in controller

**Q: Can I add authentication?**  
A: Yes! You'd add a middleware to check tokens before allowing access

**Q: How do I enable HTTPS?**  
A: Configure SSL certificate in XAMPP/Apache settings

**Q: Can I limit API calls?**  
A: Yes! Add rate limiting middleware (not included yet)

---

## Next Steps

1. ✅ Read this guide
2. 📮 Check `POSTMAN_GUIDE.md` to test with Postman
3. 🚀 Start building your frontend
4. 🔒 Add authentication when ready for production

---

**API Version:** 1.0.0  
**Last Updated:** December 12, 2025  
**Status:** Production Ready! 🎉
