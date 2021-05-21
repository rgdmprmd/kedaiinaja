<?php defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function getCategory()
    {
        return $this->db->get_where('kedaiinaja.menu_jenis', ['makananjenis_status' => 1])->result_array();
    }

    public function get_data($search, $status, $limit, $offset)
    {
        ($search) ? $where_search = " AND mk.makanan_nama LIKE '%{$search}%'" : $where_search = "";
        ($status != "all") ? $where_status = " AND mk.makananjenis_id = {$status}" : $where_status = "";
        
        $sql = "SELECT * FROM menu_makanan mk JOIN menu_jenis mj USING(makananjenis_id) WHERE 0=0 {$where_status} {$where_search} ORDER BY mk.makananjenis_id, mk.makanan_nama ASC LIMIT {$offset}, {$limit}";
        $sql_count = "SELECT * FROM menu_makanan mk JOIN menu_jenis mj USING(makananjenis_id) WHERE 0=0 {$where_status} {$where_search} ORDER BY mk.makananjenis_id, mk.makanan_nama ASC";
    
        $data['data'] = $this->db->query($sql)->result_array();
        $data['total'] = $this->db->query($sql_count)->num_rows();

        return $data;
    }
}