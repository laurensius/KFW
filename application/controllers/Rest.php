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
		$this->load->view('welcome_message');
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
