<?php defined('BASEPATH') or exit('No direct script access allowed');

// Controller auth, mengelola authentikasi user (registrasi, login, etc)
class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Home_model', 'model');
    }

    public function index()
    {
        $data['title'] = "Login";
        $data['category'] = $this->model->getCategory();

        $this->load->view('templates/client_header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('templates/client_footer');
    }
}