<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class PruebasMenu extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('Cliente_model');
	}
	function index() {  
		$response = array();
		//verificar token
			
			$response["status"] = "BIEN";
			$response["respuesta"] = "Prueba correcto";
		
		echo json_encode($response);
    } 
	 
	
}