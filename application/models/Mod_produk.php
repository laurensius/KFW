<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_produk extends CI_Model {

        function insert($data){
                $this->db->insert("t_produk",$data);
                return $this->db->affected_rows();
        }
}
