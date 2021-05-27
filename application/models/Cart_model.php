<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cart_model extends CI_Model
{
    public function get_data($email)
    {
        $sql = "SELECT * FROM pesanan_header ph JOIN pesanan_detail pd USING(pesanan_id) JOIN menu_makanan mm USING (makanan_id) WHERE ph.pesanan_status = 88 AND ph.email_input = '{$email}' ORDER BY pd.pesanan_id ASC";
        $data['data'] = $this->db->query($sql)->result_array();

        return $data;
    }

    public function getDetailById($id)
    {
        $sql = "SELECT * FROM pesanan_detail pd JOIN menu_makanan USING (makanan_id) WHERE pd.detpesanan_id = {$id}";
        return $this->db->query($sql)->row_array();
    }

    public function getHeaderById($id)
    {
        return $this->db->get_where('pesanan_header', ['pesanan_id' => $id])->row_array();
    }

    public function delete($id, $key, $table)
    {
        $this->db->delete($table, [$key => $id]);
    }

    public function update($data, $key, $id, $table)
    {
        $this->db->where($key, $id);
        $this->db->update($table, $data);
    }
}