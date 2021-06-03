<?php defined('BASEPATH') or exit('No direct script access allowed');

class Snap_model extends CI_Model
{
    public function getPesanan()
    {
        return $this->db->get_where('pesanan_header', ['pesanan_status' => 0, 'email_input' => $this->session->userdata('client_email')])->row_array();
    }

    public function insert($data)
    {
        return $this->db->insert('tb_transaction', $data);
    }
}