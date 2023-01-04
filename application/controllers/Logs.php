<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Logs extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('Registros_model');
	}
	function index() {  
		$fecha_solicitud=$_GET['fecha_solicitud_i'];
		$hora_solicitud_i=$_GET['hora_solicitud_i'];
		$hora_solicitud_f=$_GET['hora_solicitud_f'];
		$id_marca=$_GET['id_marca'];
		
		$response = array();
		$response["registros"] = $this->Registros_model->get($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f,$id_marca);
		echo json_encode($response);
    } 
	function ip() {  
		$fecha_solicitud=$_GET['fecha_solicitud_i'];
		$hora_solicitud_i=$_GET['hora_solicitud_i'];
		$hora_solicitud_f=$_GET['hora_solicitud_f'];
		
		$response = array();
		$response["registros"] = $this->Registros_model->get_ip($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f);
		echo json_encode($response);
    } 
	function marca() {  
		$fecha_solicitud=$_GET['fecha_solicitud_i'];
		$hora_solicitud_i=$_GET['hora_solicitud_i'];
		$hora_solicitud_f=$_GET['hora_solicitud_f'];
		
		$response = array();
		$response["registros"] = $this->Registros_model->get_marca($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f);
		echo json_encode($response);
    } 
	function unidad_ip() {  
		$fecha_solicitud=$_GET['fecha_solicitud_i'];
		$hora_solicitud_i=$_GET['hora_solicitud_i'];
		$hora_solicitud_f=$_GET['hora_solicitud_f'];
		$id_marca=$_GET['id_marca'];
		
		$response = array();
		$response["registros"] = $this->Registros_model->get_unidad_ip($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f,$id_marca);
		echo json_encode($response);
    } 
	function unidad_marca() {  
		$fecha_solicitud=$_GET['fecha_solicitud_i'];
		$hora_solicitud_i=$_GET['hora_solicitud_i'];
		$hora_solicitud_f=$_GET['hora_solicitud_f'];
		$id_marca=$_GET['id_marca'];
		
		$response = array();
		$response["registros"] = $this->Registros_model->get_unidad_marca($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f,$id_marca);
		echo json_encode($response);
    } 
	function ip_marca() {  
		$fecha_solicitud=$_GET['fecha_solicitud_i'];
		$hora_solicitud_i=$_GET['hora_solicitud_i'];
		$hora_solicitud_f=$_GET['hora_solicitud_f'];
		$id_marca=$_GET['id_marca'];
		
		$response = array();
		$response["registros"] = $this->Registros_model->get_ip_marca($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f,$id_marca);
		echo json_encode($response);
    } 
}