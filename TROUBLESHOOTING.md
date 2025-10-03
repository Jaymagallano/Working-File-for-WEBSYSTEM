# Troubleshooting Guide - RBAC Implementation

## Common Issues and Solutions

### 1. Blank Page / White Screen of Death

**Symptoms:**
- Page loads but shows nothing
- No error messages displayed

**Solutions:**
```php
// In index.php, temporarily enable error display:
error_reporting(E_ALL);
ini_set('display_errors', 1);

// In application/config/config.php, enable debug mode:
$config['log_threshold'] = 4; // Enable all logging
```

**Check:**
- PHP error logs (xampp/logs/php_error_log)
- Apache error logs (xampp/logs/error_log)
- CodeIgniter logs (application/logs/)

---

### 2. Database Connection Error

**Symptoms:**
- "Unable to connect to database"
- Dashboard doesn't load

**Solutions:**
```php
// Check application/config/database.php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',         // Check username
    'password' => '',             // Check password
    'database' => 'your_db_name', // Check database name exists
    'dbdriver' => 'mysqli',
);
```

**Verify:**
```bash
# In phpMyAdmin SQL tab:
SHOW DATABASES; -- Your database should be listed
USE your_database_name;
SHOW TABLES; -- Should show 'users' table
```

---

### 3. "Users table not found"

**Symptoms:**
- Error: "Table 'database.users' doesn't exist"

**Solutions:**
```sql
-- Run this SQL in phpMyAdmin:
CREATE TABLE `users` (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') DEFAULT 'student',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

### 4. Login Always Fails

**Symptoms:**
- Valid credentials show "Invalid email or password"

**Possible Causes & Solutions:**

**A. Password Hash Mismatch**
```php
// Test password verification:
// Create a test file: test_password.php
<?php
$password = 'admin123';
$hash_from_db = 'paste_hash_from_database_here';
if (password_verify($password, $hash_from_db)) {
    echo "Password is correct!";
} else {
    echo "Password verification failed!";
}
?>
```

**B. User Not Found in Database**
```sql
-- Check if user exists:
SELECT * FROM users WHERE email = 'admin@example.com';
```

**C. Session Not Starting**
```php
// In application/config/autoload.php
$autoload['libraries'] = array('database', 'session', 'form_validation');
```

---

### 5. Dashboard Shows No Data / Statistics are 0

**Symptoms:**
- Dashboard loads but shows 0 for all statistics
- Recent users table is empty

**Solutions:**

**Check if users exist:**
```sql
SELECT COUNT(*) as total FROM users;
SELECT role, COUNT(*) as count FROM users GROUP BY role;
```

**If no users exist, add test users:**
```sql
-- Use setup_test_users.php or run:
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`) 
VALUES 
('Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW()),
('Teacher', 'teacher@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', NOW()),
('Student', 'student@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW());
```

---

### 6. Navigation Menu Not Showing

**Symptoms:**
- Sidebar is empty
- No menu items visible

**Check:**
1. Verify session has role data:
```php
// In dashboard view, add temporarily:
<?php 
echo "<pre>";
print_r($this->session->userdata());
echo "</pre>";
?>
```

2. Verify header.php is loading:
```php
// In dashboard.php, check first line:
<?php $this->load->view('templates/header', ['page_title' => 'Dashboard']); ?>
```

3. Check if templates directory exists:
```bash
# Should exist:
application/views/templates/header.php
application/views/templates/footer.php
```

---

### 7. CSS Not Loading / Page Looks Unstyled

**Symptoms:**
- Dashboard has no styling
- Bootstrap classes not working

**Solutions:**

**A. Check Internet Connection**
- Bootstrap is loaded from CDN, needs internet

**B. Check Console for Errors**
- Press F12 in browser
- Look for 404 errors in Console tab

**C. Use Local Bootstrap (Optional)**
```html
<!-- Download Bootstrap and place in assets folder -->
<link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
```

---

### 8. Session Data Not Persisting

**Symptoms:**
- Keep getting redirected to login
- Session data disappears between pages

**Solutions:**

**A. Check Session Configuration**
```php
// In application/config/config.php
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = APPPATH . 'cache/';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;
```

**B. Check Cache Directory Permissions**
```bash
# Should be writable:
chmod 777 application/cache
```

**C. Check if sessions are being created**
```bash
# Should see files here:
ls application/cache/
```

---

### 9. "Call to undefined function base_url()"

**Symptoms:**
- Error when loading views

**Solution:**
```php
// In application/config/autoload.php
$autoload['helper'] = array('url', 'form');

// In application/config/config.php
$config['base_url'] = 'http://localhost/your-project-name/';
```

---

### 10. Routes Not Working / 404 Error

**Symptoms:**
- Going to /dashboard shows 404
- All routes return 404

**Solutions:**

**A. Check .htaccess file exists in root:**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

**B. Enable mod_rewrite in Apache:**
```bash
# In httpd.conf, uncomment:
LoadModule rewrite_module modules/mod_rewrite.so

# Change AllowOverride None to:
AllowOverride All
```

**C. Check routes configuration:**
```php
// In application/config/routes.php
$route['dashboard'] = 'auth/dashboard';
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['register'] = 'auth/register';
```

---

### 11. Flash Messages Not Showing

**Symptoms:**
- No success/error messages after login

**Check:**
```php
// In dashboard view:
<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>
```

**Verify session library is loaded:**
```php
// In application/config/autoload.php
$autoload['libraries'] = array('database', 'session', 'form_validation');
```

---

### 12. Form Validation Not Working

**Symptoms:**
- Can submit empty forms
- No validation errors shown

**Check:**
```php
// In Auth controller:
public function login() {
    if ($this->input->method() == 'post') {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run()) {
            // Process login
        }
    }
}
```

**In view:**
```php
<?= form_error('email', '<small class="text-danger">', '</small>') ?>
```

---

### 13. "Undefined index: role" Error

**Symptoms:**
- Error about undefined index when accessing role

**Solution:**
```php
// In dashboard method, always check:
if (!$this->session->userdata('logged_in')) {
    redirect('login');
}

$role = $this->session->userdata('role');

// Or use isset:
$role = isset($user['role']) ? $user['role'] : 'student';
```

---

### 14. GitHub Push Issues

**Symptoms:**
- Can't push to GitHub
- Authentication errors

**Solutions:**

**A. Check remote:**
```bash
git remote -v
# Should show your GitHub repository
```

**B. Re-add remote:**
```bash
git remote remove origin
git remote add origin https://github.com/username/repo-name.git
```

**C. Use Personal Access Token:**
- Go to GitHub Settings > Developer Settings > Personal Access Tokens
- Generate new token
- Use token as password when pushing

---

## Quick Diagnostic Commands

```bash
# Check if files exist:
dir application\controllers\Auth.php
dir application\views\auth\dashboard.php
dir application\views\templates\header.php

# Check PHP version (should be 7.0+):
php -v

# Check Apache is running:
netstat -ano | findstr :80

# Check MySQL is running:
netstat -ano | findstr :3306
```

## Database Diagnostic Queries

```sql
-- Verify users table structure:
DESCRIBE users;

-- Check all users:
SELECT id, name, email, role, created_at FROM users;

-- Count users by role:
SELECT role, COUNT(*) as count FROM users GROUP BY role;

-- Check for duplicate emails:
SELECT email, COUNT(*) as count FROM users GROUP BY email HAVING count > 1;

-- Verify password hashes (should be 60 characters):
SELECT id, name, LENGTH(password) as pwd_length FROM users;
```

## Testing Commands

```php
// Test database connection:
// Create: test_db.php in root
<?php
$conn = new mysqli('localhost', 'root', '', 'your_database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>

// Test session:
// Create: test_session.php in root
<?php
session_start();
$_SESSION['test'] = 'Session working!';
echo $_SESSION['test'];
?>
```

---

## Still Having Issues?

1. **Enable all error reporting:**
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```

2. **Check logs:**
   - `xampp/logs/php_error_log`
   - `application/logs/`

3. **Test components individually:**
   - Database connection
   - Session management
   - Route configuration
   - View rendering

4. **Compare with working code:**
   - Check against Lab 4 implementation
   - Verify all files are present
   - Compare database structure

5. **Ask for help:**
   - Screenshot the error
   - Note what you were doing
   - Show relevant code sections

---

**Remember:** Most issues are due to:
- Missing files
- Wrong database configuration
- Session not configured
- .htaccess issues
- Missing library in autoload
