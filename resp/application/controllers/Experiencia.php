
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Experiencia extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('Experiencia_model');
		$this->load->model('Idioma_model');
		$this->load->model('Marca_model');
	}
	function index() {  
        $_POST = json_decode(file_get_contents("php://input"),true);
		$token=$_GET['token'];
		$id_marca=$_GET['id_marca'];
							  
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
		$response["registros"] = $this->Experiencia_model->get_todas($id_marca);
		//$this->load->view('Experiencias',$response); 		
		echo json_encode($response);
    } 
    public function add_archivo(){
		$this->load->view('insert_Experiencia_'); 
	}
	 public function ruta(){
		print_r(substr(base_url(), 0, -1).$_SERVER["DOCUMENT_ROOT"].'menu_digital/pplication/Experiencias/');
		print_r($this->Experiencia_model->getById(1));
		//$this->load->view('insert_Experiencia',$response); 
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
			$response["marcas"] = $this->Marca_model->get();
		}
		echo json_encode($response);		
		//$this->load->view('insert_Experiencia',$response); 
	}
    public function add_db(){
		
	    //$_POST = json_decode(file_get_contents("php://input"),true);
		header('Access-Control-Allow-Origin','*');  
		
		$nombre = $_FILES['file']['name'];
		list($base,$extension) = explode('.',$nombre);
		$nuevo = implode('.', [$base, time(), $extension]);
		
		$nombre_popup = $_FILES['imagen_popup']['name'];
		list($base,$extension) = explode('.',$nombre_popup);
		$nuevo_popup = implode('.', [$base, time(), $extension]);
		
		$response = array();    
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/menudigital/Experiencias/".$nuevo)) {
			if (move_uploaded_file($_FILES["imagen_popup"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/menudigital/Experiencias/".$nuevo_popup)) {
		
				$token=$_POST['token'];	
				$res = $this->login_model->buscar_token($token);
				$id_marca = $_POST["id_marca"];
				$data = array(
					'nombre' => $_POST["nombre"],
					'descripcion' => $_POST["descripcion"],
					// 'ubicacion' => "https://pruebasgerard.com/menudigital/Experiencias/".$nuevo,
					'ubicacion' => "/menudigital/Experiencias/".$nuevo,
					'imagen_popup' => "/menudigital/Experiencias/".$nuevo_popup,
					'imagen' => $nuevo,
					'activo' => 1
				);
				
				if(count($res) == 0){
					$response["status"] = "Error";
					$response["respuesta"] = "Error al ingresar";		
				}
				else{	
					if($this->Experiencia_model->add($data,$id_marca)){
						$response["status"] = "OK";
						$response["respuesta"] = "Experiencia registrada correctamente";
					}else{
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al registrar la marca";
					}
				}
			}else{
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al cargar archivo";
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
		$id_idioma= (!isset($_GET['id_idioma'])) ? "0" : $_GET['id_idioma'];
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
		    $response["idiomas"] = $this->Idioma_model->get_activos();
			if($id_idioma>0){
				$response["registro"] = $this->Experiencia_model->getByIdIdioma($id,$id_idioma);
			}
			else{
				$response["registro"] = $this->Experiencia_model->getById($id);
			}
		}
		echo json_encode($response);	
		//$this->load->view('update_Experiencia',$response); 
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
		$nombre_popup ="";
		$nuevo_popup ="";
		$temporal_popup = "";
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
		//imagen popup
		if(!isset($_POST['imagen_popup'])){
		    $nombre_popup = $_FILES['imagen_popup']['name'];
    		list($base,$extension) = explode('.',$nombre_popup);
    		$nuevo_popup = implode('.', [$base, time(), $extension]);
    		$temporal_popup = $_FILES["imagen_popup"]["tmp_name"];
		}
		else{
		    $nombre_popup ="";
		    $temporal_popup = "";
		}
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
				$response["status"] = "Error";
				$response["respuesta"] = "Error al ingresar";		
			}
		else{
			if($nuevo!="")
			{	
				// $data['ubicacion'] =  "https://pruebasgerard.com/menudigital/Experiencias/".$nuevo;
				$data['ubicacion'] =  "/menudigital/Experiencias/".$nuevo;
				$data['imagen'] = $nuevo;
				if (move_uploaded_file($temporal, $_SERVER['DOCUMENT_ROOT']."/menudigital/Experiencias/".$nuevo)) {
					$envio_imagen = true;
				}else
				{
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al cargar archivo";
				}
			}	
			if($nuevo_popup!="")
			{	
				// $data['ubicacion'] =  "https://pruebasgerard.com/menudigital/Experiencias/".$nuevo;
				$data['imagen_popup'] =  "/menudigital/Experiencias/".$nuevo_popup;
				if (move_uploaded_file($temporal_popup, $_SERVER['DOCUMENT_ROOT']."/menudigital/Experiencias/".$nuevo_popup)) {
					$envio_imagen = true;
				}else
				{
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al cargar archivo";
				}
			}				
			$data ['nombre']=$_POST["nombre"];
			$data ['activo']= $_POST["activo"];	
			$data ['descripcion']= $_POST["descripcion"];					
			if ( isset( $_POST['id_idioma']) && $_POST['id_idioma'] >0 ) {
					$data ['id_idioma']= $_POST["id_idioma"];
					$data ['id_experiencia']= $_POST["id"];
					if($this->Experiencia_model->update_idioma($data,$_POST["id"],$_POST['id_idioma'])){
						$response["status"] = "OK";
						$response["respuesta"] = "Categoría actualizada correctamente";
					}else{
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al actualizar la categoría";
					}
			}
			else{
				if($this->Experiencia_model->update($data,$_POST["id"])){
					$response["status"] = "OK";
					$response["respuesta"] = "Experiencia actualizada correctamente";
				}else{
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al actualizar la marca";
				}
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
			if($this->Experiencia_model->eliminar($id)){
				$response["status"] = "OK";
				$response["respuesta"] = "Experiencia eliminada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al eliminar la marca";
			}
		}
        echo json_encode($response);
	}
	function experiencia_marca() {  
		$token=$_GET['token'];
		$id_marca=$_GET['id_marca'];
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
		    $response["registros"] = $this->Experiencia_model->experiencia_marca($id_marca);
		}
		//$this->load->view('Experiencias',$response); 		
		echo json_encode($response);
    } 
}