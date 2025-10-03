<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Test Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Setup Test Users for RBAC</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>Note:</strong> This script will help you create test users for each role. 
                            Copy the SQL and run it in phpMyAdmin or your database tool.
                        </div>

                        <?php
                        // Generate password hashes
                        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
                        $teacher_password = password_hash('teacher123', PASSWORD_DEFAULT);
                        $student_password = password_hash('student123', PASSWORD_DEFAULT);
                        ?>

                        <h5>SQL Script - Copy and Run This:</h5>
                        <div class="bg-dark text-light p-3" style="border-radius: 5px;">
                            <pre style="color: #fff; margin: 0;"><code>-- Delete existing test users (optional)
DELETE FROM `users` WHERE `email` IN ('admin@example.com', 'teacher@example.com', 'student@example.com');

-- Insert Admin User
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) 
VALUES ('Admin User', 'admin@example.com', '<?= $admin_password ?>', 'admin', NOW(), NOW());

-- Insert Teacher User
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) 
VALUES ('Teacher Johnson', 'teacher@example.com', '<?= $teacher_password ?>', 'teacher', NOW(), NOW());

-- Insert Student User
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) 
VALUES ('Student Smith', 'student@example.com', '<?= $student_password ?>', 'student', NOW(), NOW());

-- Additional Test Students
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) 
VALUES 
('John Doe', 'john.doe@example.com', '<?= $student_password ?>', 'student', NOW(), NOW()),
('Jane Williams', 'jane.williams@example.com', '<?= $student_password ?>', 'student', NOW(), NOW()),
('Mike Brown', 'mike.brown@example.com', '<?= $student_password ?>', 'student', NOW(), NOW());

-- Additional Test Teachers
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) 
VALUES 
('Prof. Anderson', 'prof.anderson@example.com', '<?= $teacher_password ?>', 'teacher', NOW(), NOW()),
('Dr. Martinez', 'dr.martinez@example.com', '<?= $teacher_password ?>', 'teacher', NOW(), NOW());</code></pre>
                        </div>

                        <hr>

                        <h5>Test Login Credentials:</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card border-danger mb-3">
                                    <div class="card-header bg-danger text-white">
                                        <strong>Admin</strong>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Email:</strong><br><code>admin@example.com</code></p>
                                        <p><strong>Password:</strong><br><code>admin123</code></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-info mb-3">
                                    <div class="card-header bg-info text-white">
                                        <strong>Teacher</strong>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Email:</strong><br><code>teacher@example.com</code></p>
                                        <p><strong>Password:</strong><br><code>teacher123</code></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success mb-3">
                                    <div class="card-header bg-success text-white">
                                        <strong>Student</strong>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Email:</strong><br><code>student@example.com</code></p>
                                        <p><strong>Password:</strong><br><code>student123</code></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <strong>Important:</strong> After running the SQL:
                            <ol class="mb-0 mt-2">
                                <li>Go to your CodeIgniter application</li>
                                <li>Access the login page</li>
                                <li>Test each user role</li>
                                <li>Verify different dashboard views</li>
                                <li>Check navigation menu differences</li>
                            </ol>
                        </div>

                        <div class="text-center mt-4">
                            <a href="index.php" class="btn btn-primary btn-lg">
                                Go to Application
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Alternative: Update Existing User Role</h5>
                    </div>
                    <div class="card-body">
                        <p>If you already have users and want to change their role:</p>
                        <div class="bg-light p-3" style="border-radius: 5px;">
                            <pre style="margin: 0;"><code>-- Change a user to admin
UPDATE `users` SET `role` = 'admin' WHERE `email` = 'your.email@example.com';

-- Change a user to teacher
UPDATE `users` SET `role` = 'teacher' WHERE `email` = 'your.email@example.com';

-- Change a user to student
UPDATE `users` SET `role` = 'student' WHERE `email` = 'your.email@example.com';</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5 mb-3 text-muted">
        <p>Delete this file after setup for security reasons</p>
    </footer>
</body>
</html>
