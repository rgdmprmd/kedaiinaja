<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cart_model extends CI_Model
{
    public function get_data($email)
    {
        $sql = "SELECT * FROM pesanan_header ph JOIN pesanan_detail pd USING(pesanan_id) LEFT JOIN meja mj USING (meja_id) JOIN menu_makanan mm USING (makanan_id) WHERE ph.pesanan_status = 88 AND ph.email_input = '{$email}' ORDER BY pd.pesanan_id ASC";
        $data['data'] = $this->db->query($sql)->result_array();

        return $data;
    }

    public function getDetailById($id)
    {
        $sql = "SELECT * FROM pesanan_detail pd JOIN menu_makanan USING (makanan_id) WHERE pd.detpesanan_id = {$id}";
        return $this->db->query($sql)->row_array();
    }

    public function getDetailByHeader($id)
    {
        $sql = "SELECT * FROM pesanan_detail pd JOIN menu_makanan mm USING (makanan_id) WHERE pd.pesanan_id = {$id}";
        return $this->db->query($sql)->result_array();
    }
    
    public function getHeaderById($id)
    {
        return $this->db->get_where('pesanan_header', ['pesanan_id' => $id])->row_array();
    }

    public function getMeja($id=null)
    {
        ($id) ? $data['detail'] = $this->db->get_where('meja', ['meja_nomer' => $id])->row_array() : $data['detail'] = '';
        $data['all'] = $this->db->get_where('meja', ['isTaken' => 0])->result_array( );

        return $data;
    }

    public function getAllMeja($chair=0, $offset = 0, $limit = 0, $search = null)
    {
        ($chair) ? $w_chair = " AND kursi_tersedia <= {$chair} " : $w_chair = "";
        ($search != null) ? $w_search = " AND meja_nomer LIKE '%{$search}%'" : $w_search = "";

        $sql = "SELECT * FROM meja WHERE isTaken = 0 {$w_chair} {$w_search}";

        $total = $this->db->query($sql)->num_rows();

        if ($limit > 0) {
            $sql .= " ORDER BY meja_id ASC LIMIT {$offset}, {$limit} ";
        }

        $data = $this->db->query($sql)->result_array();

        $rowResult = array();
        $result = array();

        if ($data) {
            foreach ($data as $d) {
                $result['id']       = $d['meja_id'];
                $result['text']     = $d['meja_nomer'];
                $result['status']   = true;

                array_push($rowResult, $result);
            }
        } else {
            $result['id'] = null;
            $result['text'] = 'Oops, meja tidak ditemukan.';
            $result['status'] = '404';

            array_push($rowResult, $result);
        }

        return array('results' => $rowResult, 'total' => $total);
    }

    public function delete($id, $key, $table)
    {
        $this->db->delete($table, [$key => $id]);
    }

    public function update($data, $key, $id, $table)
    {
        $this->db->where($key, $id);
        $this->db->update($table, $data);

        return true;
    }

    public function insert($table, $data)
    {
        return $this->db->insert($table, $data);
    }
}