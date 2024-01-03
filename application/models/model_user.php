<?php
class Model_User extends CI_Model{
    public function tampil_data(){
        return $this->db->get('user');
    }

    public function updateOnlineStatus($email, $status) {
        $data = array(
            'online_status' => $status,
        );

        $this->db->where('email', $email);
        $this->db->update('user', $data);
    }
    
}