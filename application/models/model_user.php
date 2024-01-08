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

    public function get_number_of_user() {
        $this->db->select('COUNT(*) as total');
        $this->db->from('user');
        return $this->db->get()->row()->total;
    }

    public function get_number_of_user_online() {
        $this->db->select('COUNT(*) as total');
        $this->db->from('user');
        $this->db->where('online_status', 'online');
        return $this->db->get()->row()->total;
    }

    public function get_number_of_barang() {
        $this->db->select('COUNT(*) as total');
        $this->db->from('tb_barang');
        return $this->db->get()->row()->total;
    }
    
}