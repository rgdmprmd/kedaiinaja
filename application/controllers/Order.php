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
        $data['user'] = $this->db->get_where('users', ['user_email' => $this->session->userdata('client_email')])->row_array();
        $data['order'] = $this->db->get_where('pesanan_header', ['pesanan_status' => 0, 'email_input' => $this->session->userdata('client_email')])->result_array();
        // $data['meja_list'] = $this->model->getMeja($data['meja']);

        $this->load->view('templates/header_client', $data);
        $this->load->view('order/index', $data);
        $this->load->view('templates/footer_client');
    }

    public function ajaxGetData()
    {
        $status = $this->input->post('status');
        $email = $this->session->userdata('client_email');
        
        $cart = $this->model->get_data($email, $status);
        $tr = '';

        if(count($cart['data']) > 0) {
            foreach($cart['data'] as $m) {
                if($m['bca_va_number']) {
                    $virtual_account = $m['bca_va_number'];
                } else if ($m['permata_va_number']) {
                    $virtual_account = $m['permata_va_number'];
                } else if ($m['va_number']) {
                    $virtual_account = $m['va_number'];
                } else {
                    $virtual_account = '';
                }

                if(!$m['bank'] && $m['permata_va_number']) {
                    $bank = "permata";
                } else {
                    $bank = $m['bank'];
                }

                if($m['payment_type'] == "cash") {
                    $payment_type = '<span>Payment method</span> <strong>'.ucfirst($m['payment_type']).'</strong>';
                    $is_hide = 'hide';
                } else {
                    $payment_type = '<span>Payment method</span> <strong>'.strtoupper($bank).'</strong>';
                    $is_hide = '';
                }

                $tr .= '<div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><strong>'.ucfirst($m['transaction_status']).'</strong></h4>
                                </div>
                                <div class="col-md-6">
                                    <span>Transaction on</span> <strong>'.Date("M d, Y", strtotime($m['transaction_time'])).'</strong>
                                </div>
                                <div class="col-md-6">
                                    <span>Pay before</span> <strong>'.Date("M d, Y", strtotime('+1 day', strtotime($m['transaction_time']))).'</strong>
                                </div>
                                <div class="col-md-12 mt-4">
                                    '.$payment_type.'
                                </div>
                                <div class="col-md-12 '.$is_hide.'">
                                    <span>Virtual account</span>
                                    <a class="text-success copy_toclipboard" data-clipboard-target="#va_number"><strong>'.$virtual_account.'</strong><i class="fas fa-copy ml-2"></i></a>
                                    <span class="copied"></span>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <span class="small">Segera selesaikan pembayaran kamu, agar pesananmu dapat kami proses.</span>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Qty</th>
                                                <th>Makanan</th>
                                                <th class="text-right">Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                            $pesanandata = $this->model->get_detail($m['pesanan_id']);
                                            foreach($pesanandata as $ps) {
                                                $tr .= '<tr>
                                                    <td class="text-left">'.str_pad($ps['qty_pesanan'], 2, "0", STR_PAD_LEFT).'</td>
                                                    <td><strong>'.$ps['makanan_nama'].'</strong></td>
                                                    <td class="text-right">Rp. '.number_format($ps['total_pesanan']).'</td>
                                                </tr>';
                                            }
                                        $tr .= '</tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-left" colspan="2">Total Bayar</td>
                                                <td class="text-right"><strong>Rp. '.number_format($m['pesanan_total']).'</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        } else {
            $tr .= '<tr>';
            $tr .= '<td colspan="4">No data</td>';
            $tr .= '</tr>';
        }

        $result = [
            'result' => true,
            'hasil' => $tr,
            'cart' => $cart['data'][0],
            'pesanan_total' => ($cart['data']) ? number_format($cart['data'][0]['pesanan_total']) : 0
        ];

        echo json_encode($result);
    }
}