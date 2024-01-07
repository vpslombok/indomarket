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
            'smtp_host' => 'mail.bael.my.id', // host yang digunakan
            'smtp_user' => 'e-mart@bael.my.id', // email yang digunakan
            'smtp_pass' => 'YYb$u!psJh4?', // password email yang digunakan
            'smtp_port' => 465, // port yang digunakan
            'mailtype'  => 'html', // tipe email yang digunakan
            'charset'   => 'utf-8', // charset yang digunakan
            'newline'   => "\r\n", // baris baru
            'smtp_crypto' => 'ssl', // tambahkan opsi ini untuk TLS
        ];
        $this->email->initialize($config); // untuk menginisialisasi pengiriman email

        $this->email->from('e-mart@bael.my.id', 'Indomarket Semua Ada'); // email pengirim
        $this->email->to($this->input->post('email')); // email penerima

        if ($type == 'verify') {
            $this->email->subject('verifikasi akun'); // subjek email
            $this->email->message('Klik tombol di bawah ini untuk verifikasi akun Anda:<br>
            <button type="button" style="padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px;">
                <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '" style="text-decoration: none; color: white;">Aktifkan</a>
            </button>'); // isi email
        }else if($type == 'forgot') {
            $this->email->subject('Reset Password'); // subjek email
            $this->email->message('Klik tombol di bawah ini untuk reset password Anda:<br>
            <button type="button" style="padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px;">
                <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '" style="text-decoration: none; color: white;">Reset Password</a>
            </button>'); // isi email
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    public function verify()
    {
        $email = $this->input->get('email'); //untuk mengambil data dari inputan email
        $token = $this->input->get('token'); //untuk mengambil data dari inputan token

        $user = $this->db->get_where('user', ['email' => $email])->row_array(); //untuk mengambil data dari database berdasarkan email

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array(); //untuk mengambil data dari database berdasarkan token

            if ($user_token) {
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) { //untuk mengecek apakah token sudah expired atau belum
                    $this->db->set('is_active', 1); //untuk mengubah data is_active menjadi 1
                    $this->db->where('email', $email); //untuk mengubah data berdasarkan email
                    $this->db->update('user'); //untuk mengubah data di database
                    $this->db->delete('user_token', ['email' => $email]); //untuk menghapus data di database
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $email . ' Telah Di Aktivasi! Silahkan Login</div>'); //untuk menampilkan pesan berhasil
                    redirect('auth'); //untuk mengarahkan ke halaman login
                } else {
                    $this->db->delete('user', ['email' => $email]); //untuk menghapus data di database
                    $this->db->delete('user_token', ['email' => $email]); //untuk menghapus data di database
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Token Kadaluarsa</div>'); //untuk menampilkan pesan berhasil
                    redirect('auth'); //untuk mengarahkan ke halaman login
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Token Salah</div>'); //untuk menampilkan pesan berhasil
                redirect('auth'); //untuk mengarahkan ke halaman login
            }
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

    public function forgotpassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', [
            'required' => 'Email Harus Diisi',
            'valid_email' => 'Email Tidak Valid'
        ]); //untuk mengecek apakah inputan email sudah sesuai dengan rules atau belum


        if($this->form_validation->run() == false){
            $data['title'] = 'Halaman Lupa Password'; //untuk mengirimkan data ke view [application/views/auth/forgot-password.php
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forgot-password');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();
            if($user){
                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];
                $this->db->insert('user_token', $user_token);
                $this->_sendEmail($token, 'forgot');
                $this->session->set_flashdata('lupapassword', '<div class="alert alert-success" role="alert">Silahkan Cek Email Untuk Reset Password</div>');
                redirect('auth/forgotpassword');
            } else {
                $this->session->set_flashdata('lupapassword', '<div class="alert alert-danger" role="alert">Email Belum Terdaftar Atau Belum Aktif</div>');
                redirect('auth/forgotpassword');
            }
        }
    }


    public function resetpassword()
    {
        $email = $this->input->get('email'); //untuk mengambil data dari inputan email
        $token = $this->input->get('token'); //untuk mengambil data dari inputan token

        $user = $this->db->get_where('user', ['email' => $email])->row_array(); //untuk mengambil data dari database berdasarkan email

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array(); //untuk mengambil data dari database berdasarkan token

            if ($user_token) {
                $this->session->set_userdata('reset_email', $email);
                $this->changePassword();
            } else {
                $this->session->set_flashdata('lupapassword', '<div class="alert alert-danger" role="alert">Reset Password Gagal! Token Salah</div>'); //untuk menampilkan pesan berhasil
                redirect('auth/forgotpassword'); //untuk mengarahkan ke halaman login
            }
        } else {
            $this->session->set_flashdata('lupapassword', '<div class="alert alert-danger" role="alert">Reset Password Gagal! Email Salah</div>'); //untuk menampilkan pesan berhasil
            redirect('auth/forgotpassword'); //untuk mengarahkan ke halaman login
        }
    }

    public function changePassword()
    {
        if(!$this->session->userdata('reset_email')){
            redirect('auth');
        }
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'required' => 'Password Harus Diisi',
            'matches' => 'Password Tidak Sama',
            'min_length' => 'Password Terlalu Pendek'
        ]); //untuk mengecek apakah inputan password sudah diisi atau belum
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]', [
            'required' => 'Password Harus Diisi',
            'matches' => 'Password Tidak Sama',
        ]); //untuk mengecek apakah inputan password sudah diisi atau belum

        if($this->form_validation->run() == false){
            $data['title'] = 'Halaman Reset Password'; //untuk mengirimkan data ke view [application/views/auth/reset-password.php
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/reset-password');
            $this->load->view('templates/auth_footer');
        }else{
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT); //untuk mengamankan inputan password dari karakter aneh
            $email = $this->session->userdata('reset_email');
            $this->db->set('password', $password); //untuk mengubah data password
            $this->db->where('email', $email); //untuk mengubah data berdasarkan email
            $this->db->update('user'); //untuk mengubah data di database
            $this->session->unset_userdata('reset_email');
            $this->session->set_flashdata('lupapassword', '<div class="alert alert-success" role="alert">Password Berhasil Diubah! Silahkan Login</div>'); //untuk menampilkan pesan berhasil
            redirect('auth'); //untuk mengarahkan ke halaman login
        }
    }
}
