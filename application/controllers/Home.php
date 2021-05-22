<?php defined('BASEPATH') or exit('No direct script access allowed');

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
        $data['user'] = $this->db->get_where('users', ['user_email' => $this->session->userdata('client_email')])->row_array();

        $this->load->view('templates/client_header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('templates/client_footer');
    }
}