<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function databarang()
    {
        $response['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $response['barang'] = $this->model_barang->tampil_data()->result();
        $this->load->model('model_user');
        $this->model_user->updateOnlineStatus($this->session->userdata('email'), 'online');
        $response['title'] = 'INDOMARKET';

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // public function datauser()
    // {
    //     $response['title'] = 'User Informasi';

    //     // Ambil data user dari model
    //     $response['users'] = $this->model_user->tampil_data()->result();

    //     // Ambil data user berdasarkan email dari session
    //     $response['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    //     $this->load->model('model_user');
    //     $this->model_user->updateOnlineStatus($this->session->userdata('email'), 'online');
    //     // Load view dengan data yang telah diambil
    //     $this->output
    //         ->set_content_type('application/json')
    //         ->set_output(json_encode($response));
    // }

}
