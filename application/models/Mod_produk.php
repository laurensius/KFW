<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_produk extends CI_Model {

        function insert($data){
                $this->db->insert("t_produk",$data);
                return $this->db->affected_rows();
        }

        function produk_terbaru($limit){
                $this->db->select("*");
                $this->db->from("t_produk");
                $this->db->order_by("id","desc");
                $this->db->limit($limit);
                $query = $this->db->get();
                return $query->result();
        }
}
