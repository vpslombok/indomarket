<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent ::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['number_of_user'] = $this->model_user->get_number_of_user();
        $data['data_user_online'] = $this->model_user->get_number_of_user_online();
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

    public function hapus($id)
    {
        $where = array('id' => $id);
        $this->db->delete('user', $where);
        $this->session->set_flashdata('pesan hapus akun', '<div class="alert alert-danger" role="alert">Data Berhasil Dihapus</div>');
        redirect('admin/userinformasi');
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
    
    public function role()
    {
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer');
    }

    public function roleAccess($role_id)
    {
        $data['title'] = 'Role Access';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');
    }

    public function changeaccess()
    {
        $menu_id = $this->input->post('menuId'); //untuk mengambil data dari inputan menuId
        $role_id = $this->input->post('roleId'); //untuk mengambil data dari inputan roleId

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data); //untuk mengambil data dari database berdasarkan data yang di inputkan

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data); //untuk menambahkan data ke database
        } else {
            $this->db->delete('user_access_menu', $data); //untuk menghapus data dari database
        }

        $this->session->set_flashdata('role-access', '<div class="alert alert-success" role="alert">Access Changed!</div>'); //untuk menampilkan pesan berhasil
    }

}
