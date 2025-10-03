# Learning Management System - Role-Based Access Control

![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3.1.13-orange)
![PHP](https://img.shields.io/badge/PHP-7.0+-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.1.3-purple)
![License](https://img.shields.io/badge/License-MIT-green)

## 📖 About

This is a Learning Management System (LMS) with Role-Based Access Control (RBAC) implementation built with CodeIgniter 3. The system supports three distinct user roles:

- **Admin** - Full system access and user management
- **Teacher** - Course management, student grading, and content creation
- **Student** - Course enrollment, assignment submission, and grade viewing

## ✨ Features

### Authentication & Authorization
- ✅ Secure user registration and login
- ✅ Password hashing with PHP's `password_hash()`
- ✅ Session-based authentication
- ✅ Role-based access control (RBAC)
- ✅ Protected routes and pages

### Role-Specific Dashboards
- ✅ **Admin Dashboard**: System statistics, user management, reports
- ✅ **Teacher Dashboard**: Student lists, course management, grading tools
- ✅ **Student Dashboard**: Course enrollment, assignments, grades

### Dynamic Navigation
- ✅ Role-specific menu items
- ✅ Responsive sidebar navigation
- ✅ Bootstrap Icons integration
- ✅ Active page highlighting

### Security Features
- ✅ XSS protection
- ✅ SQL injection prevention
- ✅ CSRF protection (CodeIgniter built-in)
- ✅ Input validation and sanitization
- ✅ Secure password storage

### UI/UX
- ✅ Modern Bootstrap 5 design
- ✅ Responsive layout (mobile-friendly)
- ✅ Smooth animations and transitions
- ✅ Color-coded role indicators
- ✅ Professional gradient themes

## 🛠️ Technology Stack

- **Backend**: PHP 7.0+ with CodeIgniter 3.1.13
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **CSS Framework**: Bootstrap 5.1.3
- **Icons**: Bootstrap Icons 1.7.2
- **Version Control**: Git

## 📋 Requirements

- PHP >= 7.0
- MySQL >= 5.7
- Apache Server (XAMPP/WAMP/MAMP)
- Composer (optional)
- Git

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/your-repo-name.git
cd your-repo-name
```

### 2. Configure Database
```php
// application/config/database.php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'your_database_name',
    'dbdriver' => 'mysqli',
);
```

### 3. Create Database Tables
```bash
# Option A: Run migration
php index.php migrate

# Option B: Import SQL manually
# Open phpMyAdmin and run the migration SQL
```

### 4. Create Test Users
```bash
# Open in browser
http://localhost/your-project/setup_test_users.php

# Or insert manually via phpMyAdmin
```

### 5. Configure Base URL
```php
// application/config/config.php
$config['base_url'] = 'http://localhost/your-project/';
```

### 6. Access the Application
```
Login Page: http://localhost/your-project/login
Register: http://localhost/your-project/register
```

## 👥 Test Accounts

After running the setup script, you can use these test accounts:

| Role    | Email                  | Password    |
|---------|------------------------|-------------|
| Admin   | admin@example.com      | admin123    |
| Teacher | teacher@example.com    | teacher123  |
| Student | student@example.com    | student123  |

## 📁 Project Structure

```
project-root/
├── application/
│   ├── controllers/
│   │   └── Auth.php                 # Authentication controller
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.php           # Login page
│   │   │   ├── register.php        # Registration page
│   │   │   └── dashboard.php       # Unified dashboard
│   │   └── templates/
│   │       ├── header.php          # Dynamic header
│   │       └── footer.php          # Footer template
│   ├── models/                     # Database models
│   ├── config/
│   │   ├── autoload.php           # Auto-loaded libraries
│   │   ├── config.php             # Main configuration
│   │   ├── database.php           # Database configuration
│   │   └── routes.php             # Route definitions
│   └── migrations/
│       └── 001_create_users_table.php
├── system/                         # CodeIgniter core files
├── generate_password.php           # Password hash generator
├── setup_test_users.php           # Test user setup utility
└── README.md                       # This file
```

## 🎯 Usage

### For Administrators
1. Login with admin credentials
2. View system-wide statistics
3. Manage users, courses, and settings
4. Generate reports

### For Teachers
1. Login with teacher credentials
2. View enrolled students
3. Create and manage courses
4. Grade assignments
5. Upload course materials

### For Students
1. Login with student credentials
2. View enrolled courses
3. Submit assignments
4. Check grades
5. Access course materials

## 🔒 Security

This implementation includes several security measures:

- **Password Hashing**: Using PHP's `password_hash()` with bcrypt
- **Input Sanitization**: XSS filtering enabled
- **SQL Injection Prevention**: Using CodeIgniter's Query Builder
- **Session Security**: Secure session configuration
- **Access Control**: Role-based authorization checks
- **CSRF Protection**: CodeIgniter's built-in CSRF protection

## 📸 Screenshots

### Admin Dashboard
![Admin Dashboard](screenshots/admin-dashboard.png)

### Teacher Dashboard
![Teacher Dashboard](screenshots/teacher-dashboard.png)

### Student Dashboard
![Student Dashboard](screenshots/student-dashboard.png)

## 🧪 Testing

Follow the testing checklist:

1. Database setup verification
2. User authentication (login/logout)
3. Role-based dashboard access
4. Navigation menu differences
5. Statistics and data display
6. Security measures

For detailed testing procedures, see [TESTING_CHECKLIST.md](TESTING_CHECKLIST.md)

## 🐛 Troubleshooting

Common issues and solutions:

- **Blank page**: Enable error reporting in `index.php`
- **Database connection error**: Check `database.php` configuration
- **Login fails**: Verify password hashes and user data
- **Routes not working**: Check `.htaccess` and `routes.php`

For detailed troubleshooting, see [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

## 📚 Documentation

- [Implementation Guide](RBAC_IMPLEMENTATION_GUIDE.md) - Complete implementation details
- [Testing Checklist](TESTING_CHECKLIST.md) - Comprehensive testing guide
- [Troubleshooting Guide](TROUBLESHOOTING.md) - Common issues and solutions
- [Quick Start (Tagalog)](QUICK_START_TAGALOG.md) - Quick start in Filipino

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

## 🙏 Acknowledgments

- CodeIgniter Framework
- Bootstrap Team
- Bootstrap Icons
- PHP Community

## 📅 Version History

- **1.0.0** (2025-01-XX)
  - Initial release
  - Complete RBAC implementation
  - Three user roles (Admin, Teacher, Student)
  - Dynamic navigation system
  - Responsive dashboard design

## 🔮 Future Enhancements

- [ ] Password reset functionality
- [ ] Email verification
- [ ] Two-factor authentication (2FA)
- [ ] Advanced user management
- [ ] Course creation and management
- [ ] Assignment submission system
- [ ] Grading system
- [ ] Real-time notifications
- [ ] File upload system
- [ ] Calendar integration
- [ ] Discussion forums
- [ ] Live chat support

## 📞 Support

If you encounter any issues or have questions:

1. Check the [Troubleshooting Guide](TROUBLESHOOTING.md)
2. Review the [Testing Checklist](TESTING_CHECKLIST.md)
3. Open an issue on GitHub
4. Contact the author

---

**Made with ❤️ for ITE311 Course Project**

*Role-Based Access Control Implementation*
# WEBSYSTEMTRY
