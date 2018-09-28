<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_user extends CI_Model {

	function user_detail($param){
        $this->db->select("*");
        $this->db->from("t_user");
        $this->db->where($param);
        $query = $this->db->get();
        return $query->result();
	}
}
