<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function index()
    {
        $data['title'] = 'ADMIN INDOMARKET';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function myprofile()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('user', ['email' => 
        $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/myprofile', $data);
        $this->load->view('templates/footer', $data);
    }

    public function userinformasi()
    {
        $data['title'] = 'User Informasi';
        
        // Ambil data user dari model
        $data['users'] = $this->model_user->tampil_data()->result();
    
        // Ambil data user berdasarkan email dari session
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('model_user');
        $this->model_user->updateOnlineStatus($this->session->userdata('email'), 'online');
        // Load view dengan data yang telah diambil
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/userinformasi', $data);
        $this->load->view('templates/footer', $data);
        
    }

    public function tambah_user()
    {
        $data['title'] = 'Tambah User';
        $data['user'] = $this->db->get_where('user', ['email' => 
        $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/admin_sidebar', $data);
        $this->load->view('admin/tambah_user', $data);
        $this->load->view('templates/footer', $data);
    }
    

}
