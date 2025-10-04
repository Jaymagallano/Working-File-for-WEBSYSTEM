<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Course_model');
        $this->load->model('Enrollment_model');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Please login to access this page.');
            redirect('login');
        }
    }
    
    /**
     * Enroll a student in a course via AJAX
     */
    public function enroll() {
        // Set JSON response header
        header('Content-Type: application/json');
        
        // Check if request is POST and AJAX
        if ($this->input->method() !== 'post') {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method.'
            ]);
            return;
        }
        
        // Verify user is logged in
        if (!$this->session->userdata('logged_in')) {
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized access. Please login.'
            ]);
            return;
        }
        
        // Only students can enroll
        if ($this->session->userdata('role') !== 'student') {
            echo json_encode([
                'success' => false,
                'message' => 'Only students can enroll in courses.'
            ]);
            return;
        }
        
        // Get course_id from POST (protected by XSS filtering)
        $course_id = $this->input->post('course_id', TRUE);
        
        // Validate course_id
        if (empty($course_id) || !is_numeric($course_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid course ID.'
            ]);
            return;
        }
        
        // Get user_id from session (NEVER trust client-supplied user IDs)
        $user_id = $this->session->userdata('user_id');
        
        // Check if course exists
        $course = $this->Course_model->getCourseById($course_id);
        if (!$course) {
            echo json_encode([
                'success' => false,
                'message' => 'Course not found.'
            ]);
            return;
        }
        
        // Check if already enrolled
        if ($this->Enrollment_model->isAlreadyEnrolled($user_id, $course_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ]);
            return;
        }
        
        // Enroll the user
        $enrollment_data = array(
            'user_id' => $user_id,
            'course_id' => $course_id
        );
        
        if ($this->Enrollment_model->enrollUser($enrollment_data)) {
            $response = [
                'success' => true,
                'message' => 'Successfully enrolled in ' . $course->title . '!',
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'instructor_name' => $course->instructor_name,
                    'enrolled_at' => date('M d, Y')
                ],
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ];
            echo json_encode($response);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to enroll. Please try again.'
            ]);
        }
    }
    
    /**
     * View all courses
     */
    public function index() {
        $data['user'] = $this->session->userdata();
        $data['courses'] = $this->Course_model->getAllCourses();
        
        $this->load->view('templates/header', ['page_title' => 'Courses']);
        $this->load->view('courses/index', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * View single course
     */
    public function view($course_id) {
        $course = $this->Course_model->getCourseById($course_id);
        
        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found.');
            redirect('course');
        }
        
        $data['user'] = $this->session->userdata();
        $data['course'] = $course;
        $data['is_enrolled'] = $this->Enrollment_model->isAlreadyEnrolled(
            $this->session->userdata('user_id'),
            $course_id
        );
        
        $this->load->view('templates/header', ['page_title' => $course->title]);
        $this->load->view('courses/view', $data);
        $this->load->view('templates/footer');
    }
}
