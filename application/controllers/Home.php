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
        $data['title'] = "Home";
        $data['category'] = $this->model->getCategory();
        $data['user'] = $this->db->get_where('users', ['user_email' => $this->session->userdata('client_email')])->row_array();
        $data['order'] = $this->db->get_where('pesanan_header', ['pesanan_status' => 0, 'email_input' => $this->session->userdata('client_email')])->result_array();
        $data['type'] = $this->input->get('type');
        $data['meja'] = $this->input->get('meja');

        $this->load->view('templates/header_client', $data);
        $this->load->view('home/home', $data);
        $this->load->view('templates/footer_client');
    }
}