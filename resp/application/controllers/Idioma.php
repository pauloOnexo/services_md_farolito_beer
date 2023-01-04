<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Idioma extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('Idioma_model');
	}
	function index() {  
        $_POST = json_decode(file_get_contents("php://input"),true);
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
		}
		$response["registros"] = $this->Idioma_model->get();
		//$this->load->view('Idiomaes',$response); 
		echo json_encode($response);
    } 
	 
	public function add(){
		
		$token=$_GET['token'];		
		$response = array();
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
		//$this->load->view('insert_region',$response); 
	}
	public function add_db(){
        //$_POST = json_decode(file_get_contents("php://input"),true);
		$token=$_POST['token'];	
		$res = $this->login_model->buscar_token($token);
		$data = array(
			'nombre' => $_POST["nombre"],
			'descripcion' => $_POST["descripcion"],
			'activo' => 1,
        );
		
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{	
			if($this->Idioma_model->add($data)){
				$response["status"] = "OK";
				$response["respuesta"] = "Registro guardado correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al guardar el registro ";
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
			$response["registro"] = $this->Idioma_model->getById($id);
		}
		echo json_encode($response);	
		//$this->load->view('update_region',$response); 
    } 
	public function update_db(){
		
        //$_POST = json_decode(file_get_contents("php://input"),true);
		$token=$_POST['token'];	
		
		$response = array();
		$data = array(
			'nombre' => $_POST["nombre"],
			'descripcion' => $_POST["descripcion"],
			'activo' => $_POST["activo"],
        );
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			if($this->Idioma_model->update($data,$_POST["id"])){
				$response["status"] = "OK";
				$response["respuesta"] = "Registro actualizado correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al actualizar el registro";
			}
		}
        echo json_encode($response);
	}
	public function delete(){
		
        //$_POST = json_decode(file_get_contents("php://input"),true);
		$token=$_POST['token'];	
		$id= $_POST["id"];
		
		$response = array();
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			if($this->Idioma_model->eliminar($id)){
				$response["status"] = "OK";
				$response["respuesta"] = "Registro eliminado correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al eliminar el registro";
			}
		}
        echo json_encode($response);
	}
}