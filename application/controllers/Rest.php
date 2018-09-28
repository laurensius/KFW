<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('mod_user');
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

	// ------------------------ END OF USER ------------------------------------


	// ------------------------ TOKO ------------------------------------
	function toko_detail($id = null){
		if($id != null){
			$select_where = array(
				"id" => $id
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
