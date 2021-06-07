<?php defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function get_data($email, $status)
    {
        ($status != 'all') ? $w_stats = " AND tt.transaction_status = '{$status}' " : $w_stats = "";

        $sql = "SELECT * FROM tb_transaction tt JOIN pesanan_header ph USING(pesanan_id) WHERE 1=1 {$w_stats} ORDER BY tt.id DESC";
        $data['data'] = $this->db->query($sql)->result_array();

        return $data;
    }

    public function get_detail($pesanan_id)
    {
        $sql = "SELECT * FROM pesanan_detail pd JOIN menu_makanan mm USING (makanan_id) WHERE pd.pesanan_id = {$pesanan_id}";
        return $this->db->query($sql)->result_array();
    }
}