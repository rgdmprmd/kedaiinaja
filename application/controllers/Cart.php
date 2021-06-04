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
        $countdetail = $this->model->getDetailByHeader($pesanan);

        if(count($countdetail) == 1) {
            $this->model->delete($pesanan, 'pesanan_id', 'pesanan_header');
        } else {
            $header = [
                'pesanan_total' => $pesananheader['pesanan_total']-$pesanandetail['total_pesanan'],
                'dateModified' => Date("Y-m-d H:i:s"),
                'email_update' => $this->session->userdata('client_email')
            ];
    
            $this->model->update($header, 'pesanan_id', $pesanan, 'pesanan_header');
        }

        $this->model->delete($id, 'detpesanan_id', 'pesanan_detail');


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

        $headerData = [
            'payment_type' => $payment,
            'pesanan_status' => 0,
            'dateModified' => Date("Y-m-d H:i:s"),
            'email_update' => $this->session->userdata('client_email')
        ];

        $update = $this->model->update($headerData, 'pesanan_id', $pesanan, 'pesanan_header');

        $detailData = [
            'detpesanan_status' => 0,
            'dateModified' => Date("Y-m-d H:i:s"),
            'email_update' => $this->session->userdata('client_email')
        ];

        $update = $this->model->update($detailData, 'pesanan_id', $pesanan, 'pesanan_detail');

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

            $result = [
                'result' => true,
                'pesanan' => $pesanan,
                'token' => $snapToken
            ];
    
            echo json_encode($result);
        } else {
            if($update) {
                $result = [
                    'result' => true,
                    'pesanan' => $pesanan,
                    'token' => ''
                ];
        
                echo json_encode($result);
            } else {
                $result = [
                    'result' => false,
                    'pesanan' => $pesanan,
                    'token' => ''
                ];
        
                echo json_encode($result);
            }
        }
    }

    public function finish()
    {
        $pesananid = $this->input->get('pesanan');
        $token = $this->input->get('token');
        $result = json_decode($this->input->post('result_data'));

        if($token) {
            if(isset($result->va_numbers[0]->bank)) {
                $bank = $result->va_numbers[0]->bank;
            } else if(isset($result->permata_va_number)) {
                $bank = "permata";
            } else if(isset($result->bill_key) || isset($result->biller_code)) {
                $bank = "mandiri";
            } else {
                $bank = null;
            }
    
            $data = [
                "pesanan_id" => $pesananid,
                "status_code" => $result->status_code,
                "status_message" => $result->status_message,
                "transaction_id" => $result->transaction_id,
                "order_id" => $result->order_id,
                "gross_amount" => $result->gross_amount,
                "payment_type" => $result->payment_type,
                "transaction_time" => Date("Y-m-d H:i:s", strtotime($result->transaction_time)),
                "transaction_status" => $result->transaction_status,
                "pdf_url" => (isset($result->pdf_url)) ? $result->pdf_url : null,
                "finish_redirect_url" => $result->finish_redirect_url,
                "fraud_status" => $result->fraud_status,
                "bank" => $bank,
                "va_number" => (isset($result->va_numbers[0]->va_number)) ? $result->va_numbers[0]->va_number : null,
                "bca_va_number" => (isset($result->bca_va_number)) ? $result->bca_va_number : null,
                "permata_va_number" => (isset($result->permata_va_number)) ? $result->permata_va_number : null,
                "bill_key" => (isset($result->bill_key)) ? $result->bill_key : null,
                "biller_code" => (isset($result->biller_code)) ? $result->biller_code : null,
                "dt_update" => Date("Y-m-d H:i:s"),
            ];
        } else {
            $pesanan = $this->db->get_where('pesanan_header', ['pesanan_id' => $pesananid, 'payment_type' => 'cash', 'email_input' => $this->session->userdata('client_email')])->row_array();
            
            $data = [
                "pesanan_id" => $pesananid,
                "status_code" => 201,
                "status_message" => 'Transaksi sedang diproses',
                "transaction_id" => null,
                "order_id" => null,
                "gross_amount" => $pesanan['pesanan_total'],
                "payment_type" => 'cash',
                "transaction_time" => Date("Y-m-d H:i:s"),
                "transaction_status" => 'pending',
                "pdf_url" => null,
                "finish_redirect_url" => null,
                "fraud_status" => null,
                "bank" => null,
                "va_number" => null,
                "bca_va_number" => null,
                "permata_va_number" => null,
                "bill_key" => null,
                "biller_code" => null,
                "dt_update" => Date("Y-m-d H:i:s"),
            ];
        }

        // print_r($result); exit();
        $insertTrans = $this->model->insert('tb_transaction', $data);

        if($insertTrans) {
            $data['result'] = $result;
        } else {
            echo "Request pembayaran gagal dilakukan.";
        }

        $data['meja'] = "";
        $data['type'] = "";
        $data['user'] = $this->db->get_where('users', ['user_email' => $this->session->userdata('client_email')])->row_array();
        $data['order'] = $this->db->get_where('pesanan_header', ['pesanan_status' => 0, 'email_input' => $this->session->userdata('client_email')])->result_array();
        $data['title'] = "Checkout Berhasil";

        $this->load->view('templates/header_client', $data);
        $this->load->view('cart/finish', $data);
        $this->load->view('templates/footer_client');
    }
}

// stdClass Object
// (
        // [status_code] => 201
        // [status_message] => Transaksi sedang diproses
        // [transaction_id] => 75c14f03-7aa9-4618-b9d0-e08998c0e892
        // [order_id] => 2012260650
        // [gross_amount] => 10000.00
        // [payment_type] => bank_transfer
        // [transaction_time] => 2021-06-04 14:16:17
        // [transaction_status] => pending
        // [va_numbers] => Array
        //     (
        //         [0] => stdClass Object
        //             (
        //                 [bank] => bca
        //                 [va_number] => 89622919780
        //             )

        //     )

        // [fraud_status] => accept
        // [bca_va_number] => 89622919780
        // [pdf_url] => https://app.sandbox.midtrans.com/snap/v1/transactions/4b099730-4cc6-4486-88f6-d3407a8c848c/pdf
        // [finish_redirect_url] => http://example.com?order_id=2012260650&status_code=201&transaction_status=pending
// )