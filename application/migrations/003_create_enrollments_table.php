<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_enrollments_table extends CI_Migration {
    
    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'course_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'enrollment_date' => array(
                'type' => 'DATETIME',
                'null' => FALSE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('course_id');
        $this->dbforge->create_table('enrollments');
    }
    
    public function down() {
        $this->dbforge->drop_table('enrollments');
    }
}