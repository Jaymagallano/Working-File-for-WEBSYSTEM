<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Course_model');
        $this->load->database();
    }

    public function courses() {
        // Prevent running in production
        if (ENVIRONMENT === 'production') {
            show_error('Setup is disabled in production', 403);
        }

        echo "<h1>Course Setup</h1>";
        echo "<pre>";

        // Check existing courses
        $existing_courses = $this->Course_model->get_all_courses();
        
        if (!empty($existing_courses)) {
            echo "⚠️  Found " . count($existing_courses) . " existing course(s).\n";
            echo "Deleting existing courses...\n";
            $this->db->truncate('courses');
            echo "✓ Deleted existing courses\n\n";
        }

        // Create/get teacher
        $teacher_email = 'teacher@lms.com';
        $teacher = $this->User_model->get_user_by_email($teacher_email);

        if (!$teacher) {
            $teacher_data = [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'email' => $teacher_email,
                'password' => password_hash('teacher123', PASSWORD_BCRYPT),
                'role' => 'teacher'
            ];
            
            $this->db->insert('users', $teacher_data);
            $teacher_id = $this->db->insert_id();
            echo "✓ Created default teacher account\n";
            echo "  Email: {$teacher_email}\n";
            echo "  Password: teacher123\n\n";
        } else {
            $teacher_id = $teacher->id;
            echo "✓ Using existing teacher account (ID: {$teacher_id})\n\n";
        }

        // Sample courses
        $courses = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the fundamentals of HTML, CSS, and JavaScript.',
                'instructor_id' => $teacher_id,
                'schedule' => 'Mon & Wed, 9:00 AM - 10:30 AM',
                'room' => 'Room 301',
                'max_students' => 30
            ],
            [
                'title' => 'Database Management Systems',
                'description' => 'Master SQL and database design.',
                'instructor_id' => $teacher_id,
                'schedule' => 'Tue & Thu, 10:00 AM - 11:30 AM',
                'room' => 'Room 302',
                'max_students' => 25
            ],
            [
                'title' => 'Python Programming for Beginners',
                'description' => 'Start your programming journey with Python.',
                'instructor_id' => $teacher_id,
                'schedule' => 'Mon & Wed, 2:00 PM - 3:30 PM',
                'room' => 'Room 401',
                'max_students' => 35
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Build native mobile applications.',
                'instructor_id' => $teacher_id,
                'schedule' => 'Tue & Thu, 1:00 PM - 2:30 PM',
                'room' => 'Room 402',
                'max_students' => 20
            ],
            [
                'title' => 'Data Structures and Algorithms',
                'description' => 'Deep dive into essential data structures.',
                'instructor_id' => $teacher_id,
                'schedule' => 'Mon, Wed & Fri, 3:00 PM - 4:00 PM',
                'room' => 'Room 501',
                'max_students' => 30
            ],
            [
                'title' => 'Cybersecurity Fundamentals',
                'description' => 'Learn about network security and encryption.',
                'instructor_id' => $teacher_id,
                'schedule' => 'Tue & Thu, 4:00 PM - 5:30 PM',
                'room' => 'Room 502',
                'max_students' => 25
            ]
        ];

        $success_count = 0;
        foreach ($courses as $course) {
            if ($this->db->insert('courses', $course)) {
                echo "✓ Created: {$course['title']}\n";
                $success_count++;
            } else {
                echo "✗ Failed: {$course['title']}\n";
            }
        }

        echo "\n========================================\n";
        echo "Summary:\n";
        echo "  Total courses created: {$success_count}/" . count($courses) . "\n";
        echo "  Teacher ID used: {$teacher_id}\n";
        echo "========================================\n\n";

        if ($success_count === count($courses)) {
            echo "✅ Setup completed successfully!\n\n";
            echo "<a href='" . base_url() . "'>Go to Homepage</a>";
        }

        echo "</pre>";
    }
}