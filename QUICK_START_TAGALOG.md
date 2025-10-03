# Mabilis na Gabay sa Pagsisimula (Quick Start Guide)

## 🎯 Ano ang Ginawa Natin?

Ginawa natin ang isang **Role-Based Access Control (RBAC)** system para sa Learning Management System (LMS). May tatlong uri ng users:
1. **Admin** - Nag-manage ng lahat
2. **Teacher** - Nagtuturo at nag-grade
3. **Student** - Nag-aaral

---

## 📝 Mga Hakbang para Simulan

### Hakbang 1: I-check ang Database (2 minuto)

1. Buksan ang **phpMyAdmin** (http://localhost/phpmyadmin)
2. I-click ang database mo sa left side
3. Tignan kung may "users" table na
4. Kung wala pa, i-run ang migration:
   ```bash
   php index.php migrate
   ```
   O kaya i-paste sa SQL tab:
   ```sql
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
   );
   ```

### Hakbang 2: Gumawa ng Test Users (3 minuto)

**Pinakamadali:** Buksan sa browser:
```
http://localhost/your-project-name/setup_test_users.php
```

Makikita mo ang SQL script. **Copy and paste** sa phpMyAdmin SQL tab, then click "Go".

**O kaya**, manually i-type sa phpMyAdmin SQL tab:
```sql
-- Admin User
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`) 
VALUES ('Admin User', 'admin@example.com', 
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
'admin', NOW());

-- Teacher User
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`) 
VALUES ('Teacher User', 'teacher@example.com', 
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
'teacher', NOW());

-- Student User
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`) 
VALUES ('Student User', 'student@example.com', 
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
'student', NOW());
```

### Hakbang 3: I-test ang bawat Role (5 minuto)

**Test 1: Admin**
1. Pumunta sa: `http://localhost/your-project/login`
2. Login:
   - Email: `admin@example.com`
   - Password: `admin123`
3. Dapat makita mo:
   - 4 boxes na may numbers (Total Users, Admins, Teachers, Students)
   - Table ng recent users
   - Navigation menu na may "Manage Users", "System Settings", etc.

**Test 2: Teacher**
1. I-logout muna
2. Login ulit:
   - Email: `teacher@example.com`
   - Password: `teacher123`
3. Dapat makita mo:
   - 3 boxes (Students, Courses, Assignments)
   - Table ng students
   - **Iba na** ang navigation menu - may "My Courses", "Grades", etc.

**Test 3: Student**
1. I-logout muna
2. Login ulit:
   - Email: `student@example.com`
   - Password: `student123`
3. Dapat makita mo:
   - 3 boxes (Teachers, Courses, Tasks)
   - My Courses section
   - **Iba na naman** ang navigation - may "Assignments", "Schedule", etc.

### Hakbang 4: Kumuha ng Screenshots (10 minuto)

Kailangan mo ng **6 screenshots**:

**Screenshot 1: Database**
- Buksan phpMyAdmin
- I-click ang users table
- I-click "Browse"
- Screenshot ng table na may different roles
- Dapat makita: admin, teacher, student sa role column

**Screenshot 2: Admin Dashboard**
- Login bilang admin
- Screenshot ng dashboard
- Dapat makita: lahat ng statistics at table

**Screenshot 3: Teacher Dashboard**
- Login bilang teacher
- Screenshot ng dashboard
- Dapat makita: teacher-specific content

**Screenshot 4: Student Dashboard**
- Login bilang student
- Screenshot ng dashboard
- Dapat makita: student-specific content

**Screenshot 5: Navigation Comparison**
- Gumawa ng collage/side-by-side:
  - Admin navigation sa left
  - Student navigation sa right
- Para makita ang difference

**Screenshot 6: GitHub Repository**
- Buksan ang GitHub repo mo
- Screenshot ng commits
- Dapat makita: 5+ commits, "ROLE BASE Implementation"

### Hakbang 5: Git Commits (Kailangan 4 na araw)

**IMPORTANTE:** Dapat may commits ka sa **4 different days**.

**Day 1 (Unang Araw):**
```bash
git add application/migrations/001_create_users_table.php
git commit -m "Add users table migration with role field"
git push origin main

git add application/controllers/Auth.php
git commit -m "Update Auth controller with role-based authentication"
git push origin main
```

**Day 2 (Pangalawang Araw):**
```bash
git add application/views/templates/
git commit -m "Create header and footer templates with dynamic navigation"
git push origin main
```

**Day 3 (Pangatlong Araw):**
```bash
git add application/views/auth/dashboard.php
git commit -m "Implement unified dashboard with role-based content"
git push origin main
```

**Day 4 (Pang-apat na Araw):**
```bash
git add .
git commit -m "ROLE BASE Implementation - Complete RBAC system"
git push origin main
```

---

## 🔧 Kung May Mali (Troubleshooting)

### Problem 1: "White Screen" o Walang Laman
**Solusyon:**
1. I-check kung naka-on ang XAMPP
2. I-refresh ang page (Ctrl + F5)
3. I-check ang database name sa `application/config/database.php`

### Problem 2: Hindi Maka-login
**Solusyon:**
1. I-check sa phpMyAdmin kung may users na
2. Siguraduhing tama ang email at password
3. Try mo ulit i-insert ang users gamit ang SQL sa taas

### Problem 3: Walang Makita sa Dashboard
**Solusyon:**
1. Kailangan may users sa database
2. Insert more test users para makita ang statistics

### Problem 4: Navigation Hindi Lumalabas
**Solusyon:**
1. I-check kung may folder na `application/views/templates/`
2. Dapat may `header.php` at `footer.php` doon

### Problem 5: "Base URL" Error
**Solusyon:**
```php
// Sa application/config/config.php
$config['base_url'] = 'http://localhost/your-project-name/';
```

---

## 📋 Checklist Bago Ipasa

Bago mo i-submit, i-check mo muna:

### Functionality
- [ ] Pwedeng mag-login ang admin
- [ ] Pwedeng mag-login ang teacher  
- [ ] Pwedeng mag-login ang student
- [ ] Iba-iba ang dashboard per role
- [ ] Iba-iba ang navigation per role
- [ ] Gumagana ang logout

### Screenshots
- [ ] May screenshot ng database (users table)
- [ ] May screenshot ng admin dashboard
- [ ] May screenshot ng teacher dashboard
- [ ] May screenshot ng student dashboard
- [ ] May screenshot ng navigation comparison
- [ ] May screenshot ng GitHub commits

### GitHub
- [ ] 5 or more commits
- [ ] Commits sa 4 different days
- [ ] May commit na "ROLE BASE Implementation"
- [ ] Na-push na sa GitHub
- [ ] Public o accessible ang repository

### Code
- [ ] Walang error sa console
- [ ] Clean ang code
- [ ] May comments
- [ ] Organized ang files

---

## 💡 Mga Tips para sa Demo

Kapag mag-demo ka sa teacher:

1. **Ipakita muna ang Database**
   - "Sir/Ma'am, ito po ang users table"
   - "May admin, teacher, at student roles po"

2. **Login bilang Admin**
   - "Pag nag-login po ako as admin..."
   - "Makikita po natin ang lahat ng statistics"
   - "May table po ng all users"

3. **Login bilang Teacher**
   - "Pag teacher naman po..."
   - "Iba na po ang dashboard"
   - "Iba na rin po ang navigation menu"

4. **Login bilang Student**
   - "Pag student po..."
   - "Student-specific content na po ang lumalabas"
   - "Iba na naman po ang menu"

5. **Ipakita ang Security**
   - "Pag hindi po logged in, balik sa login page"
   - "Protected po ang dashboard"

6. **Ipakita ang GitHub**
   - "Sir/Ma'am, ito po ang commits ko"
   - "May 5+ commits po over 4 days"

---

## 🎯 Ano ang Importante?

### Para sa Mataas na Grade:

1. **Gumagana lahat** - Walang error, smooth ang navigation
2. **May security** - Protected ang dashboard, hashed ang password
3. **Professional ang itsura** - Maganda ang design, organized
4. **Complete ang documentation** - May comments, may README
5. **Proper ang Git history** - 5+ commits, 4+ days, clear messages

### Bonus Points:
- Responsive design (works sa mobile)
- Extra features (profile editing, etc.)
- Very professional UI
- Excellent code organization
- Comprehensive documentation

---

## 📞 Kung Kailangan ng Tulong

1. Basahin muna ang **TROUBLESHOOTING.md**
2. I-check ang **TESTING_CHECKLIST.md**
3. Tignan kung kumpleto ang lahat ng files
4. I-verify ang database connection
5. I-check ang error logs

---

## ✅ Final Reminders

- **Mag-backup** ng database at code
- **I-test** lahat bago ipasa
- **I-check** kung gumagana sa fresh browser
- **Mag-practice** ng demo
- **Prepare** ng explanation para sa bawat feature

---

## 🎉 Tapos na!

**Congratulations!** Complete na ang RBAC implementation mo!

**Mga Natutuhan Mo:**
- Role-based authentication
- Session management
- Conditional rendering
- Database queries
- Template system
- Security best practices
- Git workflow

**Good luck sa iyong demo at submission!** 💪

---

**Kung may tanong pa, basahin ang:**
- RBAC_IMPLEMENTATION_GUIDE.md - Complete guide
- TESTING_CHECKLIST.md - Detailed testing
- TROUBLESHOOTING.md - Common problems
- IMPLEMENTATION_SUMMARY.md - Overview

**Salamat at good luck!** 🚀
