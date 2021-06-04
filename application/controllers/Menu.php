<?php defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model', 'model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $data['title'] = 'Menu';
        $data['category'] = $this->model->getCategory();
        $data['user'] = $this->db->get_where('users', ['user_email' => $this->session->userdata('client_email')])->row_array();
        $data['order'] = $this->db->get_where('pesanan_header', ['pesanan_status' => 0, 'email_input' => $this->session->userdata('client_email')])->result_array();
        $data['meja'] = $this->input->get('meja');
        $data['type'] = $this->input->get('type');

        $this->load->view('templates/header_client', $data);
        $this->load->view('menu/menu', $data);
        $this->load->view('templates/footer_client');

    }

    public function ajaxGetData()
    {
        $search = $this->input->post('search', true);
        $status = $this->input->post('status', true);
        $meja = $this->input->get('meja');
        $type = $this->input->get('type');
        $offset = $this->uri->segment(3, 0);
        $email = $this->session->userdata('client_email');
        $limit  = 99;
        
        $menu = $this->model->get_data($search, $status, $email, $limit, $offset);
        $tr = '';
        $paging = '';
        
        if($menu['total'] > 0) {
            $total = $menu['total'];
            $i = $offset + 1;
            
            foreach($menu['data'] as $m) {
                $tr .= '<div class="col-lg-3 col-md-4 col-6 mb-3">';
                $tr .= '<div class="card food-card">';
                $tr .= '<img src="'.base_url().'assets/img/food_porn/'.$m['makanan_img'].'" class="card-img-top imgs" alt="'.$m['makanan_nama'].'">';
                $tr .= '<div class="card-body">';
                $tr .= '<h5 class="card-title">'.$m['makanan_nama'].'</h5>';
                $tr .= '<p class="card-text">Harga : Rp. '.number_format($m['makanan_harga']).'</p>';
                $tr .= '<button class="btn btn-success add-to-cart" data-id="'.$m['makanan_id'].'" data-user="'.$this->session->userdata('client_email').'">Add <i class="fas fa-cart-plus"></i></button>';
                $tr .= '</div>';
                $tr .= '</div>';
                $tr .= '</div>';
            }

            $paging .= $this->_paging($total, $limit, 'ajaxGetAllMenu');
            $paging .= '<span class="page-info">Displaying ' . ($i - 1) . ' of ' . $total . ' data</span>';
        } else {
            $tr .= '<tr>';
            $tr .= '<td colspan="4">No data</td>';
            $tr .= '</tr>';
        }

        $result = [
            'result' => true,
            'error' => $paging,
            'cart' => $menu['cart'],
            'hasil' => $tr
        ];

        echo json_encode($result);
    }

    public function ajaxAddToCart()
    {
        $uid = $this->input->post('uid', true);
        $makanan = $this->input->post('makanan', true);
        $meja = $this->input->post('meja', true);
        $type = $this->input->post('type', true);

        $food = $this->model->getFoodDetail($makanan);
        $pesanan_id = $this->model->getPesananByEmail($uid);

        if(!$pesanan_id) {
            $this->_pesanan_baru($food, $meja, $type, $uid);
        } else {
            $this->_pesanan_update($food, $meja, $type, $uid, $pesanan_id);
        }

        $result = [
            'result' => true,
            'message' => 'Tambah'
        ];

        echo json_encode($result);
    }

    private function _paging($total, $limit, $modul)
    {
        $config = [
            'base_url'  => base_url() . 'menu/' . $modul,
            'total_rows' => $total,
            'per_page'  => $limit,
            'uri_segment' => 3
        ];

        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

    private function _pesanan_baru($food, $meja, $type, $uid)
    {
        ($meja) ? $meja_id = $this->model->getMejaIDByName($meja) : $meja_id = "";

        $header = [
            'meja_id' => ($meja_id) ? $meja_id['meja_id'] : null,
            'type' => ($type) ? $type : null,
            'payment_type' => null,
            'jumlah_tamu' => ($meja_id) ? $meja_id['kursi_tersedia'] : null,
            'pesanan_total' => $food['makanan_harga'],
            'pesanan_status' => 88,
            'dateCreated' => Date("Y-m-d H:i:s"),
            'dateModified' => Date("Y-m-d H:i:s"),
            'email_input' => $uid,
            'email_update' => $uid
        ];

        $header_id = $this->model->insert($header, 'pesanan_header');

        $detail = [
            'pesanan_id' => $header_id,
            'makanan_id' => $food['makanan_id'],
            'qty_pesanan' => 1,
            'total_pesanan' => $food['makanan_harga'] * 1,
            'detpesanan_status' => 88,
            'dateCreated' => Date("Y-m-d H:i:s"),
            'dateModified' => Date("Y-m-d H:i:s"),
            'email_input' => $uid,
            'email_update' => $uid
        ];

        $detail_id = $this->model->insert($detail, 'pesanan_detail');

        if($detail_id) {
            return true;
        }

        return false;
    }

    private function _pesanan_update($food, $meja, $type, $uid, $pesanan_id)
    {
        $pesanan_duplicate = $this->model->getDetailByIDPesanan($pesanan_id['pesanan_id'], $food['makanan_id']);

        if($pesanan_duplicate) {
            // jika makanan nya sama, maka update qty nya saja
            $detail = [
                'qty_pesanan' => $pesanan_duplicate['qty_pesanan'] + 1,
                'total_pesanan' => $pesanan_duplicate['total_pesanan']+$food['makanan_harga'],
                'dateModified' => Date("Y-m-d H:i:s"),
                'email_update' => $uid
            ];

            $update_detail = $this->model->update($detail, 'detpesanan_id', $pesanan_duplicate['detpesanan_id'], 'pesanan_detail');
        } else {
            // jika makanan nya baru, maka tambah pesanan_detail nya
            $detail = [
                'pesanan_id' => $pesanan_id['pesanan_id'],
                'makanan_id' => $food['makanan_id'],
                'qty_pesanan' => 1,
                'total_pesanan' => $food['makanan_harga'] * 1,
                'detpesanan_status' => 88,
                'dateCreated' => Date("Y-m-d H:i:s"),
                'dateModified' => Date("Y-m-d H:i:s"),
                'email_input' => $uid,
                'email_update' => $uid
            ];
    
            $update_detail = $this->model->insert($detail, 'pesanan_detail');
        }


        ($meja) ? $meja_id = $this->model->getMejaIDByName($meja) : $meja_id = "";

        $header = [
            'meja_id' => ($meja_id) ? $meja_id['meja_id'] : null,
            'type' => ($type) ? $type : null,
            'jumlah_tamu' => ($meja_id) ? $meja_id['kursi_tersedia'] : null,
            'pesanan_total' => $pesanan_id['pesanan_total']+$food['makanan_harga'],
            'dateModified' => Date("Y-m-d H:i:s"),
            'email_update' => $uid
        ];

        $update_header = $this->model->update($header, 'pesanan_id', $pesanan_id['pesanan_id'], 'pesanan_header');

        if($update_header && $update_detail) {
            return true;
        }

        return false;
    }
}