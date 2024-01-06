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
            $email = $this->input->post('email', true); //untuk mengambil data dari inputan email
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)), //untuk mengamankan inputan name dari karakter aneh
                'email' => htmlspecialchars($email), //untuk mengamankan inputan email dari karakter aneh
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT), //untuk mengamankan inputan password dari karakter aneh
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()
            ];
            //siapkan token
            $token = base64_encode(random_bytes(32)); //untuk membuat token secara random
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            ];

             $this->db->insert('user', $data); //untuk memasukkan data ke database
             $this->db->insert('user_token', $user_token); //untuk memasukkan data ke database

            $this->_sendEmail($token, 'verify'); //untuk mengirimkan email verifikasi


            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Selamat! Akun Anda Sudah Terdaftar</div>'); //untuk menampilkan pesan berhasil
            redirect('auth'); //untuk mengarahkan ke halaman login
        }
    }

    private function _sendEmail($token, $type)
    {
        $config = [
            'protocol'  => 'smtp', // protokol yang digunakan
            'smtp_host' => 'tls://smtp.googlemail.com', // host yang digunakan
            'smtp_user' => 'dayemamben@gmail.com', // email yang digunakan
            'smtp_pass' => 'tbetwwllohhvjata', // password email yang digunakan
            'smtp_port' => 587, // port yang digunakan
            'mailtype'  => 'html', // tipe email yang digunakan
            'charset'   => 'utf-8', // charset yang digunakan
            'newline'   => "\r\n", // baris baru
            'smtp_crypto' => 'tls', // tambahkan opsi ini untuk TLS
        ];
        $this->email->initialize($config); // untuk menginisialisasi pengiriman email
    
        $this->email->from('dayemamben@gmail.com', 'VPS Lombok NTB'); // email pengirim
        $this->email->to($this->input->post('email')); // email penerima
    
        if ($type == 'verify') {
            $this->email->subject('verifikasi akun'); // subjek email
            $this->email->message('Klik link ini untuk verifikasi akun anda: <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Aktifkan</a>'); // isi email
        }
    
        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
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
