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
	function Comunicados_marca_sucursal_idioma() {  
		$token=$_GET['token'];
		$id_sucursal=$_GET['id_unidad'];
		$id_marca=$_GET['id_marca'];
		$idioma=1;
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
			$response["registros"] = $this->Comunicado_model->get_marca_sucursal_idioma($id_sucursal,$id_marca,$idioma);
		}
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
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/menudigital/Comunicados/".$nuevo)) {
		        			
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
	function update_idioma() {  
		$response = array();
	    $token=$_GET['token'];
		$id = $_GET['id'];
		$id_idioma= (!isset($_GET['id_idioma'])) ? "0" : $_GET['id_idioma'];
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			if($id_idioma>0)
				$response["registro"] = $this->Comunicado_model->getByIdIdioma($id,$id_idioma);
			else
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
											
				if (move_uploaded_file($temporal, $_SERVER['DOCUMENT_ROOT']."/menudigital/Comunicados/".$nuevo)) {
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
			if ( isset( $_POST['id_idioma']) && $_POST['id_idioma'] >0 ) {
					$data ['id_idioma']= $_POST["id_idioma"];
					$data ['id_comunicado']= $_POST["id"];
					if($this->Comunicado_model->update_idioma($data,$_POST["id"],$_POST['id_idioma'])){
						$response["status"] = "OK";
						$response["respuesta"] = "Registro actualizado correctamente";
					}else{
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al actualizar la categoría";
					}
			}
			else{
				if($this->Comunicado_model->update($data,$_POST["id"])){
					$response["status"] = "OK";
					$response["respuesta"] = "Registro actualizado correctamente";
				}else{
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al actualizar el Registro";
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
		//$sucursal=$_GET['id_unidad'];		
		$region=$_GET['id_region'];		
		$response = array();
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			$PNG_TEMP_DIR = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."menudigital".DIRECTORY_SEPARATOR."QR".DIRECTORY_SEPARATOR;
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			include('qr/qrlib.php');
			$content = 'https://www.menugrg.com.mx:7443/toks/home/'.$region;
			//$content = 'https://www.menugrg.com.mx:7443/toks/home/'.$sucursal;
			//QRcode::png($content,$PNG_TEMP_DIR."unidad".$sucursal.".png",QR_ECLEVEL_L,10,2);
			QRcode::png($content,$PNG_TEMP_DIR."region".$region.".jpg",QR_ECLEVEL_L,10,2);
			$img ="region".$region.".jpg";
			//$contenidoBinario = file_get_contents($PNG_TEMP_DIR.$img);
			//$imagenComoBase64 = base64_encode($contenidoBinario);
			$response["qr"] ='/menudigital/QR/'.$img;
		}
		echo json_encode($response);	
	}
	public function QR_Combinaciones(){
		
		$token=$_GET['token'];			
		$region=$_GET['id_region'];		
		$response = array();
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			$nombre_region ='';
			$nombre_marca ='';
			$id_region ='';
			$alcohol ='';
			$nombre_alcohol ='';
			$individual ='';
			$kilo ='';
			$i =0;
			$archivo =array();
			$archivo_qr = array();
			$parametros = array();
			$unidades = $this->Comunicado_model->getCombinaciones($region);
				include('qr/qrlib.php');
				$PNG_TEMP_DIR = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."menudigital".DIRECTORY_SEPARATOR."QR".DIRECTORY_SEPARATOR;
			foreach($unidades as $unidad){
				$nombre_marca=$unidad->marca;
				$nombre_region=$unidad->nombre_region;
				$id_region=$unidad->id_region;
				$nombre_alcohol=$unidad->nombre_alcohol;
				$alcohol=$unidad->alcohol;
				$individual=$unidad->individual;
				$kilo=$unidad->kilos;
				$numero = rand();
				$archivo_qr ['archivo']='/menudigital/QR/'.$nombre_region.($alcohol ==1 ? $nombre_alcohol:'').($individual =='1' ?'_individual':'').($kilo ==1 ?'_kilo':'').'_'.$numero .".jpg";
				$parametros['alcohol'] =$alcohol;
				$parametros['individual'] =$individual;
				$parametros['kilo'] =$kilo;
				$parametros['id_region'] =$id_region;
				$archivo_qr ['parametros'] =$parametros;
				if(file_exists('/../../menudigital/QR/'.$nombre_region.($alcohol ==1 ? $nombre_alcohol:'').($individual =='1' ?'_individual':'').($kilo ==1 ?'_kilo':'').'_'.$numero.".jpg"))
					unlink('/../../menudigital/QR/'.$nombre_region.($alcohol ==1 ? $nombre_alcohol:'').($individual =='1' ?'_individual':'').($kilo ==1 ?'_kilo':'').'_'.$numero .".jpg");
				$response["status"] = "OK";
				$response["respuesta"] = "Ingreso correcto";
				$content = 'https://www.menugrg.com.mx:7443/'.$nombre_marca.'?region='.$id_region.'&alcohol='.$alcohol.'&individual='.$individual.'&kilo='.$kilo;
				QRcode::png($content,$PNG_TEMP_DIR.$nombre_region.($alcohol =='1' ?'_alcohol':'').($individual =='1' ?'_individual':'').($kilo =='1' ?'_kilo':'').'_'.$numero .".jpg",QR_ECLEVEL_L,10,2);
				$archivo[$i] =$archivo_qr;
				$i++;
			}
			
			$response["qr"] =$archivo;
		}
		echo json_encode($response);	
	}
	public function QR_Combinaciones_(){
		
		$token=$_GET['token'];			
		$region=$_GET['id_region'];		
		$response = array();
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			$nombre_region ='';
			$nombre_marca ='';
			$id_region ='';
			$alcohol ='';
			$nombre_alcohol ='';
			$individual ='';
			$kilo ='';
			$archivo ='';
			$unidades = $this->Comunicado_model->getCombinaciones($region);
				include('qr/qrlib.php');
				$PNG_TEMP_DIR = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."menudigital".DIRECTORY_SEPARATOR."QR".DIRECTORY_SEPARATOR;
			foreach($unidades as $unidad){
				$nombre_marca=$unidad->marca;
				$nombre_region=$unidad->nombre_region;
				$id_region=$unidad->id_region;
				$nombre_alcohol=$unidad->nombre_alcohol;
				$alcohol=$unidad->alcohol;
				$individual=$unidad->individual;
				$kilo=$unidad->kilos;
				$numero = rand();
				$archivo .='/menudigital/QR/'.$nombre_region.($alcohol ==1 ? $nombre_alcohol:'').($individual =='1' ?'_individual':'').($kilo ==1 ?'_kilo':'').'_'.$numero .".jpg|";
				if(file_exists('/../../menudigital/QR/'.$nombre_region.($alcohol ==1 ? $nombre_alcohol:'').($individual =='1' ?'_individual':'').($kilo ==1 ?'_kilo':'').'_'.$numero.".jpg"))
					unlink('/../../menudigital/QR/'.$nombre_region.($alcohol ==1 ? $nombre_alcohol:'').($individual =='1' ?'_individual':'').($kilo ==1 ?'_kilo':'').'_'.$numero .".jpg");
				$response["status"] = "OK";
				$response["respuesta"] = "Ingreso correcto";
				$content = 'https://www.menugrg.com.mx:7443/'.$nombre_marca.'?region='.$id_region.'&alcohol='.$alcohol.'&individual='.$individual.'&kilo='.$kilo;
				QRcode::png($content,$PNG_TEMP_DIR.$nombre_region.($alcohol =='1' ?'_alcohol':'').($individual =='1' ?'_individual':'').($kilo =='1' ?'_kilo':'').'_'.$numero .".jpg",QR_ECLEVEL_L,10,2);
			}
			
			$response["qr"] =$archivo;
		}
		echo json_encode($response);	
	}
}