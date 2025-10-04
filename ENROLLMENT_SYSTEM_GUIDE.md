# COURSE ENROLLMENT SYSTEM - Implementation Guide

## ✅ Completed Implementation

### Step 1: Database Migration ✓
**File:** `application/migrations/003_create_enrollments_table.php`

The enrollments table has been created with the following structure:
- `id` - Primary key, auto-increment
- `user_id` - Foreign key to users table
- `course_id` - Foreign key to courses table  
- `enrollment_date` - DateTime, not null

**Migration Status:** ✅ Successfully migrated

---

### Step 2: Enrollment Model ✓
**File:** `application/models/Enrollment_model.php`

Created model with the following methods:
- `enrollUser($data)` - Insert new enrollment record
- `getUserEnrollments($user_id)` - Fetch all courses a user is enrolled in
- `isAlreadyEnrolled($user_id, $course_id)` - Check if user is already enrolled (prevents duplicates)
- `unenrollUser($user_id, $course_id)` - Remove enrollment
- `getCourseEnrollments($course_id)` - Get all students in a course
- `countCourseEnrollments($course_id)` - Count students in a course
- `countUserEnrollments($user_id)` - Count courses a user is enrolled in
- `getEnrollment($user_id, $course_id)` - Get specific enrollment record

---

### Step 3: Course Model ✓
**File:** `application/models/Course_model.php`

Created model with comprehensive methods:
- `getAllCourses()` - Get all courses with instructor names
- `getCourseById($course_id)` - Get single course details
- `getCoursesByTeacher($teacher_id)` - Get courses by specific teacher
- `getAvailableCourses($user_id)` - Get courses NOT enrolled by user
- `createCourse($data)` - Create new course
- `updateCourse($course_id, $data)` - Update course
- `deleteCourse($course_id)` - Delete course
- `countCourses()` - Count total courses
- `countCoursesByTeacher($teacher_id)` - Count courses by teacher

---

### Step 4: Course Controller ✓
**File:** `application/controllers/Course.php`

**Security Features Implemented:**
- ✅ Authorization check - User must be logged in
- ✅ Role verification - Only students can enroll
- ✅ Input validation - Course ID must be numeric
- ✅ SQL injection prevention - Using Query Builder
- ✅ XSS protection - Input post with TRUE parameter
- ✅ CSRF protection - CSRF token in response
- ✅ Server-side user ID validation - Uses session, NOT client input
- ✅ Duplicate enrollment prevention
- ✅ Course existence verification

**enroll() Method Features:**
1. Checks if request is POST
2. Verifies user is logged in
3. Ensures only students can enroll
4. Validates course_id (numeric check)
5. Gets user_id from SESSION (never trusts client)
6. Verifies course exists
7. Checks if already enrolled
8. Inserts enrollment with timestamp
9. Returns JSON response with CSRF token

---

### Step 5: Updated Auth Controller ✓
**File:** `application/controllers/Auth.php`

Modified dashboard() method for students to load:
- `$data['enrolled_courses']` - List of enrolled courses
- `$data['enrolled_count']` - Count of enrolled courses
- `$data['available_courses']` - Courses not yet enrolled

---

### Step 6: Student Dashboard View ✓
**File:** `application/views/auth/dashboard.php`

**New Sections Added:**
1. **Enrolled Courses Count** - Dynamic counter updated via AJAX
2. **My Enrolled Courses** - List group showing:
   - Course title
   - Course description
   - Instructor name
   - Enrollment date
   - "Enrolled" badge
3. **Available Courses** - List with:
   - Course title
   - Course description
   - Instructor name
   - "Enroll" button with data attributes

---

### Step 7: AJAX Implementation ✓
**File:** `application/views/templates/footer.php`

**jQuery AJAX Features:**
- ✅ CSRF token handling
- ✅ Click event listener on `.enroll-btn`
- ✅ Prevents default behavior
- ✅ Disables button during request
- ✅ Shows loading state
- ✅ POST request to `/course/enroll`
- ✅ Success handler:
  - Shows Bootstrap alert
  - Removes course from available list
  - Adds course to enrolled list
  - Updates enrollment counter
  - Animates transitions
- ✅ Error handler with retry capability
- ✅ Updates CSRF token after each request
- ✅ No page reload required

---

### Step 8: Routes Configuration ✓
**File:** `application/config/routes.php`

Added routes:
```php
$route['course/enroll'] = 'course/enroll';
$route['course/(:num)'] = 'course/view/$1';
$route['course'] = 'course/index';
```

---

### Step 9: CSRF Protection ✓
**File:** `application/config/config.php`

```php
$config['csrf_protection'] = TRUE;  // ✅ ENABLED
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;  // Regenerates on every submit
```

---

## 🧪 Testing Instructions

### Prerequisites
1. Run migrations: `php index.php migrate`
2. Seed users: Visit `http://localhost/WEBSYSTEM/index.php/userseeder`
3. Seed courses: Visit `http://localhost/WEBSYSTEM/index.php/courseseeder`

### Test Accounts
- **Admin:** admin@lms.com / admin123
- **Teacher:** teacher@lms.com / teacher123
- **Student:** student@lms.com / student123

---

## 📸 Required Screenshots

### 1. Database Structure
- Open phpMyAdmin
- Navigate to `lms_magallanoo` database
- Click on `enrollments` table
- Click "Structure" tab
- **Screenshot:** Show table structure with fields

### 2. Student Dashboard - Before Enrollment
- Login as student
- Navigate to dashboard
- **Screenshot:** Show "Available Courses" section with Enroll buttons

### 3. Browser Developer Tools - AJAX Request
- Login as student
- Open Developer Tools (F12)
- Go to "Network" tab
- Click "Enroll" button on a course
- Click on the `enroll` request
- **Screenshot:** Show:
  - Request URL: `/course/enroll`
  - Request Method: POST
  - Form Data: `course_id`, CSRF token
  - Response: JSON with success message

### 4. Student Dashboard - After Enrollment
- After successful enrollment
- **Screenshot:** Show:
  - Success alert message
  - Course moved to "Enrolled Courses" section
  - "Enroll" button disappeared/disabled
  - Enrollment counter updated

### 5. Database - Enrollments Table Data
- Open phpMyAdmin
- View `enrollments` table data
- **Screenshot:** Show enrollment records with:
  - id
  - user_id
  - course_id
  - enrollment_date

---

## 🛡️ Security Vulnerability Testing

### Test 1: Authorization Bypass ✓
**Test:** Access enrollment endpoint without login
```bash
# Using browser console or Postman
POST http://localhost/WEBSYSTEM/index.php/course/enroll
Body: { course_id: 1 }
```
**Expected Result:** `Unauthorized access. Please login.`

### Test 2: SQL Injection Prevention ✓
**Test:** Send malicious course_id
```javascript
// In browser console after login
$.post('/WEBSYSTEM/index.php/course/enroll', {
    course_id: "1 OR 1=1",
    csrf_test_name: $('[name=csrf_test_name]').val()
});
```
**Expected Result:** `Invalid course ID.` (Not numeric validation)

### Test 3: CSRF Protection ✓
**Test:** Send request without CSRF token
```javascript
$.post('/WEBSYSTEM/index.php/course/enroll', {
    course_id: 1
    // NO CSRF token
});
```
**Expected Result:** `403 Forbidden` or CSRF error

### Test 4: Data Tampering Prevention ✓
**Test:** Try to enroll another user
```javascript
$.post('/WEBSYSTEM/index.php/course/enroll', {
    course_id: 1,
    user_id: 999,  // Different user ID
    csrf_test_name: $('[name=csrf_test_name]').val()
});
```
**Expected Result:** Server ignores `user_id`, uses session ID instead

### Test 5: Input Validation ✓
**Test:** Send invalid/non-existent course_id
```javascript
$.post('/WEBSYSTEM/index.php/course/enroll', {
    course_id: 99999,
    csrf_test_name: $('[name=csrf_test_name]').val()
});
```
**Expected Result:** `Course not found.`

### Test 6: Duplicate Enrollment Prevention ✓
**Test:** Try to enroll in same course twice
```javascript
// Enroll in course
// Try enrolling again in same course
```
**Expected Result:** `You are already enrolled in this course.`

### Test 7: Role-Based Access Control ✓
**Test:** Login as teacher or admin, try to enroll
**Expected Result:** `Only students can enroll in courses.`

---

## 🎯 How to Use the System

### For Students:

1. **Login**
   - Go to: `http://localhost/WEBSYSTEM/`
   - Email: `student@lms.com`
   - Password: `student123`

2. **View Available Courses**
   - On dashboard, scroll to "Available Courses" section
   - See list of courses with instructor names

3. **Enroll in a Course**
   - Click the "Enroll" button next to any course
   - Watch the page update without refreshing:
     - Success message appears
     - Button disappears
     - Course moves to "My Enrolled Courses"
     - Counter updates

4. **View Enrolled Courses**
   - See all your enrolled courses in "My Enrolled Courses" section
   - View enrollment date for each course

---

## 📁 Project Structure

```
WEBSYSTEM/
├── application/
│   ├── config/
│   │   ├── config.php (CSRF enabled)
│   │   ├── database.php
│   │   └── routes.php (enrollment routes)
│   ├── controllers/
│   │   ├── Auth.php (updated dashboard)
│   │   ├── Course.php (enroll method)
│   │   ├── CourseSeeder.php
│   │   └── UserSeeder.php
│   ├── models/
│   │   ├── Course_model.php
│   │   └── Enrollment_model.php
│   ├── migrations/
│   │   └── 003_create_enrollments_table.php
│   └── views/
│       ├── auth/
│       │   └── dashboard.php (enrollment UI)
│       └── templates/
│           ├── header.php
│           └── footer.php (AJAX script)
```

---

## 🚀 Quick Start Commands

```bash
# 1. Run migrations
php index.php migrate

# 2. Seed test users (if not done)
# Visit: http://localhost/WEBSYSTEM/index.php/userseeder

# 3. Seed courses
# Visit: http://localhost/WEBSYSTEM/index.php/courseseeder

# 4. Login as student
# Visit: http://localhost/WEBSYSTEM/
# Email: student@lms.com
# Password: student123

# 5. Test enrollment feature on dashboard
```

---

## ✨ Features Implemented

✅ Pivot table (enrollments) with proper foreign keys  
✅ Dynamic AJAX enrollment (no page reload)  
✅ Real-time UI updates  
✅ Bootstrap alerts for feedback  
✅ Duplicate enrollment prevention  
✅ Authorization checks  
✅ Input validation  
✅ SQL injection prevention  
✅ XSS protection  
✅ CSRF protection  
✅ Role-based access control  
✅ Server-side user ID validation  
✅ Animated transitions  
✅ Responsive design  
✅ Error handling  
✅ Success/failure feedback  

---

## 🎓 Learning Outcomes

This lab demonstrates:
1. **MVC Architecture** - Separation of concerns
2. **Database Relationships** - Pivot tables for many-to-many
3. **AJAX** - Asynchronous requests without page reload
4. **jQuery** - DOM manipulation and event handling
5. **Security** - CSRF, SQL injection, XSS prevention
6. **REST API** - JSON responses
7. **User Experience** - Real-time feedback
8. **Authorization** - Role-based access control
9. **Input Validation** - Client and server-side
10. **Professional Code** - Comments, error handling, standards

---

## 📞 Support

Kung may problema ka:
1. Check na running ang XAMPP (Apache + MySQL)
2. Verify database name: `lms_magallanoo`
3. Run migrations: `php index.php migrate`
4. Check browser console for JavaScript errors (F12)
5. Check CodeIgniter logs: `application/logs/`

---

**Congratulations! Tapos na ang Lab 5 at Course Enrollment System! 🎉**
