<?php defined('BASEPATH') or exit('No direct script access allowed');

class Client_auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Client_auth_model', 'model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $data['title'] = 'Login';
        $data['type'] = $this->input->get('type');
        $data['meja'] = $this->input->get('meja');
        $data['type'] = $this->input->get('type');
        $data['meja'] = $this->input->get('meja');

        $this->load->view('templates/auth_header', $data);
        $this->load->view('client_auth/login', $data);
        $this->load->view('templates/auth_footer', $data);
    }

    public function registration()
    {
        $data['title'] = 'Registration';
        $data['type'] = $this->input->get('type');
        $data['meja'] = $this->input->get('meja');

        $this->load->view('templates/auth_header', $data);
        $this->load->view('client_auth/registration', $data);
        $this->load->view('templates/auth_footer', $data);
    }

    public function regist_user()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.user_email]', ['is_unique' => 'Email has already registered!']);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', ['matches' => 'Password is not match!', 'min_length' => 'Password is too short!']);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == FALSE) {
            $formEror = [
                'nama' => form_error('name', '<small class="text-danger pl-3">', '</small>'),
                'email' => form_error('email', '<small class="text-danger pl-3">', '</small>'),
                'password' => form_error('password1', '<small class="text-danger pl-3">', '</small>')
            ];

            $result = [
                'result' => false,
                'error' => $formEror
            ];

            echo json_encode($result);
        } else {
            // Jika berhasil, tangkap data yang di input
            $emailRegister = htmlspecialchars($this->input->post('email', true));
            $nameRegister = htmlspecialchars($this->input->post('name', true));
            $passwordRegister = $this->input->post('password1');

            // Siapkan data yang akan di register
            $data = [
                'user_nama' => $nameRegister,
                'user_email' => $emailRegister,
                'user_image' => 'default.jpg',
                'user_password' => password_hash($passwordRegister, PASSWORD_DEFAULT),
                'role_id' => 2,
                'user_status' => 0,
                'dateCreated' => Date('Y-m-d H:i:s'),
                'dateModified' => NULL
            ];

            // Buat token untuk verifikasi email
            $token = base64_encode(random_bytes(32));

            // Siapkan data yang akan di insert ke user_token
            $user_token = [
                'user_email' => $emailRegister,
                'token' => $token,
                'dateCreated' => Date('Y-m-d H:i:s')
            ];

            // Insert data user baru dan insert token untuk verifikasi email, via model
            $this->model->setUser($data);
            $this->model->setToken($user_token);

            // Setelah di insert, maka jalankan method _sendEmail untuk mengirimkan email verifkasi
            $this->_sendEmail($token, 'verify', $emailRegister);

            $result = [
                'result' => true,
                'error' => ''
            ];

            echo json_encode($result);
        }
    }

    public function login_user()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $formEror = [
                'email' => form_error('email', '<small class="text-danger">', '</small>'),
                'password' => form_error('password', '<small class="text-danger">', '</small>')
            ];

            $result = [
                'result' => 400,
                'message' => $formEror
            ];

            echo json_encode($result);
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->model->getUserByEmail($email);

        if ($user) {
            if ($user['user_status'] == 1) {
                if (password_verify($password, $user['user_password'])) {
                    $data = [
                        'client_email' => $user['user_email'],
                    ];

                    $this->session->set_userdata($data);

                    $result = [
                        'result' => 200,
                        'message' => $user['user_email']
                    ];

                    echo json_encode($result);
                } else {
                    // Salah password
                    $result = [
                        'result' => 403,
                        'message' => $email
                    ];

                    echo json_encode($result);
                }
            } else {
                // Email tidak aktif
                $result = [
                    'result' => 402,
                    'message' => $email
                ];

                echo json_encode($result);
            }
        } else {
            // Salah email
            $result = [
                'result' => 401,
                'message' => $email
            ];

            echo json_encode($result);
        }
    }

    private function _sendEmail($token, $type, $email)
    {
        // Config standar untuk sendEmail menggunakan GMAIL
        // 'smtp_host' => 'ssl://smtp.googlemail.com',
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => getenv('SMTP_EMAIL'),
            'smtp_pass' => getenv('SMTP_PASSWORD'),
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        // Load library email beserta configurasinya
        $this->load->library('email', $config);
        $this->email->initialize($config);

        // Email nya nanti mau dikirim oleh siapa
        $this->email->from(getenv('SMTP_EMAIL'), 'Bad Code Society');

        // Email nya nanti mau dikirim ke siapa
        $this->email->to($email);

        // Jika tipenya verify tentukan subject dan messagenya
        if ($type == 'verify') {
            // Subject emailnya apa
            $this->email->subject('Account Verification');

            // Isi emailnya apa
            $this->email->message('Click this link to verify your account : <a href="' . base_url() . 'client_auth/verify?email=' . $email . '&token=' . urlencode($token) . '">Activate</a>.');
        } else if ($type == 'forgot') {
            // Jika tipenya forgot, Subject emailnya apa
            $this->email->subject('Reset Password');

            // Isi emailnya apa
            $this->email->message('Click this link to reset your password : <a href="' . base_url() . 'client_auth/resetpassword?email=' . $email . '&token=' . urlencode($token) . '">Reset password</a>.');
        }

        // Cek apakah emailnya berhasil dikirm
        if ($this->email->send()) {
            // Jika berhasil return true
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->model->getUserByEmail($email);

        if ($user) {
            $user_token = $this->model->getTokenByToken($token);

            // Cek, apakah token ketemu
            if ($user_token) {
                if (time() - strtotime($user_token['dateCreated']) < (60 * 60 * 24)) {
                    $this->model->emailIsVerify($email);

                    // Tampilkan pesan berhasil dan redirect ke halaman login
                    $this->session->set_flashdata('stoken', $email);
                    redirect('client_auth');
                } else {
                    // Jika expired, maka hapus data user dan tokennya berdasarkan email yang dikirimkan, via model
                    $this->model->verifyTokenIsExpired($email);

                    // Tampilkan pesan token expired, dan redirect ke halaman login
                    $this->session->set_flashdata('etoken', 'token');
                    redirect('client_auth');
                }
            } else {
                $this->session->set_flashdata('wtoken', 'Activation');
                redirect('client_auth');
            }
        } else {
            $this->session->set_flashdata('wemail', $email);
            redirect('client_auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('client_email');

        $this->session->set_flashdata('logout', 'Logout');
        redirect('home');
    }
}
