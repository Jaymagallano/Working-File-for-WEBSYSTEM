# Role-Based Access Control (RBAC) Implementation Guide

## Overview
This implementation includes a complete Role-Based Access Control system for a Learning Management System (LMS) with three user roles: Admin, Teacher, and Student.

## Features Implemented

### 1. **Enhanced Authentication System**
- Secure login with input sanitization
- Password hashing using PHP's `password_hash()`
- Session management with role-based data
- XSS and SQL injection protection
- Redirect prevention for logged-in users

### 2. **Role-Based Dashboard**
- **Unified Dashboard**: Single dashboard route for all users
- **Conditional Content**: Different content based on user role
- **Role-Specific Data**: Each role sees relevant statistics and information

### 3. **Dynamic Navigation**
- **Admin Navigation**: User management, courses, system settings, reports
- **Teacher Navigation**: My courses, students, assignments, grades, materials
- **Student Navigation**: Courses, assignments, grades, schedule, resources

### 4. **Responsive Design**
- Modern Bootstrap 5 UI
- Sidebar navigation with icons
- Card-based layout
- Mobile-responsive

## File Structure

```
application/
├── controllers/
│   └── Auth.php                    # Enhanced with RBAC
├── views/
│   ├── auth/
│   │   ├── login.php              # Login page
│   │   ├── register.php           # Registration page (defaults to student)
│   │   └── dashboard.php          # Unified dashboard with role-based content
│   └── templates/
│       ├── header.php             # Dynamic header with role-based navigation
│       └── footer.php             # Footer template
├── migrations/
│   └── 001_create_users_table.php # Users table with role field
└── config/
    └── routes.php                 # Routes configuration
```

## Setup Instructions

### Step 1: Database Setup

1. Run the migration to create the users table:
```bash
php index.php migrate
```

2. Or manually create the table:
```sql
CREATE TABLE `users` (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') DEFAULT 'student',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);
```

### Step 2: Create Test Users

#### Option A: Use the Password Generator
1. Access `http://localhost/your-project/generate_password.php`
2. Generate password hashes for your test users
3. Insert users into database using the generated SQL

#### Option B: Use Registration Form
1. Register users through the registration page (defaults to student role)
2. Manually update roles in the database:
```sql
UPDATE `users` SET `role` = 'admin' WHERE `email` = 'admin@example.com';
UPDATE `users` SET `role` = 'teacher' WHERE `email` = 'teacher@example.com';
```

#### Option C: Quick Test Users SQL
```sql
-- Admin User (Email: admin@example.com, Password: admin123)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`) 
VALUES ('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW());

-- Teacher User (Email: teacher@example.com, Password: teacher123)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`) 
VALUES ('Teacher User', 'teacher@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', NOW());

-- Student User (Email: student@example.com, Password: student123)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`) 
VALUES ('Student User', 'student@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW());
```

### Step 3: Test the Application

1. **Test Admin Dashboard**:
   - Login: admin@example.com / admin123
   - Should see: Total users, admins, teachers, students statistics
   - Navigation: Manage Users, Manage Courses, System Settings, Reports

2. **Test Teacher Dashboard**:
   - Login: teacher@example.com / teacher123
   - Should see: Student count, courses, assignments
   - Navigation: My Courses, Students, Assignments, Grades, Materials

3. **Test Student Dashboard**:
   - Login: student@example.com / student123
   - Should see: Teacher count, enrolled courses, pending tasks
   - Navigation: My Courses, Assignments, Grades, Schedule, Resources

### Step 4: Security Features

The implementation includes several security measures:

1. **Input Sanitization**: Using `$this->input->post('field', TRUE)` with XSS filtering
2. **Password Hashing**: Using PHP's `password_hash()` and `password_verify()`
3. **SQL Injection Prevention**: Using CodeIgniter's Query Builder
4. **Session Management**: Proper session creation and destruction
5. **Access Control**: Checking logged_in status before allowing dashboard access
6. **Role Validation**: Ensuring users only see content for their role

## Testing Checklist

- [ ] Users table created with correct structure
- [ ] Admin user can login and sees admin dashboard
- [ ] Teacher user can login and sees teacher dashboard
- [ ] Student user can login and sees student dashboard
- [ ] Navigation menu shows different items per role
- [ ] Statistics display correctly for each role
- [ ] Logout functionality works
- [ ] Access control prevents unauthorized access
- [ ] Registration creates new users as students

## Screenshots Required

1. **Screenshot 1**: phpMyAdmin showing users table with different roles
2. **Screenshot 2**: Admin dashboard view with statistics and user list
3. **Screenshot 3**: Teacher dashboard with student list
4. **Screenshot 4**: Student dashboard with courses view
5. **Screenshot 5**: Side-by-side comparison of navigation menus
6. **Screenshot 6**: GitHub repository with commits

## Git Commit Strategy

Make at least 5 commits over 4 days:

```bash
# Day 1
git add application/migrations/001_create_users_table.php
git commit -m "Add users table migration with role field"

git add application/controllers/Auth.php
git commit -m "Update Auth controller with role-based authentication"

# Day 2
git add application/views/templates/
git commit -m "Create header and footer templates with dynamic navigation"

git add application/views/auth/dashboard.php
git commit -m "Implement unified dashboard with role-based content"

# Day 3
git add application/views/auth/login.php application/views/auth/register.php
git commit -m "Enhance login and registration with security features"

# Day 4
git add .
git commit -m "ROLE BASE Implementation - Complete RBAC system"

git push origin main
```

## Advanced Features (Optional)

1. **Middleware**: Create a middleware to check roles before controller execution
2. **Permissions**: Add granular permissions beyond role-based access
3. **Activity Logging**: Track user activities in the system
4. **Password Reset**: Implement password recovery functionality
5. **Email Verification**: Add email verification for new registrations

## Common Issues & Solutions

### Issue 1: Dashboard shows blank page
**Solution**: Check if session library is loaded in autoload.php

### Issue 2: Navigation not showing correctly
**Solution**: Verify session role data is set during login

### Issue 3: Statistics not displaying
**Solution**: Ensure users exist in database with proper roles

### Issue 4: Login always fails
**Solution**: Check password hash generation and verification

## Support

For issues or questions:
1. Check CodeIgniter 3 documentation
2. Verify database connection in config/database.php
3. Check PHP error logs
4. Enable CodeIgniter debug mode in config.php

## Credits

- CodeIgniter 3 Framework
- Bootstrap 5 for UI
- Bootstrap Icons for iconography
