<?php defined('BASEPATH') or exit('No direct script access allowed');

// Controller admin yang menglola admin page
class Home_model extends CI_Model
{
    public function getCategory()
    {
        return $this->db->get_where('menu_jenis', ['makananjenis_status' => 1])->result_array();
    }
}
