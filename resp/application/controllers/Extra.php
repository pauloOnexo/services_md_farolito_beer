<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Extra extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('Extra_model');
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
		$response["registros"] = $this->Extra_model->get();
		//$this->load->view('Extraes',$response); 
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
			'precio_nacional' => $_POST["precio_nacional"],
			'precio_acapulco' => $_POST["precio_acapulco"],
			'precio_cc' => $_POST["precio_cc"],
			'precio_tijuana' => $_POST["precio_tijuana"],
			'precio_pl' => $_POST["precio_pl"],
			'precio_aeropuerto' => $_POST["precio_aeropuerto"],
			'activo' => 1,
        );
		
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{	
			if($this->Extra_model->add($data)){
				$response["status"] = "OK";
				$response["respuesta"] = "Extra registrada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al registrar la region";
			}
		}
        echo json_encode($response);
	}
	function update() {  
		$id = $_GET['id'];
		$response = array();
		$token=$_GET['token'];	
		$id_idioma= (!isset($_GET['id_idioma'])) ? "0" : $_GET['id_idioma'];
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			if($id_idioma>0){
				$response["registro"] = $this->Extra_model->getByIdIdioma($id,$id_idioma);
			}
			else{
				$response["registro"] = $this->Extra_model->getById($id);
			}
		    $response["idiomas"] = $this->Idioma_model->get_activos();
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
			'precio_nacional' => $_POST["precio_nacional"],
			'precio_acapulco' => $_POST["precio_acapulco"],
			'precio_cc' => $_POST["precio_cc"],
			'precio_tijuana' => $_POST["precio_tijuana"],
			'precio_pl' => $_POST["precio_pl"],
			'precio_aeropuerto' => $_POST["precio_aeropuerto"],
			'activo' => 1,
        );
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			if ( isset( $_POST['id_idioma']) && $_POST['id_idioma'] >0 ) {
					$data ['id_idioma']= $_POST["id_idioma"];
					$data ['id_extra']= $_POST["id"];
					if($this->Extra_model->update_idioma($data,$_POST["id"],$_POST['id_idioma'])){
						$response["status"] = "OK";
						$response["respuesta"] = "Categoría actualizada correctamente";
					}else{
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al actualizar la categoría";
					}
			}
			else{
				if($this->Extra_model->update($data,$_POST["id"])){
					$response["status"] = "OK";
					$response["respuesta"] = "Sucursal actualizada correctamente";
				}else{
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al actualizar la sucursl";
				}
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
			if($this->Extra_model->eliminar($id)){
				$response["status"] = "OK";
				$response["respuesta"] = "Extra eliminada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al eliminar la region";
			}
		}
        echo json_encode($response);
	}
}