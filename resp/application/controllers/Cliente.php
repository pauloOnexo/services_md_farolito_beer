<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Cliente extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('Cliente_model');
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
		$response["registros"] = $this->Cliente_model->get();
		//$this->load->view('Clientees',$response); 
		echo json_encode($response);
    } 
	 function categorias() {  
		$token=$_GET['token'];
		$id_sucursal=$_GET['sucursal'];
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
		$response["registros"] = $this->Cliente_model->categorias($id_sucursal);
		//$this->load->view('Clientees',$response); 
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
			'correo' => $_POST["correo"],
			'contrasenha' => $_POST["contrasenha"],
			'telefono' => $_POST["telefono"]
			);
		
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{	
			if($this->Cliente_model->add($data)){
				$response["status"] = "OK";
				$response["respuesta"] = "Sucursal registrada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al registrar la sucursl";
			}
		}
        echo json_encode($response);
	}
	function recuperar_contrasenha() {  
		$token=$_GET['token'];	
		$response = array();		
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
		// $res =1;
		// if($res == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
		}
		echo json_encode($response);	
		//$this->load->view('cliente_recuperar',$response); 
    } 
	function recuperar_contrasenha_db() {  
        //$_POST = json_decode(file_get_contents("php://input"),true);
		$token=$_POST['token'];	
		$correo=$_POST['correo'];
		$response = array();
		
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
		// $res =1;
		// if($res == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["registro"] = $this->Cliente_model->getByEmail($correo);
		}
		echo json_encode($response);	
		//$this->load->view('update_region',$response); 
    } 
	function update() {  
		$id = $_GET['id'];
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
			$response["registro"] = $this->Cliente_model->getById($id);
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
			'correo' => $_POST["correo"],
			'contrasenha' => $_POST["contrasenha"],
			'telefono' => $_POST["telefono"]
        );
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			if($this->Cliente_model->update($data,$_POST["id"])){
				$response["status"] = "OK";
				$response["respuesta"] = "Sucursal actualizada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al actualizar la sucursl";
			}
		}
        echo json_encode($response);
	}
	function login() {  
		$token=$_GET['token'];
		$response = array();
		$res = $this->login_model->buscar_token($token);
		//$res=1;
		if(count($res) == 0){
		//if($res==0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{			
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
		}
		//$this->load->view('CLogin',$response); 
		echo json_encode($response);
    } 
	function login_db() 
	{ 		
	    //$_POST = json_decode(file_get_contents("php://input"),true);
		$arrayRespuesta = array();
		$nombre = $_POST['correo'];
		$contrasena = sha1($_POST['contrasena']);
		$token=$_POST['token'];		
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){	
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{		
			$resusuario = $this->Cliente_model->login($nombre,$contrasena);
			if(count($resusuario)>0){
				$arrayRespuesta["status"] = "OK";
				$arrayRespuesta["respuesta"] = "Usuario correcto";
				$arrayRespuesta["nombre"] = $resusuario[0]->nombre;
			}else{

					$arrayRespuesta["status"] = "ERROR";
					$arrayRespuesta["respuesta"] = "Usuario o contraseña incorrecta";
				}
		}
		$myJSON = json_encode($arrayRespuesta);
		echo $myJSON;
	} 
}