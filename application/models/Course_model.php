<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Get all courses
     */
    public function getAllCourses() {
        $this->db->select('courses.*, users.name as instructor_name');
        $this->db->from('courses');
        $this->db->join('users', 'users.id = courses.teacher_id', 'left');
        $this->db->order_by('courses.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Get course by ID
     */
    public function getCourseById($course_id) {
        $this->db->select('courses.*, users.name as instructor_name');
        $this->db->from('courses');
        $this->db->join('users', 'users.id = courses.teacher_id', 'left');
        $this->db->where('courses.id', $course_id);
        $query = $this->db->get();
        return $query->row();
    }
    
    /**
     * Get courses by teacher ID
     */
    public function getCoursesByTeacher($teacher_id) {
        $this->db->select('*');
        $this->db->from('courses');
        $this->db->where('teacher_id', $teacher_id);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Get available courses for a student (not enrolled)
     */
    public function getAvailableCourses($user_id) {
        $this->db->select('courses.*, users.name as instructor_name');
        $this->db->from('courses');
        $this->db->join('users', 'users.id = courses.teacher_id', 'left');
        $this->db->where("courses.id NOT IN (
            SELECT course_id FROM enrollments WHERE user_id = {$user_id}
        )", NULL, FALSE);
        $this->db->order_by('courses.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Create a new course
     */
    public function createCourse($data) {
        return $this->db->insert('courses', $data);
    }
    
    /**
     * Update course
     */
    public function updateCourse($course_id, $data) {
        $this->db->where('id', $course_id);
        return $this->db->update('courses', $data);
    }
    
    /**
     * Delete course
     */
    public function deleteCourse($course_id) {
        $this->db->where('id', $course_id);
        return $this->db->delete('courses');
    }
    
    /**
     * Count total courses
     */
    public function countCourses() {
        return $this->db->count_all('courses');
    }
    
    /**
     * Count courses by teacher
     */
    public function countCoursesByTeacher($teacher_id) {
        $this->db->where('teacher_id', $teacher_id);
        return $this->db->count_all_results('courses');
    }
}
