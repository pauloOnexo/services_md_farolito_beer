<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Comunicado extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('Comunicado_model');
		$this->load->model('Marca_model');
	}
	function index() {  
        //$_POST = json_decode(file_get_contents("php://input"),true);
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
		$response["registros"] = $this->Comunicado_model->get($id_marca);
		//$this->load->view('Comunicados',$response); 		
		echo json_encode($response);
    } 
	 function Comunicados_marca_sucursal() {  
		$token=$_GET['token'];
		$id_sucursal=$_GET['id_unidad'];
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
		$response["registros"] = $this->Comunicado_model->get_marca_sucursal($id_sucursal,$id_marca);
		//$this->load->view('Comunicados',$response); 		
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
			$response["marcas"] = $this->Marca_model->get();
		}
		echo json_encode($response);		
		//$this->load->view('insert_Comunicado',$response); 
	}
    public function add_db(){
		
	    //$_POST = json_decode(file_get_contents("php://input"),true);
		header('Access-Control-Allow-Origin','*');  
		$nombre = $_FILES['file']['name'];
		list($base,$extension) = explode('.',$nombre);
		$nuevo = implode('.', [$base, time(), $extension]);
		$nuevo = trim ($nuevo);
		
		
		$response = array();    
		if (move_uploaded_file($_FILES["file"]["tmp_name"], getenv('DIR_FILES')."/menudigital/Comunicados/".$nuevo)) {
		        			
			$token=$_POST['token'];	
			$res = $this->login_model->buscar_token($token);
			$data = array(
				'id_local' => $_POST["id_local"],
				'id_marca' => $_POST["id_marca"],
				'ids_regiones' => $_POST["ids_regiones"],
				// 'imagen' => "https://pruebasgerard.com/menudigital/Comunicados/".$nuevo,
				'imagen' => "/menudigital/Comunicados/".$nuevo,
				'activo' => 1,
				'fecha_inicio' => $_POST["fecha_inicio"],
				'fecha_fin' => $_POST["fecha_fin"],
				'tipo_comunicado' => $_POST["tipo_comunicado"],
				'link' => $_POST["link"],
			);
			
			if(count($res) == 0){
				$response["status"] = "Error";
				$response["respuesta"] = "Error al ingresar";		
			}
			else{	
				if($this->Comunicado_model->add($data)){
					$response["status"] = "OK";
					$response["respuesta"] = "Registro registrado correctamente";
				}else{
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al registrar el registro";
				}
			}
	
		}else{
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al cargar archivo";
				}
        echo json_encode($response);
	}
	function update() {  
		$response = array();
	    $token=$_GET['token'];
		$id = $_GET['id'];
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
		    $response["registro"] = $this->Comunicado_model->getById($id);
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
		}
		echo json_encode($response);	
	//	$this->load->view('update_Comunicado',$response); 
    } 
public function update_db(){
		//$_POST = json_decode(file_get_contents("php://input"),true);
		header('Access-Control-Allow-Origin','*');  	
		$token=$_POST['token'];			
		$response = array();
		$data = array();	
		$envio_imagen = false;
		//$activo = $_POST["activo"];
		$nombre ="";
		$nuevo ="";
		$temporal = "";
		
		
		$envio_imagen_titulo = false;
		$nombre_titulo ="";
		$nuevo_titulo ="";
		$temporal_titulo = "";
		
		if(!isset($_POST['file'])){
    		$nombre = $_FILES['file']['name'];
    		list($base,$extension) = explode('.',$nombre);
    		$nuevo = implode('.', [$base, time(), $extension]);
    		$nuevo = trim($nuevo);
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
			if($nombre!="")
			{	
				// $data['ubicacion'] =  "https://pruebasgerard.com/menudigital/Comunicados/".$nuevo;
				$data['imagen'] =  "/menudigital/Comunicados/".$nuevo;
											
				if (move_uploaded_file($temporal, getenv('DIR_FILES')."/menudigital/Comunicados/".$nuevo)) {
					$envio_imagen = true;
				}else
				{
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al cargar archivo";
				}
			}			
					
			$data ['id_marca']=$_POST["id_marca"];
			$data ['id_local']=$_POST["id_local"];
			$data ['ids_regiones']=$_POST["ids_regiones"];
			$data ['fecha_inicio']=$_POST["fecha_inicio"];
			$data ['fecha_fin']=$_POST["fecha_fin"];
			$data ['tipo_comunicado']=$_POST["tipo_comunicado"];
			$data ['link']=$_POST["link"];
			//$data ['activo']= $activo;	
			if($this->Comunicado_model->update($data,$_POST["id"])){
				$response["status"] = "OK";
				$response["respuesta"] = "Registro actualizado correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al actualizar el Registro";
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
			if($this->Comunicado_model->eliminar($id)){
				$response["status"] = "OK";
			$response["respuesta"] = "Registro eliminado correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al eliminar el registro";
			}
		}
        echo json_encode($response);
	}
	public function QR(){
		
		$token=$_GET['token'];		
		$sucursal=$_GET['id_unidad'];		
		$response = array();
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			include('qr/qrlib.php');
			$content = 'https://www.menugrg.com.mx:7443/toks/home/'.$sucursal;
			QRcode::png($content,"unidad".$content.".png",QR_ECLEVEL_L,10,2);
			$img = "unidad".$content.".png";
			$contenidoBinario = file_get_contents($img);
			$imagenComoBase64 = base64_encode($contenidoBinario);
			$response["qr"] =$imagenComoBase64;
		}
		echo json_encode($response);	
	}
}