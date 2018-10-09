<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('mod_user');
		$this->load->model('mod_toko');
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
				}else{
					$severity = "warning";
					$message = "Nama pengguna dan kata sandi tidak sesuai";
					$data_count = "0";
					$data = array();
				}
			}else{
				$severity = "danger";
				$message = "Nama pengguna tidak terdaftar";
				$data_count = "0";
				$data = $check;
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



}

function verifikasi_register(){
	if($this->input->post()!=null){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$nama_lengkap = $this->input->post('nama_lengkap');
		$alamat = $this->input->post('alamat');
		$no_hp = $this->input->post('no_hp');
		$resultcek = $this->mod_user->is_registered($username);
		if($resultcek==null){
			$data = array(
				"username" => $username,
				"password" => md5($password),
				"nama_lengkap" => $nama_lengkap,
				"alamat" => $alamat,
				"no_hp" => $no_hp,
				"login_terrakhir" => date("Y-m-d H:i:s"));
			$resultcek = $this->mod_user->register($data);
			if($resultcek > 0){
				$return = array(
					"status_cek" => "SUCCESS",
					"message" => "Pendaftaran berhasil",
					"message_severity" => "success",
					"data_user" => null);    
			}else{
				$return = array(
					"status_cek" => "FAILED",
					"message" => "Pendaftaran gagal. Silahkan coba lagi.",
					"message_severity" => "warning",
					"data_user" => null);  
			}
		}else{
			 $return = array(
				"status_cek" => "FOUND",
				"message" => "Userneme sudah digunakan, cari username lainnya!",
				"message_severity" => "danger",
				"data_user" => null
			);
		} 
	}else{
		$return = array(
			"status_cek" => "NO DATA POSTED",
			"message" => "Tidak ada data dikirim ke server!",
			"message_severity" => "danger",
			"data_user" => null
		);
	}
	echo json_encode(array("response"=>$return));
}
// ------------------------ END OF USER ------------------------------------