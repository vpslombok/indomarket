<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent ::__construct();
        is_logged_in();
    }
    
    public function index()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['barang'] = $this->model_barang->tampil_data()->result();
        $this->load->model('model_user');
        $this->model_user->updateOnlineStatus($this->session->userdata('email'), 'online');
        $data['title'] = 'INDOMARKET';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data );
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer' );
       
    }

    public function myprofile()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = 'My Profile';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data );
        $this->load->view('user/myprofile', $data);
        $this->load->view('templates/footer' );
    }

    public function editprofile()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = 'Edit Profile';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data );
        $this->load->view('user/editprofile', $data);
        $this->load->view('templates/footer' );
    }

    public function editprofileaksi()
    {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $password =  $this->input->post('password');

        //password jika di ubah
        if ($password) {
            $password = password_hash($password, PASSWORD_DEFAULT); //untuk mengenkripsi password
            $this->db->set('password', $password); //untuk mengupdate data password
        }

        //cek jika ada gambar yang akan di upload
        $upload_image = $_FILES['image']['name'];

        if ($upload_image) {
            $config['allowed_types'] = 'gif|jpg|png'; //untuk menentukan tipe file yang di upload
            $config['max_size']     = '2048'; //untuk menentukan ukuran file yang di upload
            $config['upload_path'] = './assets/img/profile/'; //untuk menentukan lokasi file yang di upload

            $this->load->library('upload', $config); //untuk memanggil library upload
            if ($this->upload->do_upload('image')) { //untuk mengecek apakah file berhasil di upload atau tidak
                $old_image = $data['user']['image']; //untuk mengambil data gambar lama
                if ($old_image != 'default.jpg') { //untuk mengecek apakah gambar lama bukan default.jpg
                    unlink(FCPATH . 'assets/img/profile/' . $old_image); //untuk menghapus gambar lama
                }
                $new_image = $this->upload->data('file_name'); //untuk mengambil data gambar baru
                $this->db->set('image', $new_image); //untuk mengupdate data gambar baru
            } else {
                echo $this->upload->display_errors(); //untuk menampilkan pesan error
            }
        }

        $this->db->set('name', $name); //untuk mengupdate data name
        $this->db->where('email', $email); //untuk mengupdate data email
        $this->db->update('user'); //untuk mengupdate data user
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Profile Berhasil Di Update</div>'); //untuk menampilkan pesan berhasil
        redirect('user/myprofile'); //untuk mengarahkan ke halaman myprofile
    }

    public function logout()
    {
        $this->session->unset_userdata('email'); //untuk menghapus session email
        $this->session->unset_userdata('role'); //untuk menghapus session role
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Anda Telah Logout</div>'); //untuk menampilkan pesan berhasil
        redirect('auth'); //untuk mengarahkan ke halaman login
    }

    public function keranjang()
    {
        $data['title'] = 'Keranjang';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar' );
        $this->load->view('user/keranjang', $data);
        $this->load->view('templates/footer' );
    }

    public function pembayaran()
    {
        $data['title'] = 'Pembayaran';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar' );
        $this->load->view('user/pembayaran', $data);
        $this->load->view('templates/footer' );
    }

    public function prosespesanan()
    {
        $data['title'] = 'Proses Pesanan';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar' );
        $this->load->view('user/prosespesanan', $data);
        $this->load->view('templates/footer' );
    }

    public function detail($id_brg)
    {
        $data['title'] = 'Detail Produk';
        $data['barang'] = $this->model_barang->detail_brg($id_brg);
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar' );
        $this->load->view('user/detailbarang', $data);
        $this->load->view('templates/footer' );
    }

    public function tambah_ke_keranjang($id)
    {
        $barang = $this->model_barang->find($id);

        $data = array(
            'id'      => $barang->id_brg,
            'qty'     => 1,
            'price'   => $barang->harga,
            'name'    => $barang->nama_brg,
        );

        $this->cart->insert($data);
        redirect('user');
    }

    public function hapus_keranjang()
    {
        $this->cart->destroy();
        redirect('user/keranjang');
    }

    public function pembayaran_aksi()
    {
        $is_processed = $this->model_invoice->index();
        if ($is_processed) {
            $this->cart->destroy();
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar' );
            $this->load->view('user/pembayaran');
            $this->load->view('templates/footer' );
        } else {
            echo "Maaf, Pesanan Anda Gagal Di Proses";
        }
    }

    public function detail_pesanan($id_invoice)
    {
        $data['title'] = 'Detail Pesanan';
        $data['invoice'] = $this->model_invoice->ambil_id_invoice($id_invoice);
        $data['pesanan'] = $this->model_invoice->ambil_id_pesanan($id_invoice);
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar' );
        $this->load->view('user/detailpesanan', $data);
        $this->load->view('templates/footer' );
    }

    public function search()
    {
        $keyword = $this->input->post('keyword');
        $data['barang'] = $this->model_barang->get_keyword($keyword);
        $data['title'] = 'INDOMARKET';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar' );
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer' );
    }

    public function ubahpassword()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = 'Ubah Password';

        $this->form_validation->set_rules('current_password', 'current_password', 'required|trim', [
            'required' => 'Password Lama Harus Diisi'
        ]);
        $this->form_validation->set_rules('new_password1', 'current_password1', 'required|trim|min_length[3]|matches[new_password2]', [
            'required' => 'Password Lama Harus Diisi',
            'min_length' => 'Password minimal 3 karakter',
            'matches' => 'Password tidak sama'
        ]);
        $this->form_validation->set_rules('new_password2', 'new_password2', 'required|trim', [
            'required' => 'Password Lama Harus Diisi'
        ]);
        if ($this->form_validation->run() == false){
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data );
        $this->load->view('user/ubahpassword', $data);
        $this->load->view('templates/footer' );
       
        } else{
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');
            if (!password_verify($current_password, $data['user']['password'])){
                $this->session->set_flashdata('ubahpassword', '<div class="alert alert-danger" role="alert">Password Lama Salah</div>');
                redirect('user/ubahpassword');
            } else{
                if ($current_password == $new_password){
                    $this->session->set_flashdata('ubahpassword', '<div class="alert alert-danger" role="alert">Password Baru Tidak Boleh Sama Dengan Password Lama</div>');
                    redirect('user/ubahpassword');
                } else{
                    //password sudah ok
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $this->db->set('password', $password_hash); //untuk mengupdate data password
                    $this->db->where('email', $this->session->userdata('email')); //untuk mengupdate data email
                    $this->db->update('user'); //untuk mengupdate data user
                    $this->session->set_flashdata('ubahpassword', '<div class="alert alert-success" role="alert">Password Berhasil Diubah</div>');
                    redirect('user/ubahpassword');
                }
            }
        }
    }
    
}
