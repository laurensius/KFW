<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_kategori extends CI_Model {

        function select(){
                $this->db->select("*");
                $this->db->from("t_kategori");
                $query = $this->db->get();
                return $query->result();
        }
}
