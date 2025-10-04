<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enrollment_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Enroll a user in a course
     * @param array $data - must contain user_id and course_id
     * @return bool
     */
    public function enrollUser($data) {
        // Add enrollment timestamp
        $data['enrollment_date'] = date('Y-m-d H:i:s');
        
        // Insert enrollment record
        return $this->db->insert('enrollments', $data);
    }
    
    /**
     * Get all courses a user is enrolled in
     * @param int $user_id
     * @return array
     */
    public function getUserEnrollments($user_id) {
        $this->db->select('courses.*, users.name as instructor_name, enrollments.enrollment_date');
        $this->db->from('enrollments');
        $this->db->join('courses', 'courses.id = enrollments.course_id');
        $this->db->join('users', 'users.id = courses.teacher_id', 'left');
        $this->db->where('enrollments.user_id', $user_id);
        $this->db->order_by('enrollments.enrollment_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Check if user is already enrolled in a course
     * @param int $user_id
     * @param int $course_id
     * @return bool
     */
    public function isAlreadyEnrolled($user_id, $course_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('course_id', $course_id);
        $query = $this->db->get('enrollments');
        return $query->num_rows() > 0;
    }
    
    /**
     * Unenroll a user from a course
     * @param int $user_id
     * @param int $course_id
     * @return bool
     */
    public function unenrollUser($user_id, $course_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('course_id', $course_id);
        return $this->db->delete('enrollments');
    }
    
    /**
     * Get all students enrolled in a course
     * @param int $course_id
     * @return array
     */
    public function getCourseEnrollments($course_id) {
        $this->db->select('users.*, enrollments.enrollment_date');
        $this->db->from('enrollments');
        $this->db->join('users', 'users.id = enrollments.user_id');
        $this->db->where('enrollments.course_id', $course_id);
        $this->db->order_by('enrollments.enrollment_date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Count enrollments for a course
     * @param int $course_id
     * @return int
     */
    public function countCourseEnrollments($course_id) {
        $this->db->where('course_id', $course_id);
        return $this->db->count_all_results('enrollments');
    }
    
    /**
     * Count enrollments for a user
     * @param int $user_id
     * @return int
     */
    public function countUserEnrollments($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results('enrollments');
    }
    
    /**
     * Get enrollment record
     * @param int $user_id
     * @param int $course_id
     * @return object|null
     */
    public function getEnrollment($user_id, $course_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('course_id', $course_id);
        $query = $this->db->get('enrollments');
        return $query->row();
    }
}
