# 🚀 XAMPP Setup Guide - Step by Step

Hey there! This guide will help you set up XAMPP on your MacBook from scratch. No technical jargon, just simple steps.

---

## What is XAMPP?

XAMPP is like a complete package that includes:

- **Apache** - The web server (so your website can run)
- **MySQL** - The database (where all your data lives)
- **PHP** - The programming language (that makes everything work)
- **phpMyAdmin** - A nice interface to manage your database

Instead of installing these separately, XAMPP gives you everything in one go!

---

## Step 1: Download and Install XAMPP

### Download XAMPP

1. Open your browser and go to: https://www.apachefriends.org/download.html
2. Click on "XAMPP for macOS"
3. Choose the latest PHP version (PHP 8.2 or higher)
4. Download the `.dmg` file (it's about 150MB)

### Install XAMPP

1. Open the downloaded `.dmg` file
2. Drag the XAMPP folder to your Applications folder
3. Wait for it to copy (takes about 1-2 minutes)
4. Done! XAMPP is now installed

---

## Step 2: Start XAMPP

1. Open **Finder**
2. Go to **Applications**
3. Find **XAMPP** folder and open it
4. Double-click on **manager-osx** (this opens XAMPP control panel)
5. Click **"Start"** button next to **Apache** → should turn green ✅
6. Click **"Start"** button next to **MySQL** → should turn green ✅

**Both are green? Perfect! Your server is running!** 🎉

---

## Step 3: Put Your Project in the Right Place

XAMPP needs your project to be in a special folder called `htdocs`.

### Where is htdocs?

The full path is: `/Applications/XAMPP/xamppfiles/htdocs/`

### Move Your Project

**Option 1: Copy your project**

```bash
# Open Terminal and run:
sudo cp -R /path/to/your/project /Applications/XAMPP/xamppfiles/htdocs/admin.rojgariindia.com
```

**Option 2: Create a shortcut (symlink)**

```bash
# This creates a link instead of copying
sudo ln -s /path/to/your/project /Applications/XAMPP/xamppfiles/htdocs/admin.rojgariindia.com
```

Replace `/path/to/your/project` with where your project actually is!

---

## Step 4: Check if It's Working

1. Open your browser
2. Go to: `http://localhost`
3. You should see the XAMPP welcome page

To check your project:

- Go to: `http://localhost/admin.rojgariindia.com`

---

## Step 5: Set Up Your Database

### Access phpMyAdmin

1. Open browser and go to: `http://localhost/phpmyadmin`
2. You should see a nice database interface

### Create Database User

1. Click on **"User accounts"** tab at the top
2. Click **"Add user account"** button
3. Fill in the details:
   - **Username:** `admin_rojgari`
   - **Host:** Choose "Local" (should show `localhost`)
   - **Password:** `admin@123` (or choose your own strong password)
   - **Re-type password:** Same as above
4. Scroll down and check these boxes:
   - ✅ "Create database with same name and grant all privileges"
   - ✅ "Grant all privileges on wildcard name"
5. Click **"Go"** button at the bottom

**Database created!** ✅

### Import Your Database

1. Still in phpMyAdmin, click on **"Databases"** tab
2. Click on the database name **"admin_rojgari"** (should be in the list)
3. Click **"Import"** tab at the top
4. Click **"Choose File"** button
5. Find and select: `rojgar_india.sql` from your project folder
6. Scroll down and click **"Go"** button
7. Wait for it to import (takes 5-10 seconds)
8. You should see: "Import has been successfully finished" ✅

### Verify Tables are Imported

1. Click on **"admin_rojgari"** database in left sidebar
2. You should see 15 tables:
   - admin_settings
   - candidates
   - candidate_profiles
   - candidate_skills
   - candidate_work_experience
   - cities
   - contact_inquiry
   - countries
   - job_functions
   - job_industry
   - job_posts
   - job_skills
   - states
   - vw_active_candidates
   - webpages

**All tables showing? Perfect!** 🎉

---

## Step 6: Configure Your Project

Your project needs to know how to connect to the database.

### Update Database Password

1. Open your project folder
2. Go to: `rojgar-india/controller/databaseclass.php`
3. Open this file in any text editor (TextEdit, VSCode, Sublime, etc.)
4. Find these lines at the top:

```php
define("HOST", 'localhost');
define("DBNAME", 'admin_rojgari');
define("DBUSER", 'admin_rojgari');
define("DBPASSWORD", 'admin@123');  // Make sure this matches what you set!
```

5. If you used a different password, change `admin@123` to your password
6. Save the file

---

## Step 7: Test Your API

### Test Health Check

Open your browser and go to:

```
http://localhost/admin.rojgariindia.com/api/health
```

You should see something like:

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

**If you see this, your API is working!** 🎉

---

## Common Issues and Solutions

### Issue 1: "Port 80 already in use"

**Problem:** Something else is using port 80 (like built-in Apache on Mac)

**Solution:**

1. Open Terminal
2. Run: `sudo apachectl stop`
3. Restart XAMPP Apache

### Issue 2: "Access Denied for database"

**Problem:** Wrong password or user doesn't exist

**Solution:**

1. Go back to Step 5 and recreate the database user
2. Make sure password in `databaseclass.php` matches exactly
3. Remember passwords are case-sensitive!

### Issue 3: "404 Not Found" when accessing API

**Problem:** Project not in right location or .htaccess not working

**Solution:**

1. Check project is in: `/Applications/XAMPP/xamppfiles/htdocs/`
2. Check there's a file called `.htaccess` in the `api` folder
3. Restart Apache from XAMPP control panel

### Issue 4: Can't access phpMyAdmin

**Problem:** MySQL not running

**Solution:**

1. Open XAMPP control panel
2. Make sure MySQL is started (should be green)
3. If it's red, click Start button

---

## Quick Reference

### Important URLs

- **XAMPP Home:** `http://localhost`
- **phpMyAdmin:** `http://localhost/phpmyadmin`
- **Your Project:** `http://localhost/admin.rojgariindia.com`
- **API Health:** `http://localhost/admin.rojgariindia.com/api/health`

### Important Locations

- **XAMPP Folder:** `/Applications/XAMPP/`
- **htdocs Folder:** `/Applications/XAMPP/xamppfiles/htdocs/`
- **Your Project:** `/Applications/XAMPP/xamppfiles/htdocs/admin.rojgariindia.com/`

### Database Info

- **Host:** localhost
- **Database Name:** admin_rojgari
- **Username:** admin_rojgari
- **Password:** admin@123

### Starting/Stopping XAMPP

1. Open `/Applications/XAMPP/manager-osx`
2. Click Start/Stop buttons for Apache and MySQL

---

## That's It!

You're all set up! Your API is running and ready to use.

**Next Steps:**

1. Read `API_GUIDE.md` to understand how your API works
2. Use `POSTMAN_GUIDE.md` to test your API with Postman
3. Start building your frontend!

---

**Setup Complete!** ✅  
**Date:** December 12, 2025  
**Status:** Ready to Code! 🚀
