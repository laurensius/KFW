<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_toko extends CI_Model {

	function toko_detail($param){
        $this->db->select("*");
        $this->db->from("t_toko");
        $this->db->where($param);
        $query = $this->db->get();
        return $query->result();
	}
}
