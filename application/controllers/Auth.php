<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{


    public function index()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', [ //untuk mengecek apakah inputan email sudah sesuai dengan rules atau belum
            'required' => 'Email Harus Diisi',
            'valid_email' => 'Email Tidak Valid'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|trim', ['required' => 'Password Harus Diisi']);

        if ($this->form_validation->run() == false) { //untuk mengecek apakah inputan sudah sesuai dengan rules atau belum
            $data['title'] = 'Halaman Login'; //untuk mengirimkan data ke view [application/views/auth/login.php
            $this->load->view('templates/auth_header', $data);
            $this->load->view('Auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            //validasinya sukses
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email'); //untuk mengambil data dari inputan email
        $password = $this->input->post('password'); //untuk mengambil data dari inputan password

        $user = $this->db->get_where('user', ['email' => $email])->row_array(); //untuk mengambil data dari database berdasarkan email
        //jika user ada
        if ($user) {

            //jika user aktif
            if ($user['is_active'] == 1) {
                //cek password
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id'  => $user['role_id'],

                    ];
                    $this->session->set_userdata($data); //untuk membuat session
                    if ($user['role_id'] == 1) {
                        redirect('admin');
                    } else {
                        redirect('user');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Password salah</div>'); //untuk menampilkan pesan berhasil
                    redirect('auth'); //untuk mengarahkan ke halaman login
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Akun Belum Di Aktivasi</div>'); //untuk menampilkan pesan berhasil
                redirect('auth'); //untuk mengarahkan ke halaman login
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Akun Belum Terdaftar</div>'); //untuk menampilkan pesan berhasil
            redirect('auth'); //untuk mengarahkan ke halaman login
        }
    }

    public function registration()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|trim', [
            'required' => 'Nama Harus Diisi'
        ]); //untuk mengecek apakah inputan name sudah diisi atau belum
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'required' => 'Email Harus Diisi',
            'is_unique' => 'Email Sudah Terdaftar',
        ]); //untuk mengecek apakah inputan email sudah diisi atau belum
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'required' => 'Password Harus Diisi',
            'matches' => 'Password Tidak Sama',
            'min_length' => 'Password Terlalu Pendek'
        ]); //untuk mengecek apakah inputan password sudah diisi atau belum
        $this->form_validation->set_rules('password2', 'Password', 'required|trim', [
            'required' => 'Password Harus Diisi',
        ]); //untuk mengecek apakah inputan password sudah diisi atau belum



        if ($this->form_validation->run() == false) {
            $data['title'] = 'Halaman Daftar'; //untuk mengirimkan data ke view [application/views/auth/registration.php
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)), //untuk mengamankan inputan name dari karakter aneh
                'email' => htmlspecialchars($this->input->post('email', true)), //untuk mengamankan inputan email dari karakter aneh
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT), //untuk mengamankan inputan password dari karakter aneh
                'role_id' => 2,
                'is_active' => 1,
                'date_created' => time()
            ];

            $this->db->insert('user', $data); //untuk memasukkan data ke database
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Selamat! Akun Anda Sudah Terdaftar</div>'); //untuk menampilkan pesan berhasil
            redirect('auth'); //untuk mengarahkan ke halaman login
        }
    }

    public function logout()
    {
        $this->load->model('model_user');
        $this->load->model('model_user');
        $this->model_user->updateOnlineStatus($this->session->userdata('email'), 'offline');
        $this->session->unset_userdata('email'); //untuk menghapus session email
        $this->session->unset_userdata('role'); //untuk menghapus session role
        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Anda Telah Berhasil Logout</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>'); //untuk menampilkan pesan berhasil
        redirect('auth'); //untuk mengarahkan ke halaman login
        $response = array('status' => 'success', 'message' => 'Login successful');
        echo json_encode($response);
    }

    public function blocked()
    {
        $this->load->view('auth/blocked'); //untuk menampilkan halaman blocked
    }
}
