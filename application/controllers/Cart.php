<?php defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

if ( "OPTIONS" === $_SERVER['REQUEST_METHOD'] ) {
    die();
}

class Cart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cart_model', 'model');
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

    public function ajaxGetData()
    {
        $email = $this->session->userdata('client_email');
        
        $cart = $this->model->get_data($email);
        $tr = '';

        if(count($cart['data']) > 0) {
            foreach($cart['data'] as $m) {
                $tr .= '<div class="col-md-12 mb-3">';
                $tr .= '<div class="card">';
                $tr .= '<div class="card-body">';
                $tr .= '<div class="d-none d-md-block">';
                $tr .= '<div class="row">';
                $tr .= '<div class="col-md-2 align-self-center">';
                $tr .= '<img src="'.base_url().'assets/img/food_porn/'.$m['makanan_img'].'" class="img-thumbnail" alt="" width="100">';
                $tr .= '</div>';
                $tr .= '<div class="col-md-10 align-self-center">';
                $tr .= '<div class="row">';
                $tr .= '<div class="col-md-12">';
                $tr .= '<p>'.$m['makanan_nama'].'</p>';
                $tr .= '</div>';
                $tr .= '<div class="col-md-1">';
                $tr .= '<button type="button" class="btn btn-sm btn-outline-danger checkout_delete" data-id="'.$m['detpesanan_id'].'" data-pesananid="'.$m['pesanan_id'].'"><i class="fas fa-fw fa-trash small"></i></button>';
                $tr .= '</div>';
                $tr .= '<div class="col-md-5">';
                $tr .= '<button class="btn btn-sm btn-outline-success mr-2 btn-qty" data-id="'.$m['detpesanan_id'].'" data-pesananid="'.$m['pesanan_id'].'" data-type="-"><i class="fas fa-minus small"></i></button>';
                $tr .= '<span>'.$m['qty_pesanan'].'x</span>';
                $tr .= '<button class="btn btn-sm btn-outline-success ml-2 btn-qty" data-id="'.$m['detpesanan_id'].'" data-pesananid="'.$m['pesanan_id'].'" data-type="+"><i class="fas fa-plus small"></i></button>';
                $tr .= '</div>';
                $tr .= '<div class="col-md-6 text-right">';
                $tr .= '<p>Rp. '.number_format($m['total_pesanan']).'</p>';
                $tr .= '</div></div></div></div></div>';

                $tr .= '<div class="d-sm-block d-md-none">';
                $tr .= '<div class="row">';
                $tr .= '<div class="col-5">';
                $tr .= '<img src="'.base_url().'assets/img/food_porn/'.$m['makanan_img'].'" class="img-thumbnail" alt="" width="100%">';
                $tr .= '</div>';
                $tr .= '<div class="col-7">';
                $tr .= '<div class="row">';
                $tr .= '<div class="col-12">';
                $tr .= '<p>'.$m['makanan_nama'].'</p>';
                $tr .= '<p>Rp. '.number_format($m['total_pesanan']).'</p>';
                $tr .= '</div>';
                $tr .= '<div class="col-9">';
                $tr .= '<button class="btn btn-sm btn-outline-success mr-2 btn-qty" data-id="'.$m['detpesanan_id'].'" data-pesananid="'.$m['pesanan_id'].'" data-type="-"><i class="fas fa-minus small"></i></button>';
                $tr .= '<span>'.$m['qty_pesanan'].'x</span>';
                $tr .= '<button class="btn btn-sm btn-outline-success ml-2 btn-qty" data-id="'.$m['detpesanan_id'].'" data-pesananid="'.$m['pesanan_id'].'" data-type="+"><i class="fas fa-plus small"></i></button>';
                $tr .= '</div>';
                $tr .= '<div class="col-3 text-right">';
                $tr .= '<button type="button" class="btn btn-sm btn-outline-danger checkout_delete" data-id="'.$m['detpesanan_id'].'" data-pesananid="'.$m['pesanan_id'].'"><i class="fa fa-trash small"></i></button>';
                $tr .= '</div></div></div></div></div></div></div></div>';
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

    public function ajaxGetMeja()
    {
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $limit = $this->input->get('limit') ? $this->input->get('limit') : 3;
        $search = $this->input->get('search', true);
        $chair = $this->input->get('chair', true);

        $offset = ($page - 1) * $limit;

        $jenisMenu = $this->model->getAllMeja($chair, $offset, $limit, $search);

        echo json_encode($jenisMenu);
    }

    public function ajaxUpdateQty()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $pesanan = $this->input->post('pesanan');

        $satu = 1;

        $pesanandetail = $this->model->getDetailById($id);
        $pesananheader = $this->model->getHeaderById($pesanan);

        if($type == "-") {
            $header = [
                'pesanan_total' => $pesananheader['pesanan_total']-$pesanandetail['makanan_harga'],
                'dateModified' => Date("Y-m-d H:i:s"),
                'email_update' => $this->session->userdata('client_email')
            ];
    
            $this->model->update($header, 'pesanan_id', $pesanan, 'pesanan_header');

            if($pesanandetail['qty_pesanan'] == 1) {
                $this->model->delete($id, 'detpesanan_id', 'pesanan_detail');
            } else {
                $detail = [
                    'qty_pesanan' => $pesanandetail['qty_pesanan']-$satu,
                    'total_pesanan' => $pesanandetail['total_pesanan']-$pesanandetail['makanan_harga'],
                    'dateModified' => Date("Y-m-d H:i:s"),
                    'email_update' => $this->session->userdata('client_email')
                ];
    
                $this->model->update($detail, 'detpesanan_id', $id, 'pesanan_detail');
            }
        } else {
            $detail = [
                'qty_pesanan' => $pesanandetail['qty_pesanan']+$satu,
                'total_pesanan' => $pesanandetail['total_pesanan']+$pesanandetail['makanan_harga'],
                'dateModified' => Date("Y-m-d H:i:s"),
                'email_update' => $this->session->userdata('client_email')
            ];

            $this->model->update($detail, 'detpesanan_id', $id, 'pesanan_detail');

            $header = [
                'pesanan_total' => $pesananheader['pesanan_total']+$pesanandetail['makanan_harga'],
                'dateModified' => Date("Y-m-d H:i:s"),
                'email_update' => $this->session->userdata('client_email')
            ];
    
            $this->model->update($header, 'pesanan_id', $pesanan, 'pesanan_header');
        }

        $result = [
            'result' => true,
            'message' => 'Update'
        ];

        echo json_encode($result);
    }

    public function ajaxDeleteItem()
    {
        $id = $this->input->post('id');
        $pesanan = $this->input->post('pesanan');
        
        $pesanandetail = $this->model->getDetailById($id);
        $pesananheader = $this->model->getHeaderById($pesanan);

        $this->model->delete($id, 'detpesanan_id', 'pesanan_detail');

        $header = [
            'pesanan_total' => $pesananheader['pesanan_total']-$pesanandetail['total_pesanan'],
            'dateModified' => Date("Y-m-d H:i:s"),
            'email_update' => $this->session->userdata('client_email')
        ];

        $this->model->update($header, 'pesanan_id', $pesanan, 'pesanan_header');

        $result = [
            'result' => true,
            'message' => 'Delete'
        ];

        echo json_encode($result);
    }

    public function ajaxDropStatus()
    {
        $pesanan = $this->input->post('pesanan');
        $payment = $this->input->post('payment');

        $header = $this->model->getHeaderById($pesanan);
        $detail = $this->model->getDetailByHeader($pesanan);
        $user = $this->db->get_where('users', ['user_email' => $this->session->userdata('client_email')])->row_array();

        if($payment == "cashless") {
            $transaction_details = [
                'order_id' => rand(),
                'gross_amount' => $header['pesanan_total'], // no decimal allowed for creditcard
            ];

		    $item_details = [];
            foreach($detail as $det) {
                $item_details[] = [
                    'id' => $det['detpesanan_id'],
                    'price' => $det['makanan_harga'],
                    'quantity' => $det['qty_pesanan'],
                    'name' => $det['makanan_nama']
                ];
            }

            $customer_details = [
                'first_name'    => $user['user_nama'],
                'last_name'     => '',
                'email'         => $user['user_email'],
                'phone'         => '',
                'billing_address'  => '',
                'shipping_address' => ''
            ];

            $credit_card['secure'] = true;

            $time = time();
            $custom_expiry = [
                'start_time' => date("Y-m-d H:i:s O", $time),
                'unit' => 'minute', 
                'duration'  => 15
            ];
            
            $transaction_data = [
                'transaction_details'=> $transaction_details,
                'item_details'       => $item_details,
                'customer_details'   => $customer_details,
                'credit_card'        => $credit_card,
                'expiry'             => $custom_expiry
            ];

            // print_r($transaction_data); exit();

            error_log(json_encode($transaction_data));
            $snapToken = $this->midtrans->getSnapToken($transaction_data);
            error_log($snapToken);

		    echo json_encode($snapToken);
        } else {
            $header = [
                'payment_type' => $payment,
                'pesanan_status' => 0,
                'dateModified' => Date("Y-m-d H:i:s"),
                'email_update' => $this->session->userdata('client_email')
            ];
    
            $update = $this->model->update($header, 'pesanan_id', $pesanan, 'pesanan_header');
    
            $detail = [
                'detpesanan_status' => 0,
                'dateModified' => Date("Y-m-d H:i:s"),
                'email_update' => $this->session->userdata('client_email')
            ];
    
            $update = $this->model->update($detail, 'pesanan_id', $pesanan, 'pesanan_detail');
    
            if($update) {
                $result = [
                    'result' => true,
                    'message' => 'update status'
                ];
        
                echo json_encode($result);
            } else {
                $result = [
                    'result' => false,
                    'message' => 'update status'
                ];
        
                echo json_encode($result);
            }
        }
    }
}