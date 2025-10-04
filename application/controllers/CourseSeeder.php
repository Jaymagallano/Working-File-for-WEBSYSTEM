<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CourseSeeder extends CI_Controller {
    
    public function index() {
        $this->load->database();
        
        // Check if courses already exist
        $existing_courses = $this->db->count_all('courses');
        
        if ($existing_courses > 0) {
            echo "<h2>Courses already seeded!</h2>";
            echo "<p>Found {$existing_courses} existing courses.</p>";
            echo "<p><a href='" . base_url('dashboard') . "'>Go to Dashboard</a></p>";
            return;
        }
        
        // Get a teacher user to assign courses
        $teacher = $this->db->get_where('users', ['role' => 'teacher'])->row();
        
        if (!$teacher) {
            echo "<h2>Error: No teacher found!</h2>";
            echo "<p>Please create a teacher user first using UserSeeder.</p>";
            echo "<p><a href='" . base_url('userseeder') . "'>Run User Seeder</a></p>";
            return;
        }
        
        // Sample courses data
        $courses = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript. Perfect for beginners looking to start their web development journey.',
                'teacher_id' => $teacher->id,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'PHP and MySQL Database Programming',
                'description' => 'Master server-side programming with PHP and database management with MySQL. Build dynamic web applications from scratch.',
                'teacher_id' => $teacher->id,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Advanced JavaScript and jQuery',
                'description' => 'Deep dive into JavaScript programming and jQuery library. Learn AJAX, DOM manipulation, and modern ES6+ features.',
                'teacher_id' => $teacher->id,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'CodeIgniter 3 Framework',
                'description' => 'Build robust web applications using CodeIgniter 3 framework. Learn MVC architecture, routing, and database operations.',
                'teacher_id' => $teacher->id,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Bootstrap 5 Responsive Design',
                'description' => 'Create beautiful, responsive websites using Bootstrap 5. Master grid system, components, and utilities.',
                'teacher_id' => $teacher->id,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Git Version Control System',
                'description' => 'Learn version control with Git and GitHub. Collaborate on projects and manage code effectively.',
                'teacher_id' => $teacher->id,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        // Insert courses
        $inserted = 0;
        foreach ($courses as $course) {
            if ($this->db->insert('courses', $course)) {
                $inserted++;
            }
        }
        
        echo "<h2>Course Seeder Completed!</h2>";
        echo "<p>Successfully inserted {$inserted} courses.</p>";
        echo "<ul>";
        foreach ($courses as $course) {
            echo "<li><strong>{$course['title']}</strong> - {$course['description']}</li>";
        }
        echo "</ul>";
        echo "<p><a href='" . base_url('dashboard') . "' class='btn btn-primary'>Go to Dashboard</a></p>";
        
        echo "<style>
            body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
            h2 { color: #198754; }
            ul { line-height: 2; }
            .btn { display: inline-block; padding: 10px 20px; background: #0d6efd; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
            .btn:hover { background: #0b5ed7; }
        </style>";
    }
}
