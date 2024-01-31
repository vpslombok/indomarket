<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['number_of_user'] = $this->model_user->get_number_of_user();
        $data['data_user_online'] = $this->model_user->get_number_of_user_online();
        $data['jumlah_produk'] = $this->model_user->get_number_of_barang();
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

        $this->session->set_flashdata('role', '<div class="alert alert-success" role="alert">Access Changed!</div>'); //untuk menampilkan pesan berhasil
    }

    public function roleedit()
    {
        $data['title'] = 'Role Edit';
        $data['role'] = $this->db->get_where('user_role', ['id' => $this->uri->segment(3)])->row_array();
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/admin_sidebar', $data);
        $this->load->view('admin/role_edit', $data);
        $this->load->view('templates/footer', $data);
        $this->session->set_flashdata('role', '<div class="alert alert-success" role="alert">Role Berhasil Diedit</div>');
        redirect('admin/role');
    }

    public function roledelete()
    {
        $this->db->where('id', $this->uri->segment(3));
        $this->db->delete('user_role');
        $this->session->set_flashdata('role', '<div class="alert alert-success" role="alert">Role Berhasil Dihapus</div>');
        redirect('admin/role');
    }

    public function databarang()
    {
        $data['title'] = 'Data Produk';
        $data['barang'] = $this->model_barang->tampil_data()->result();
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/data-barang', $data);
        $this->load->view('templates/footer', $data);
    }

    public function tambah_barang()
    {
        $nama_brg       = $this->input->post('nama_brg');
        $keterangan     = $this->input->post('keterangan');
        $kategori       = $this->input->post('kategori');
        $harga          = $this->input->post('harga');
        $stok           = $this->input->post('stok');
        $gambar         = $_FILES['gambar']['name'];
        if ($gambar != '') {
        $config['upload_path']   = './uploads';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('gambar')) {
            echo "Gambar gagal diupload!";
        } else {
            $uploaded_data = $this->upload->data();

            // Call the new function to resize the image
            $this->resize_image($uploaded_data['full_path']);

            $gambar = $uploaded_data['file_name'];
        }
    }

        $data = array(
            'nama_brg'      => $nama_brg,
            'keterangan'    => $keterangan,
            'kategori'      => $kategori,
            'harga'         => $harga,
            'stok'          => $stok,
            'gambar'        => $gambar
        );
        $this->session->set_flashdata('data-barang', '<div class="alert alert-success" role="alert">Data Berhasill di tambah</div>');

        $this->model_barang->tambah_barang($data, 'tb_barang');
        redirect('admin/databarang');
    }

    public function resize_image($source_path)
{
    $config['image_library']  = 'gd2';
    $config['source_image']   = $source_path;
    $config['maintain_ratio'] = TRUE;
    $config['width']          = 800; // Set your desired width
    $config['height']         = 600; // Set your desired height

    $this->load->library('image_lib', $config);

    if (!$this->image_lib->resize()) {
        echo $this->image_lib->display_errors();
    }

    $this->image_lib->clear();
}

    public function hapus_produk($id)
    {
        // Dapatkan informasi gambar sebelum menghapus produk
        $produk = $this->model_barang->get_produk_by_id($id);
        $gambar_produk = $produk['gambar'];

        // Hapus data produk dari database
        $where = array('id_brg' => $id); // Change 'id' to 'id_brg'
        $this->model_barang->hapus_produk($where, 'tb_barang');

        // Hapus gambar terkait dari folder uploads
        if ($gambar_produk != '') {
            $path_gambar = './uploads/' . $gambar_produk;
            if (file_exists($path_gambar)) {
                unlink($path_gambar);
            }
        }
        // Set flashdata dan redirect
        $this->session->set_flashdata('data-barang', '<div class="alert alert-success" role="alert">Data Berhasil Dihapus</div>');
        redirect('admin/databarang');
    }
}
