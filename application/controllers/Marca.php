<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Marca extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
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
		}
		$response["registros"] = $this->Marca_model->get();
		//$this->load->view('Marcas',$response); 		
		echo json_encode($response);
    } 
    public function add_archivo(){
		$this->load->view('insert_Marca_'); 
	}
	public function add_db_(){
	    /*if (move_uploaded_file($_FILES["file"]["tmp_name"], getenv('DIR_FILES')."/menudigital/Marcas/".$_FILES['file']['name']))
	    echo 'correcto';
	    else 'error';
	    print_r (getenv('DIR_FILES')."/menudigital/Marcas/");*/
	    if($_FILES['file']['name'])
	     echo '1';
	    else echo '2';
	    
	}
	 public function ruta(){
		print_r(substr(base_url(), 0, -1).$_SERVER["DOCUMENT_ROOT"].'menu_digital/pplication/Marcas/');
		print_r($this->Marca_model->getById(1));
		//$this->load->view('insert_Marca',$response); 
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
		//$this->load->view('insert_Marca',$response); 
	}
    public function add_db(){
		
	    //$_POST = json_decode(file_get_contents("php://input"),true);
		header('Access-Control-Allow-Origin','*');  
		
		$nombre = $_FILES['file']['name'];
		list($base,$extension) = explode('.',$nombre);
		$nuevo = implode('.', [$base, time(), $extension]);
		
		$response = array();    
		if (move_uploaded_file($_FILES["file"]["tmp_name"], getenv('DIR_FILES')."/menudigital/Marcas/".$nuevo)) {
			
			$token=$_POST['token'];	
			$res = $this->login_model->buscar_token($token);
			$data = array(
				'nombre' => $_POST["nombre"],
				// 'ubicacion' => "https://pruebasgerard.com/menudigital/Marcas/".$nuevo,
				'ubicacion' => "/menudigital/Marcas/".$nuevo,
				'imagen' => $nuevo,
				'activo' => 1
			);
			
			if(count($res) == 0){
				$response["status"] = "Error";
				$response["respuesta"] = "Error al ingresar";		
			}
			else{	
				if($this->Marca_model->add($data)){
					$response["status"] = "OK";
					$response["respuesta"] = "Marca registrada correctamente";
				}else{
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al registrar la marca";
				}
			}
		}else{
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al cargar archivo";
				}
        echo json_encode($response);
	}
	function update() {  
	    $token=$_GET['token'];
		$id = $_GET['id'];
		$response = array();
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
		    $response["registro"] = $this->Marca_model->getById($id);
		}
		echo json_encode($response);	
		//$this->load->view('update_Marca',$response); 
    } 
public function update_db(){
		//$_POST = json_decode(file_get_contents("php://input"),true);
		header('Access-Control-Allow-Origin','*');  	
		$token=$_POST['token'];			
		$response = array();
		$data = array();	
		$envio_imagen = false;
		
		$nombre ="";
		$nuevo ="";
		$temporal = "";
		if(!isset($_POST['file'])){
		    $nombre = $_FILES['file']['name'];
    		list($base,$extension) = explode('.',$nombre);
    		$nuevo = implode('.', [$base, time(), $extension]);
    		$temporal = $_FILES["file"]["tmp_name"];
		}
		else{
		    $nombre ="";
		    $temporal = "";
		}
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
				$response["status"] = "Error";
				$response["respuesta"] = "Error al ingresar";		
			}
		else{
			if($nuevo!="")
			{	
				// $data['ubicacion'] =  "https://pruebasgerard.com/menudigital/Marcas/".$nuevo;
				$data['ubicacion'] =  "/menudigital/Marcas/".$nuevo;
				$data['imagen'] = $nuevo;
				if (move_uploaded_file($temporal, getenv('DIR_FILES')."/menudigital/Marcas/".$nuevo)) {
					$envio_imagen = true;
				}else
				{
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al cargar archivo";
				}
			}			
			$data ['nombre']=$_POST["nombre"];
			$data ['activo']= $_POST["activo"];					
			
			if($this->Marca_model->update($data,$_POST["id"])){
				$response["status"] = "OK";
				$response["respuesta"] = "Marca actualizada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al actualizar la marca";
			}
		}				
        echo json_encode($response);
	}
	public function delete_(){
		$this->load->view('delete_marca');
       
	}
	public function delete(){
		//header('Access-Control-Allow-Origin','*');  	
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
			if($this->Marca_model->eliminar($id)){
				$response["status"] = "OK";
				$response["respuesta"] = "Marca eliminada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al eliminar la marca";
			}
		}
        echo json_encode($response);
	}
}