<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Subcategoria extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('Subcategoria_model');
		$this->load->model('Categoria_model');
		$this->load->model('Idioma_model');
		$this->load->model('Region_model');
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
		$response["registros"] = $this->Subcategoria_model->get($id_marca);
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
			$response["categorias"] = $this->Categoria_model->get($id_marca);
			$response["regiones"] = $this->Region_model->get($id_marca);
		}
		echo json_encode($response);			
		//$this->load->view('insert_region',$response); 
	}
	public function add_db(){
        //$_POST = json_decode(file_get_contents("php://input"),true);
		$token=$_POST['token'];	
		$res = $this->login_model->buscar_token($token);
		$color_pleca = '';
		if ( isset( $_POST['color_pleca'] ) ) {
            $color_pleca = $_POST['color_pleca'] ;
        } else {
            $color_pleca = '';
        }
		$data = array(
			'nombre' => $_POST["nombre"],
			'descripcion' => $_POST["descripcion"],
			'categorias' => $_POST["categorias"],
			'destacado' => $_POST["destacado"],
			'activo' => 1,
		    'regiones' => $_POST["regiones"]!=''?$_POST["regiones"]:'0',
		    'color_pleca' => $color_pleca
        );
		
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{	
			if($this->Subcategoria_model->add($data)){
				$response["status"] = "OK";
				$response["respuesta"] = "Subcategoria registrada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al registrar la region";
			}
		}
        echo json_encode($response);
	}
	function update() {  
		$id = $_GET['id'];
		$id_marca=$_GET['id_marca'];
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
				$response["registro"] = $this->Subcategoria_model->getByIdIdioma($id,$id_idioma);
			}
			else{
				$response["registro"] = $this->Subcategoria_model->getById($id);
			}
		    $response["idiomas"] = $this->Idioma_model->get_activos();
			$response["categorias"] = $this->Categoria_model->get($id_marca);
			$response["regiones"] = $this->Region_model->get($id_marca);
		}
		echo json_encode($response);	
		//$this->load->view('update_region',$response); 
    } 
	public function update_db(){
		
        //$_POST = json_decode(file_get_contents("php://input"),true);
		$token=$_POST['token'];	
		$color_pleca = '';
		if ( isset( $_POST['color_pleca'] ) ) {
            $color_pleca = $_POST['color_pleca'] ;
        } else {
            $color_pleca = '';
        }
		$response = array();
		$data = array(
			'nombre' => $_POST["nombre"],
			'descripcion' => $_POST["descripcion"],
			'categorias' => $_POST["categorias"],
			'activo' => $_POST["activo"],
			'destacado' => $_POST["destacado"],
			'regiones' => $_POST["regiones"]!=''?$_POST["regiones"]:'0',
			'color_pleca' => $color_pleca
        );
		$res = $this->login_model->buscar_token($token);
		if(count($res) == 0){
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";		
		}
		else{
			if ( isset( $_POST['id_idioma']) && $_POST['id_idioma'] >0 ) {
					$data ['id_idioma']= $_POST["id_idioma"];
					$data ['id_subcategoria']= $_POST["id"];
					if($this->Subcategoria_model->update_idioma($data,$_POST["id"],$_POST['id_idioma'])){
						$response["status"] = "OK";
						$response["respuesta"] = "Categoría actualizada correctamente";
					}else{
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al actualizar la categoría";
					}
			}
			else{
				if($this->Subcategoria_model->update($data,$_POST["id"])){
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
			if($this->Subcategoria_model->eliminar($id)){
				$response["status"] = "OK";
				$response["respuesta"] = "Subcategoria eliminada correctamente";
			}else{
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al eliminar la region";
			}
		}
        echo json_encode($response);
	}
}