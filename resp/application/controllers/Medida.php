<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Medida extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('Medida_model');
		$this->load->model('login_model');
	}
	function index() {  
	
		$token=$_GET['token'];
		$response = array();
		//verificar token
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{			
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
		$response["registros"] = $this->Medida_model->get();
		}
		//$this->load->view('Medidaes',$response); 
		echo json_encode($response);
    } 
	 
	public function add(){
		$response = array();
		$token=$_GET['token'];		
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{			
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
		}
		echo json_encode($response);
		//$this->load->view('insert_sucursal',$response); 
	}
public function add_db(){
		$response = array();	
        $_POST = json_decode(file_get_contents("php://input"),true);
		$token=$_POST['token'];	
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{	
			$data = array(
				'nombre' => $_POST["nombre"],
				'activo' => 1,
				'descripcion' => $_POST["descripcion"]
			);
			
		//print_r( $data);
			if($this->Medida_model->add($data)){
				$response["status"] = "OK";
				$response["respuesta"] = "Medida registrada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al registrar la medida";
			}
		}
         echo json_encode($response);
	}
	function update() {  
		$id = $_GET['id'];
		$response = array();
		$token=$_GET['token'];
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{			
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["registro"] = $this->Medida_model->getById($id);
		}
		
		echo json_encode($response);
		//$this->load->view('update_sucursal',$response); 
    } 
	public function update_db(){
        //$_POST = json_decode(file_get_contents("php://input"),true);        
		$token=$_POST['token'];	
		$response = array();	
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{	
			$data = array(
				'nombre' => $_POST["nombre"],
				'activo' => $_POST["activo"],
				'descripcion' => $_POST["descripcion"]
			);
			
			if($this->Medida_model->update($data,$_POST["id"])){
				$response["status"] = "OK";
				$response["respuesta"] = "Medida actualizada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al actualizar la medida";
			}
		}
        echo json_encode($response);
	}
	public function delete(){
		
        $_POST = json_decode(file_get_contents("php://input"),true);
		$token=$_POST['token'];	
		$id= $_POST["id"];
		
		$response = array();
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			if($this->Medida_model->eliminar($id)){
				$response["status"] = "OK";
				$response["respuesta"] = "Medida eliminada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al eliminar la sucursal";
			}
		}
        echo json_encode($response);
	}
}