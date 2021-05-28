<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cart_model', 'model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $data['title'] = 'Cart';
        $data['meja'] = $this->input->get('meja');
        $data['type'] = $this->input->get('type');
        $data['meja_list'] = $this->model->getMeja($data['meja']);

        // print_r($data['meja_list']['all']); exit();
        $data['user'] = $this->db->get_where('users', ['user_email' => $this->session->userdata('client_email')])->row_array();

        $this->load->view('templates/client_header', $data);
        $this->load->view('cart/cart', $data);
        $this->load->view('templates/client_footer');
    }

    public function ajaxGetData()
    {
        $email = $this->session->userdata('client_email');
        
        $cart = $this->model->get_data($email);
        $tr = '';

        if(count($cart['data']) > 0) {
            foreach($cart['data'] as $m) {
                $tr .= '<div class="checkout_card">';
                $tr .= '<img src="'.base_url().'assets/img/food_porn/'.$m['makanan_img'].'" alt="food image">';
                $tr .= '<div class="checkout_title">';
                $tr .= '<h4>'.$m['makanan_nama'].'</h4>';
                $tr .= '<p>Rp. '.number_format($m['makanan_harga']).'</p>';
                $tr .= '</div>';
                $tr .= '<p class="qty"><a href="#" class="btn-qty btn-minus" data-id="'.$m['detpesanan_id'].'" data-pesananid="'.$m['pesanan_id'].'" data-type="-"><i class="fas fa-fw fa-minus"></i></a>'.$m['qty_pesanan'].'x<a href="#" class="btn-qty btn-plus" data-id="'.$m['detpesanan_id'].'" data-pesananid="'.$m['pesanan_id'].'" data-type="+"><i class="fas fa-fw fa-plus"></i></a></p>';
                $tr .= '<p class="total_harga">Rp. '.number_format($m['total_pesanan']).'</p>';
                $tr .= '<a href="#" class="checkout_delete" data-id="'.$m['detpesanan_id'].'" data-pesananid="'.$m['pesanan_id'].'"><i class="fas fa-fw fa-times"></i></a>';
                $tr .= '</div>';
            }
        } else {
            $tr .= '<tr>';
            $tr .= '<td colspan="4">No data</td>';
            $tr .= '</tr>';
        }

        $result = [
            'result' => true,
            'hasil' => $tr,
            'pesanan_total' => ($cart['data']) ? number_format($cart['data'][0]['pesanan_total']) : 0
        ];

        echo json_encode($result);
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
}