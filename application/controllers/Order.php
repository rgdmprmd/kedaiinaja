<?php defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Order_model', 'model');
        $this->load->library('pagination');
        
        $params = array('server_key' => 'SB-Mid-server-rOppPix03bf6s1J_kFywI9C9', 'production' => false);
		$this->load->library('midtrans');
		$this->midtrans->config($params);
		$this->load->helper('url');	
    }

    public function index()
    {
        $data['title'] = 'Cart';
        $data['meja'] = $this->input->get('meja');
        $data['type'] = $this->input->get('type');
        $data['meja_list'] = $this->model->getMeja($data['meja']);
        $data['user'] = $this->db->get_where('users', ['user_email' => $this->session->userdata('client_email')])->row_array();
        $data['order'] = $this->db->get_where('pesanan_header', ['pesanan_status' => 0, 'email_input' => $this->session->userdata('client_email')])->result_array();

        // $this->load->view('templates/client_header', $data);
        // $this->load->view('cart/cart', $data);
        // $this->load->view('templates/client_footer');
        
        $this->load->view('templates/header_client', $data);
        $this->load->view('cart/index', $data);
        $this->load->view('templates/footer_client');
    }
}