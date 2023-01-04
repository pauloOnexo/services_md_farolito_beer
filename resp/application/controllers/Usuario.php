<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Usuario extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('Usuarios_model');
		$this->load->model('Rol_model');
		$this->load->model('Marca_model');
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
		$response["registros"] = $this->Usuarios_model->get();
		}
		//$this->load->view('Subcategoriaes',$response); 
		echo json_encode($response);
    } 
	
	public function add(){
		
		$token=$_GET['token'];		
		$id_marca=$_GET['id_marca'];
		$response = array();
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["roles"] = $this->Rol_model->get();
			$response["marcas"] = $this->Marca_model->get();
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
			'correo' => $_POST["correo"],
			'telefono' => $_POST["telefono"],
			'contrasenha' => sha1($_POST["contrasenha"]),
		    'id_rol' => $_POST["id_rol"],
		    'id_marca' => $_POST["id_marca"]
        );
		
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{	
			if($this->Usuarios_model->add($data)){
				$response["status"] = "OK";
				$response["respuesta"] = "Usuario registrado correctamente";
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
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["registro"] = $this->Usuarios_model->getById($id);
			$response["roles"] = $this->Rol_model->get();
			$response["marcas"] = $this->Marca_model->get();
		}
		echo json_encode($response);	
		//$this->load->view('update_region',$response); 
    } 
	public function update_db(){
		
		$token=$_POST['token'];	
		
		$response = array();
		$data = array();
		
		if($_POST["contrasenha"] != "null"){
		    $data = array(
			'nombre' => $_POST["nombre"],
			'correo' => $_POST["correo"],
			'id_rol' => $_POST["id_rol"],
			'id_marca' => $_POST["id_marca"],
			'telefono' => $_POST["telefono"],
			'contrasenha' => sha1($_POST["contrasenha"]),
        );
		}else{
		    $data = array(
			'nombre' => $_POST["nombre"],
			'correo' => $_POST["correo"],
			'id_rol' => $_POST["id_rol"],
			'id_marca' => $_POST["id_marca"],
			'telefono' => $_POST["telefono"],
        );
		}
		
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			if($this->Usuarios_model->update($data,$_POST["id"])){
				$response["status"] = "OK";
				$response["respuesta"] = "Usuario actualizado correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al actualizar el usuario";
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
			if($this->Usuarios_model->eliminar($id)){
				$response["status"] = "OK";
				$response["respuesta"] = "Usuario eliminado correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al eliminar el usuario";
			}
		}
        echo json_encode($response);
	}
}