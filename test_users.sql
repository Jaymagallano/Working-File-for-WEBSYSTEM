-- Test Users for Role-Based Access Control
-- Run this SQL in your database to create test users

-- Admin User
-- Email: admin@example.com
-- Password: admin123
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
('Admin User', 'admin@example.com', '$2y$10$YourHashedPasswordHere', 'admin', NOW(), NOW());

-- Teacher User
-- Email: teacher@example.com
-- Password: teacher123
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
('Teacher User', 'teacher@example.com', '$2y$10$YourHashedPasswordHere', 'teacher', NOW(), NOW());

-- Student User
-- Email: student@example.com
-- Password: student123
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
('Student User', 'student@example.com', '$2y$10$YourHashedPasswordHere', 'student', NOW(), NOW());

-- Additional Test Users
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
('John Doe', 'john@example.com', '$2y$10$YourHashedPasswordHere', 'student', NOW(), NOW()),
('Jane Smith', 'jane@example.com', '$2y$10$YourHashedPasswordHere', 'teacher', NOW(), NOW()),
('Bob Wilson', 'bob@example.com', '$2y$10$YourHashedPasswordHere', 'student', NOW(), NOW());

-- NOTE: To generate proper password hashes, use the registration form or run this PHP code:
-- <?php echo password_hash('your_password', PASSWORD_DEFAULT); ?>

-- For quick testing, here are pre-generated hashes:
-- admin123: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
-- teacher123: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
-- student123: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

-- Alternative method: Update existing users to change their roles
-- UPDATE `users` SET `role` = 'admin' WHERE `email` = 'your_email@example.com';
-- UPDATE `users` SET `role` = 'teacher' WHERE `email` = 'your_email@example.com';
-- UPDATE `users` SET `role` = 'student' WHERE `email` = 'your_email@example.com';
