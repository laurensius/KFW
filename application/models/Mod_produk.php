<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_produk extends CI_Model {

        function insert($data){
                $this->db->insert("t_produk",$data);
                return $this->db->affected_rows();
        }

        function produk_terbaru($limit){
                $this->db->select("
                t_produk.id as id_produk,
                t_produk.id_toko,
                t_produk.id_kategori,
                t_produk.nama_produk,
                t_produk.harga, 
                t_produk.deskripsi,
                t_produk.status,
                t_produk.image,  
                t_kategori.id as id_kategori,
                t_kategori.kategori,    
                t_toko.id as id_toko,    
                t_toko.nama_toko,
                t_user.id as id_user,
                t_user.nama_lengkap 
                ");
                $this->db->from("t_produk");
                $this->db->join("t_toko","t_produk.id_toko = t_toko.id","inner");
                $this->db->join("t_kategori","t_produk.id_kategori = t_kategori.id","inner");
                $this->db->join("t_user","t_user.id = t_toko.id_user","inner");
                $this->db->order_by("t_produk.id","desc");
                $this->db->limit($limit);
                $query = $this->db->get();
                return $query->result();
        }
}
