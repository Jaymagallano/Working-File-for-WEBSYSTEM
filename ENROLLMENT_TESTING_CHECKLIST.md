# 📋 COURSE ENROLLMENT SYSTEM - Testing Checklist

## Pre-Testing Setup

- [ ] XAMPP is running (Apache + MySQL)
- [ ] Database `lms_magallanoo` exists
- [ ] Migrations completed: `php index.php migrate`
- [ ] Users seeded: Visit `/userseeder`
- [ ] Courses seeded: Visit `/courseseeder`

---

## Step 7: Test the Application Thoroughly

### ✅ Test 1: Student Login
- [ ] Navigate to `http://localhost/WEBSYSTEM/`
- [ ] Login with:
  - Email: `student@lms.com`
  - Password: `student123`
- [ ] Verify redirect to dashboard
- [ ] Verify "Welcome, Student User!" message

### ✅ Test 2: View Dashboard Sections
- [ ] **My Enrolled Courses** section is visible
- [ ] Shows "You are not enrolled in any courses yet" (if new student)
- [ ] **Available Courses** section is visible
- [ ] Shows list of 6 courses with Enroll buttons
- [ ] Each course shows:
  - [ ] Course title
  - [ ] Course description
  - [ ] Instructor name
  - [ ] Blue "Enroll" button

### ✅ Test 3: Enroll in a Course (AJAX Test)
- [ ] Open Browser Developer Tools (F12)
- [ ] Go to "Network" tab
- [ ] Click "Enroll" button on any course
- [ ] **Verify NO page reload occurs**
- [ ] Verify in Network tab:
  - [ ] Request to `course/enroll` appears
  - [ ] Method is POST
  - [ ] Status is 200 OK
  - [ ] Response is JSON format

### ✅ Test 4: Check AJAX Response
- [ ] Click on the `enroll` request in Network tab
- [ ] Go to "Headers" tab
- [ ] Verify Form Data contains:
  - [ ] `course_id`: (number)
  - [ ] `csrf_test_name`: (token value)
- [ ] Go to "Response" tab
- [ ] Verify JSON response:
  ```json
  {
    "success": true,
    "message": "Successfully enrolled in...",
    "course": {
      "id": 1,
      "title": "Course Name",
      "instructor_name": "Teacher User",
      "enrolled_at": "Jan 13, 2025"
    },
    "csrf_test_name": "new_token_value"
  }
  ```

### ✅ Test 5: Verify UI Updates (No Page Reload)
After clicking Enroll:
- [ ] Green success alert appears at top
- [ ] Alert says "Successfully enrolled in [Course Name]!"
- [ ] Enroll button disappears
- [ ] Course item fades out from Available Courses
- [ ] Course appears in "My Enrolled Courses" section
- [ ] Enrolled count increases by 1
- [ ] New course shows:
  - [ ] Course title
  - [ ] Instructor name
  - [ ] "Enrolled" badge (green)
  - [ ] Enrollment date

### ✅ Test 6: Enroll in Multiple Courses
- [ ] Enroll in 2-3 more courses
- [ ] Each enrollment updates UI dynamically
- [ ] No page refreshes occur
- [ ] Counter updates correctly
- [ ] All enrolled courses appear in order

### ✅ Test 7: Verify Database Records
- [ ] Open phpMyAdmin
- [ ] Select `lms_magallanoo` database
- [ ] Click on `enrollments` table
- [ ] Verify records exist:
  - [ ] `id` is auto-incrementing
  - [ ] `user_id` matches your student ID
  - [ ] `course_id` matches enrolled courses
  - [ ] `enrollment_date` has current timestamp

---

## Step 9: Vulnerability Checking

### 🛡️ Test 1: Authorization Bypass
**Objective:** Verify unauthenticated users cannot enroll

**Steps:**
- [ ] Logout from the application
- [ ] Open browser console (F12)
- [ ] Run command:
  ```javascript
  $.post('http://localhost/WEBSYSTEM/index.php/course/enroll', {
      course_id: 1
  }, function(response) {
      console.log(response);
  });
  ```
- [ ] **Expected:** Error response: "Unauthorized access. Please login."
- [ ] **Result:** ✅ PASS / ❌ FAIL

**Alternative Test with Postman:**
- [ ] Open Postman
- [ ] Create POST request to: `http://localhost/WEBSYSTEM/index.php/course/enroll`
- [ ] Body: `{ "course_id": 1 }`
- [ ] Send without authentication
- [ ] **Expected:** 401/403 or error message
- [ ] **Result:** ✅ PASS / ❌ FAIL

---

### 🛡️ Test 2: SQL Injection Prevention
**Objective:** Verify malicious SQL input is rejected

**Steps:**
- [ ] Login as student
- [ ] Open browser console (F12)
- [ ] Run command:
  ```javascript
  $.post('http://localhost/WEBSYSTEM/index.php/course/enroll', {
      course_id: "1 OR 1=1",
      csrf_test_name: '<?= $this->security->get_csrf_hash() ?>'
  }, function(response) {
      console.log(response);
  });
  ```
- [ ] **Expected:** "Invalid course ID." (not numeric validation)
- [ ] **Result:** ✅ PASS / ❌ FAIL

**Additional SQL Injection Tests:**
- [ ] Try: `course_id: "1; DROP TABLE enrollments--"`
- [ ] Try: `course_id: "1' OR '1'='1"`
- [ ] Try: `course_id: "1 UNION SELECT * FROM users--"`
- [ ] All should be rejected with "Invalid course ID"
- [ ] **Result:** ✅ PASS / ❌ FAIL

---

### 🛡️ Test 3: CSRF Protection
**Objective:** Verify requests without CSRF token are rejected

**Steps:**
- [ ] Login as student
- [ ] Open browser console
- [ ] Run command WITHOUT CSRF token:
  ```javascript
  $.ajax({
      url: 'http://localhost/WEBSYSTEM/index.php/course/enroll',
      type: 'POST',
      data: { course_id: 1 },
      success: function(response) { console.log(response); },
      error: function(xhr) { console.log(xhr.responseText); }
  });
  ```
- [ ] **Expected:** 403 Forbidden or CSRF error
- [ ] **Result:** ✅ PASS / ❌ FAIL

**Verify CSRF is Enabled:**
- [ ] Open `application/config/config.php`
- [ ] Check: `$config['csrf_protection'] = TRUE;`
- [ ] Check: `$config['csrf_regenerate'] = TRUE;`
- [ ] **Result:** ✅ PASS / ❌ FAIL

---

### 🛡️ Test 4: Data Tampering Prevention
**Objective:** Verify server uses session user_id, not client input

**Steps:**
- [ ] Login as student (user_id = 3)
- [ ] Open browser console
- [ ] Try to enroll another user:
  ```javascript
  $.post('http://localhost/WEBSYSTEM/index.php/course/enroll', {
      course_id: 1,
      user_id: 999,  // Trying to tamper
      csrf_test_name: $('[name=csrf_test_name]').val()
  }, function(response) {
      console.log(response);
  });
  ```
- [ ] Check database `enrollments` table
- [ ] **Expected:** `user_id` should be 3 (your session), NOT 999
- [ ] **Result:** ✅ PASS / ❌ FAIL

**Code Verification:**
- [ ] Open `application/controllers/Course.php`
- [ ] Find line: `$user_id = $this->session->userdata('user_id');`
- [ ] Verify NO line uses: `$this->input->post('user_id')`
- [ ] **Result:** ✅ PASS / ❌ FAIL

---

### 🛡️ Test 5: Input Validation
**Objective:** Verify invalid course IDs are rejected

**Test 5a: Non-existent Course**
- [ ] Login as student
- [ ] Open browser console
- [ ] Run:
  ```javascript
  $.post('http://localhost/WEBSYSTEM/index.php/course/enroll', {
      course_id: 99999,
      csrf_test_name: $('[name=csrf_test_name]').val()
  }, function(response) {
      console.log(response);
  });
  ```
- [ ] **Expected:** "Course not found."
- [ ] **Result:** ✅ PASS / ❌ FAIL

**Test 5b: Empty Course ID**
- [ ] Try: `course_id: ""`
- [ ] **Expected:** "Invalid course ID."
- [ ] **Result:** ✅ PASS / ❌ FAIL

**Test 5c: Null Course ID**
- [ ] Try: `course_id: null`
- [ ] **Expected:** "Invalid course ID."
- [ ] **Result:** ✅ PASS / ❌ FAIL

**Test 5d: String Course ID**
- [ ] Try: `course_id: "abc"`
- [ ] **Expected:** "Invalid course ID."
- [ ] **Result:** ✅ PASS / ❌ FAIL

---

### 🛡️ Test 6: Duplicate Enrollment Prevention
**Objective:** Verify students cannot enroll twice in same course

**Steps:**
- [ ] Login as student
- [ ] Enroll in a course normally (click Enroll button)
- [ ] Wait for success message
- [ ] Open browser console
- [ ] Try to enroll again in same course:
  ```javascript
  $.post('http://localhost/WEBSYSTEM/index.php/course/enroll', {
      course_id: 1,  // Same course
      csrf_test_name: $('[name=csrf_test_name]').val()
  }, function(response) {
      console.log(response);
  });
  ```
- [ ] **Expected:** "You are already enrolled in this course."
- [ ] **Result:** ✅ PASS / ❌ FAIL

**Database Verification:**
- [ ] Check `enrollments` table
- [ ] Verify no duplicate records (same user_id + course_id)
- [ ] **Result:** ✅ PASS / ❌ FAIL

---

### 🛡️ Test 7: Role-Based Access Control
**Objective:** Verify only students can enroll

**Test 7a: Teacher Attempting to Enroll**
- [ ] Logout
- [ ] Login as teacher:
  - Email: `teacher@lms.com`
  - Password: `teacher123`
- [ ] Navigate to dashboard
- [ ] Verify no "Available Courses" or "Enroll" buttons visible
- [ ] Open browser console
- [ ] Try to enroll:
  ```javascript
  $.post('http://localhost/WEBSYSTEM/index.php/course/enroll', {
      course_id: 1,
      csrf_test_name: $('[name=csrf_test_name]').val()
  }, function(response) {
      console.log(response);
  });
  ```
- [ ] **Expected:** "Only students can enroll in courses."
- [ ] **Result:** ✅ PASS / ❌ FAIL

**Test 7b: Admin Attempting to Enroll**
- [ ] Logout
- [ ] Login as admin:
  - Email: `admin@lms.com`
  - Password: `admin123`
- [ ] Try same enrollment test
- [ ] **Expected:** "Only students can enroll in courses."
- [ ] **Result:** ✅ PASS / ❌ FAIL

---

## 📸 Screenshots Checklist

### Screenshot 1: Database Structure
- [ ] Open phpMyAdmin
- [ ] Navigate to `lms_magallanoo` database
- [ ] Click `enrollments` table
- [ ] Click "Structure" tab
- [ ] **Capture:** Table structure showing:
  - id (INT, Primary Key, Auto Increment)
  - user_id (INT)
  - course_id (INT)
  - enrollment_date (DATETIME)

### Screenshot 2: Student Dashboard (Before Enrollment)
- [ ] Login as student
- [ ] Dashboard loaded
- [ ] **Capture:** Full page showing:
  - "Available Courses" section
  - List of courses with Enroll buttons
  - "My Enrolled Courses" (empty or with message)
  - Sidebar navigation
  - User info at top

### Screenshot 3: Browser Developer Tools (Network Tab)
- [ ] Open Developer Tools (F12)
- [ ] Go to "Network" tab
- [ ] Click "Enroll" button on a course
- [ ] Click on the `enroll` request
- [ ] **Capture:** Developer Tools showing:
  - Request URL: `/course/enroll`
  - Request Method: POST
  - Status: 200
  - Form Data tab showing: course_id, csrf_test_name
  - Response tab showing JSON success response

### Screenshot 4: Student Dashboard (After Enrollment)
- [ ] After successful enrollment (no page refresh)
- [ ] **Capture:** Dashboard showing:
  - Green success alert at top
  - Course in "My Enrolled Courses" section
  - Course removed from "Available Courses"
  - Updated enrollment counter
  - "Enrolled" badge on course

### Screenshot 5: Database Enrollment Records
- [ ] Open phpMyAdmin
- [ ] Select `lms_magallanoo` database
- [ ] Click `enrollments` table
- [ ] Click "Browse" tab
- [ ] **Capture:** Table data showing:
  - Multiple enrollment records
  - user_id values
  - course_id values
  - enrollment_date timestamps

### Screenshot 6: Security Test (Authorization Bypass)
- [ ] Logout
- [ ] Open browser console
- [ ] Run unauthorized enroll attempt
- [ ] **Capture:** Console showing:
  - POST request
  - Error response: "Unauthorized access. Please login."

### Screenshot 7: Security Test (SQL Injection)
- [ ] Login as student
- [ ] Open console
- [ ] Run SQL injection test
- [ ] **Capture:** Console showing:
  - Malicious input: "1 OR 1=1"
  - Error response: "Invalid course ID."

---

## Final Checklist

### Functionality Tests
- [ ] Enrollment works without page reload
- [ ] Success messages display correctly
- [ ] UI updates dynamically
- [ ] Counter updates properly
- [ ] Multiple enrollments work
- [ ] Database records created correctly

### Security Tests
- [ ] Authorization bypass prevented
- [ ] SQL injection blocked
- [ ] CSRF protection active
- [ ] Data tampering prevented
- [ ] Input validation works
- [ ] Duplicate enrollment blocked
- [ ] Role-based access enforced

### Documentation
- [ ] All 7 screenshots captured
- [ ] Security test results documented
- [ ] Code is clean and commented
- [ ] No console errors

### GitHub
- [ ] All changes committed
- [ ] Descriptive commit message
- [ ] Pushed to repository

---

## 🎉 Completion Status

**Date Completed:** _______________

**Total Tests:** 20+

**Tests Passed:** ______ / 20+

**Tests Failed:** ______

**Overall Result:** ✅ PASS / ❌ FAIL

---

## Notes / Issues Found

```
[Write any issues or observations here]
```

---

**Congratulations on completing the Course Enrollment System testing! 🎓**
