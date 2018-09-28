<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_toko extends CI_Model {

	function toko_detail($param){
        $this->db->select("*");
        $this->db->from("t_toko");
        $this->db->join("r_toko_user","t_toko.id = r_toko_user.id_toko ","inner");
        $this->db->join("t_user","t_user.id = r_toko_user.id_user ","inner");

        $this->db->where($param);
        $query = $this->db->get();
        return $query->result();
	}
}
