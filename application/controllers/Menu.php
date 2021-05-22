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

        $this->load->view('templates/client_header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('templates/client_footer');
    }

    public function ajaxGetData()
    {
        $search = $this->input->post('search', true);
        $status = $this->input->post('status', true);
        $offset = $this->uri->segment(3, 0);
        $limit  = 20;
        
        $menu = $this->model->get_data($search, $status, $limit, $offset);
        $tr = '';
        $paging = '';
        
        if($menu['total'] > 0) {
            $total = $menu['total'];
            $i = $offset + 1;
            
            foreach($menu['data'] as $m) {

                $tr .= '<div class="card">';
                $tr .= '<img src="'.base_url().'assets/img/food_porn/'.$m['makanan_img'].'" alt="" width="200px">';
                $tr .= '<h3>'.$m['makanan_nama'].'</h3>';
                $tr .= '<small>'.$m['makananjenis_nama'].'</small>';
                $tr .= '<p>Rp. '.number_format($m['makanan_harga']).'</p>';
                $tr .= '<button type="button">Add</button>';
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
            'hasil' => $tr
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
}