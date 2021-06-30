<?php defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function getCategory()
    {
        return $this->db->get_where('menu_jenis', ['makananjenis_status' => 1])->result_array();
    }

    public function get_data($search, $status, $email, $limit, $offset)
    {
        ($search) ? $where_search = " AND mk.makanan_nama LIKE '%{$search}%'" : $where_search = "";
        ($status != "all") ? $where_status = " AND mk.makananjenis_id = {$status}" : $where_status = "";

        $sql = "SELECT * FROM menu_makanan mk JOIN menu_jenis mj USING(makananjenis_id) WHERE 0=0 {$where_status} {$where_search} ORDER BY mk.makananjenis_id, mk.makanan_nama ASC LIMIT {$offset}, {$limit}";
        $sql_count = "SELECT * FROM menu_makanan mk JOIN menu_jenis mj USING(makananjenis_id) WHERE 0=0 {$where_status} {$where_search} ORDER BY mk.makananjenis_id, mk.makanan_nama ASC";

        if ($email) {
            $sql_cart = "SELECT sum(total_pesanan) as total_pesanan FROM pesanan_detail WHERE detpesanan_status = 88 AND email_input = '{$email}'";
            $data['cart'] = $this->db->query($sql_cart)->row_array();
        } else {
            $data['cart'] = [];
        }

        $data['data'] = $this->db->query($sql)->result_array();
        $data['total'] = $this->db->query($sql_count)->num_rows();

        return $data;
    }

    public function getFoodDetail($makanan)
    {
        return $this->db->get_where('menu_makanan', ['makanan_id' => $makanan])->row_array();
    }

    public function getMejaIDByName($meja)
    {
        return $this->db->get_where('meja', ['meja_nomer' => $meja])->row_array();
    }

    public function getPesananByEmail($uid)
    {
        // pesanan_status == 88 == cart_mode
        return $this->db->get_where('pesanan_header', ['email_input' => $uid, 'pesanan_status' => 88])->row_array();
    }

    public function getDetailByIDPesanan($id_pesanan, $id_makanan)
    {
        return $this->db->get_where('pesanan_detail', ['pesanan_id' => $id_pesanan, 'makanan_id' => $id_makanan])->row_array();
    }

    public function insert($data, $table)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function update($data, $key, $id, $table)
    {
        $this->db->where($key, $id);
        $this->db->update($table, $data);

        return true;
    }
}
