<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('mod_user');
		$this->load->model('mod_toko');
		$this->load->model('mod_kategori');
		$this->load->model('mod_produk');
		header('Content-type:json');
	}

	public function index(){
	}

	// ------------------------ USER ------------------------------------
	public function user_detail($id = null){
		if($id != null){
			$select_where = array(
				"id" => $id
			);
			$user = $this->mod_user->user_detail($select_where);
			if(sizeof($user) > 0){
				$response = array(
					"severity" => "success",
					"message" => "Load data berhasil",
					"data" => $user,
				);
			}else{
				$response = array(
					"severity" => "warning",
					"message" => "Data user tidak ditemukan",
					"data" => array()
				);
			}
		}else{
			$response = array(
				"severity" => "danger",
				"message" => "Parameter request tidak lengkap",
				"data" => array(),
			);
		}
		echo json_encode($response,JSON_PRETTY_PRINT);
	}

	public function verifikasi(){ 
		if($this->input->post('username') == null && $this->input->post('password') == null){
			$login = file_get_contents('php://input');
			$json = json_decode($login);
			if($json == null){
				$severity = "warning";
				$message = "Tidak ada data dikirim ke server";
				$data_count = "0";
				$data = array();
				$username = null;
				$password = null;
			}else{
				$username = $json->username;
				$password = $json->password;
			}
		}else{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
		}
		if($username != null && $password != null ){
			$check = $this->mod_user->is_registered($username);
			if(sizeof($check) > 0){
				if($check[0]->password == md5($password)){
					$severity = "success";
					$message = "Login berhasil";
					$data_count = (string)sizeof($check);
					$data = $check;
					$toko = $this->mod_toko->toko_detail(array("id_user" => $check[0]->id));
				}else{
					$severity = "warning";
					$message = "Nama pengguna dan kata sandi tidak sesuai";
					$data_count = "0";
					$data = array();
					$toko = array();
				}
			}else{
				$severity = "danger";
				$message = "Nama pengguna tidak terdaftar";
				$data_count = "0";
				$data = $check;
				$toko = array();
			}
		}else{
			$severity = "warning";
			$message = "Tidak ada data dikirim ke server";
			$data_count = "0";
			$data = array();
			$toko = array();
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"data_count" => $data_count,
			"data" => $data,
			"toko" => $toko
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}
	
	function verifikasi_register(){
		if($this->input->post('username') == null && 
		$this->input->post('password') == null && 
		$this->input->post('nama_lengkap') == null &&  
		$this->input->post('alamat') == null && 
		$this->input->post('no_hp') == null){
			$login = file_get_contents('php://input');
			$json = json_decode($login);
			if($json == null){
				$severity = "warning";
				$message = "Tidak ada data dikirim ke server";
				$data_count = "0";
				$data = array();
				$username = null;
				$password = null;
				$nama_lengkap = null;
				$alamat = null;
				$no_hp = null;
			}else{
				$username = $json->username;
				$password = $json->password;
				$nama_lengkap = $json->nama_lengkap;
				$alamat = $json->alamat;
				$no_hp = $json->no_hp;
			}
		}else{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$nama_lengkap = $this->input->post('nama_lengkap');
			$alamat = $this->input->post('alamat');
			$no_hp = $this->input->post('no_hp');
		}

		if($username != null && $password != null && $nama_lengkap != null && $alamat != null && $no_hp != null){
			$check = $this->mod_user->is_registered($username);
			if(sizeof($check) > 0){
				$severity = "danger";
				$message = "Nama pengguna sudah digunakan";
				$data_count = "0";
				$data = array();
			}else{
				$data = array(
					"username" => $username,
					"password" => md5($password),
					"nama_lengkap" => $nama_lengkap,
					"alamat" => $alamat,
					"no_hp" => $no_hp,
					"login_terakhir" => date("Y-m-d H:i:s"));
				$resultcek = $this->mod_user->register($data);
				if($resultcek > 0){
					$severity = "success";
					$message = "Registrasi berhasil";
					$data_count = "0";
					$data = array();
				}else{
					$severity = "danger";
					$message = "Registrasi gagal. Silakan coba lagi";
					$data_count = "0";
					$data = array();
				}
			}
		}else{
			$severity = "warning";
			$message = "Tidak ada data dikirim ke server";
			$data_count = "0";
			$data = array();
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"data_count" => $data_count,
			"data" => $data
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}
	// ------------------------ END OF USER ------------------------------------
	

	// ------------------------ TOKO ------------------------------------
	function toko_detail($id = null){
		if($id != null){
			$select_where = array(
				"t_toko.id" => $id
			);
			$toko = $this->mod_toko->toko_detail($select_where);
			if(sizeof($toko) > 0){
				$response = array(
					"severity" => "success",
					"message" => "Load data berhasil",
					"data" => $toko,
				);
			}else{
				$response = array(
					"severity" => "warning",
					"message" => "Data toko tidak ditemukan",
					"data" => array()
				);
			}
		}else{
			$response = array(
				"severity" => "danger",
				"message" => "Parameter request tidak lengkap",
				"data" => array(),
			);
		}
		echo json_encode($response,JSON_PRETTY_PRINT);
	}
	// ------------------------ END OF TOKO ------------------------------------

	function load_kategori(){
		$data = $this->mod_kategori->select();
		$response = array(
			"kategori" => $data
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}



	function upload_produk(){
		if($this->input->post('id_toko') == null &&
		 $this->input->post('id_kategori') == null &&
		 $this->input->post('nama_produk') == null &&
		 $this->input->post('harga') == null &&
		 $this->input->post('deskripsi') == null &&
		 $this->input->post('status') == null &&
		 $this->input->post('image') == null){
			$login = file_get_contents('php://input');
			$json = json_decode($login);
			if($json == null){
				$severity = "warning";
				$message = "Tidak ada data dikirim ke server";
				$data_count = "0";
				$data = array();
				$id_toko = null;
				$id_kategori = null;
				$nama_produk = null;
				$harga = null;
				$deskripsi = null;
				$status = null;
				$image = null;
			}else{
				$id_toko = $json->id_toko;
				$id_kategori = $json->id_kategori;
				$nama_produk = $json->nama_produk;
				$harga = $json->harga;
				$deskripsi = $json->deskripsi;
				$status = $json->status;
				$image = $json->image;
			}
		}else{
			$id_toko = $this->input->post('id_toko');
			$id_kategori =$this->input->post('id_kategori');
			$nama_produk = $this->input->post('nama_produk');
			$harga = $this->input->post('harga');
			$deskripsi = $this->input->post('deskripsi');
			$status = $this->input->post('status');
			$image = $this->input->post('image');
		}
		if($id_toko != null && 
		$id_kategori != null &&
		$nama_produk != null &&
		$harga != null &&
		$deskripsi != null &&
		$status != null &&
		$image != null){
			$arr_data  = array(
				"id_toko" => $id_toko,
				"id_kategori" => $id_kategori,
				"nama_produk" => $nama_produk,
				"harga" => $harga,
				"deskripsi" => $deskripsi,
				"status" => $status,
				"image" => $image
			);
			$ins = $this->mod_produk->insert($arr_data);
			if($ins > 0){
				$severity = "success";
				$message = "Upload produk berhasil";
				$data_count = "0";
				$data = array();
				$toko = array();
			}else{
				$severity = "warning";
				$message = "Upload produk gagal";
				$data_count = "0";
				$data = array();
				$toko = array();
			}
		}else{
			$severity = "danger";
			$message = "Tidak ada data dikirim ke server";
			$data_count = "0";
			$data = array();
			$toko = array();
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"data_count" => $data_count,
			"data" => $data,
			"toko" => $toko
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}

	public function produk_terbaru($limit=3){
		$load = $this->mod_produk->produk_terbaru($limit);
		if(sizeof($load) > 0){
			$severity = "success";
			$message = "Load data berhasil";
			$data_count = string(sizeof($load));
			$data = $load;
		}else{
			$severity = "danger";
			$message = "Tidak ada data";
			$data_count = "0";
			$data = array();
		}
		$response = array(
			"severity" => $severity,
			"message" => $message,
			"data_count" => $data_count,
			"data" => $data,
		);
		echo json_encode($response,JSON_PRETTY_PRINT);
	}

}

