<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Categoria extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('Categoria_model');
		$this->load->model('Idioma_model');
		$this->load->model('Marca_model');
	}
	function index()
	{
		//$_POST = json_decode(file_get_contents("php://input"),true);
		$token = $_GET['token'];
		$id_marca = $_GET['id_marca'];
		$response = array();
		//verificar token
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
		}
		$response["registros"] = $this->Categoria_model->get($id_marca);
		//$this->load->view('Categorias',$response); 		
		echo json_encode($response);
	}
	function categorias_sucursal()
	{
		$token = $_GET['token'];
		$id_sucursal = $_GET['id_sucursal'];
		$id_marca = $_GET['id_marca'];
		$response = array();
		//verificar token
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
		}
		$response["registros"] = $this->Categoria_model->get_activas($id_sucursal, $id_marca);
		//$this->load->view('Categorias',$response); 		
		echo json_encode($response);
	}

	public function add()
	{
		$token = $_GET['token'];
		$response = array();
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["marcas"] = $this->Marca_model->get();
		}
		echo json_encode($response);
		//$this->load->view('insert_categoria',$response); 
	}
	public function add_db()
	{

		//$_POST = json_decode(file_get_contents("php://input"),true);
		header('Access-Control-Allow-Origin', '*');
		$nombre = $_FILES['file']['name'];
		list($base, $extension) = explode('.', $nombre);
		$nuevo = implode('.', [$base, time(), $extension]);
		$nuevo = trim($nuevo);

		$nombre_titulo = $_FILES['imagen_titulo_categoria']['name'];
		list($base, $extension) = explode('.', $nombre_titulo);
		$nuevo_titulo = implode('.', [$base, time(), $extension]);
		$nuevo_titulo = trim($nuevo_titulo);

		$response = array();

		$envio_imagen_fondo = false;
		$nombre_fondo = "";
		$nuevo_fondo = "";
		$temporal_fondo = "";
		$ruta_imagen_titulo    = "";
		if (!isset($_POST['imagen_fondo'])) {
			$envio_imagen_fondo = true;
			$nombre_fondo = $_FILES['imagen_fondo']['name'];
			list($base, $extension) = explode('.', $nombre_fondo);
			$nuevo_fondo = implode('.', [$base, time(), $extension]);
			$nuevo_fondo = trim($nuevo_fondo);
			$temporal_fondo = $_FILES["imagen_fondo"]["tmp_name"];
		} else {
			$nombre_fondo = "";
			$temporal_fondo = "";
		}

		if (move_uploaded_file($_FILES["file"]["tmp_name"], getenv('DIR_FILES') . "/menudigital/categorias/" . $nuevo)) {

			if (move_uploaded_file($_FILES["imagen_titulo_categoria"]["tmp_name"], getenv('DIR_FILES') . "/menudigital/categorias/" . $nuevo_titulo)) {

				if ($envio_imagen_fondo) {
					if (move_uploaded_file($temporal_fondo, getenv('DIR_FILES') . "/menudigital/categorias/" . $nuevo_fondo)) {
						$ruta_imagen_titulo = "/menudigital/categorias/" . $nuevo_fondo;
					} else {
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al cargar archivo";
					}
				}
				$token = $_POST['token'];
				$res = $this->login_model->buscar_token($token);
				$data = array(
					'nombre' => $_POST["nombre"],
					'id_marca' => $_POST["id_marca"],
					'descripcion' => $_POST["descripcion"],
					// 'ubicacion' => "https://pruebasgerard.com/menudigital/categorias/".$nuevo,
					'ubicacion' => "/menudigital/categorias/" . $nuevo,
					'imagen' => $nuevo,
					'activo' => 1,
					//'imagen_titulo_categoria' => "https://pruebasgerard.com/menudigital/categorias/".$nuevo_titulo,
					'imagen_titulo_categoria' => "/menudigital/categorias/" . $nuevo_titulo,
					'imagen_fondo' => $ruta_imagen_titulo,
					'nombre_imagen_titulo_categoria' => $nuevo_titulo
				);

				if (count($res) == 0) {
					$response["status"] = "Error";
					$response["respuesta"] = "Error al ingresar";
				} else {
					if ($this->Categoria_model->add($data)) {
						$response["status"] = "OK";
						$response["respuesta"] = "Sucursal registrada correctamente";
					} else {
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al registrar la sucursl";
					}
				}
			} else {
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al cargar archivo";
			}
		} else {
			$response["status"] = "ERROR";
			$response["respuesta"] = "Error al cargar archivo";
		}
		echo json_encode($response);
	}
	function update()
	{
		$response = array();
		$token = $_GET['token'];
		$id = $_GET['id'];
		$id_idioma = $_GET['id_idioma'];
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			if ($id_idioma > 0) {
				$response["registro"] = $this->Categoria_model->getByIdIdioma($id, $id_idioma);
			} else {
				$response["registro"] = $this->Categoria_model->getById($id);
			}
			$response["idiomas"] = $this->Idioma_model->get_activos();
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
		}
		echo json_encode($response);
		//	$this->load->view('update_categoria',$response); 
	}
	function update_idioma()
	{
		$response = array();
		$token = $_GET['token'];
		$id = $_GET['id'];
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["registro"] = $this->Categoria_model->getByIdIdioma($id, $id_idioma);
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
		}
		echo json_encode($response);
		//	$this->load->view('update_categoria',$response); 
	}
	public function update_db()
	{
		//$_POST = json_decode(file_get_contents("php://input"),true);
		header('Access-Control-Allow-Origin', '*');
		$token = $_POST['token'];
		$response = array();
		$data = array();
		$envio_imagen = false;
		$activo = $_POST["activo"];
		$nombre = "";
		$nuevo = "";
		$temporal = "";

		$envio_imagen_titulo = false;
		$nombre_titulo = "";
		$nuevo_titulo = "";
		$temporal_titulo = "";

		$envio_imagen_fondo = false;
		$nombre_fondo = "";
		$nuevo_fondo = "";
		$temporal_fondo = "";

		if (!isset($_POST['file'])) {
			$nombre = $_FILES['file']['name'];
			list($base, $extension) = explode('.', $nombre);
			$nuevo = implode('.', [$base, time(), $extension]);
			$nuevo = trim($nuevo);
			$temporal = $_FILES["file"]["tmp_name"];
		} else {
			$nombre = "";
			$temporal = "";
		}

		if (!isset($_POST['imagen_titulo_categoria'])) {
			$envio_imagen_titulo = true;
			$nombre_titulo = $_FILES['imagen_titulo_categoria']['name'];
			list($base, $extension) = explode('.', $nombre_titulo);
			$nuevo_titulo = implode('.', [$base, time(), $extension]);
			$nuevo_titulo = trim($nuevo_titulo);
			$temporal_titulo = $_FILES["imagen_titulo_categoria"]["tmp_name"];
		} else {
			$nombre_titulo = "";
			$temporal_titulo = "";
		}
		if (!isset($_POST['imagen_fondo'])) {
			$envio_imagen_fondo = true;
			$nombre_fondo = $_FILES['imagen_fondo']['name'];
			list($base, $extension) = explode('.', $nombre_fondo);
			$nuevo_fondo = implode('.', [$base, time(), $extension]);
			$nuevo_fondo = trim($nuevo_fondo);
			$temporal_fondo = $_FILES["imagen_fondo"]["tmp_name"];
		} else {
			$nombre_fondo = "";
			$temporal_fondo = "";
		}

		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			if ($nombre != "") {
				$data['ubicacion'] =  "/menudigital/categorias/" . $nuevo;
				$data['imagen'] = $nuevo;
				if (move_uploaded_file($temporal, getenv('DIR_FILES') . "/menudigital/categorias/" . $nuevo)) {
					$envio_imagen = true;
				} else {
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al cargar archivo";
				}
			}
			if ($nombre_titulo != "") {
				$data['imagen_titulo_categoria'] =  "/menudigital/categorias/" . $nuevo_titulo;
				$data['nombre_imagen_titulo_categoria'] = $nuevo_titulo;
				if (move_uploaded_file($temporal_titulo, getenv('DIR_FILES') . "/menudigital/categorias/" . $nuevo_titulo)) {
					$envio_imagen_titulo = true;
				} else {
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al cargar archivo";
				}
			}
			if ($envio_imagen_fondo) {
				$data['imagen_fondo'] =  "/menudigital/categorias/" . $nuevo_fondo;
				if (move_uploaded_file($temporal_fondo, getenv('DIR_FILES') . "/menudigital/categorias/" . $nuevo_fondo)) {
					$envio_imagen_titulo = true;
				} else {
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al cargar archivo";
				}
			}
			$data['nombre'] = $_POST["nombre"];
			$data['descripcion'] = $_POST["descripcion"];
			$data['id_marca'] = $_POST["id_marca"];
			$data['activo'] = $activo;
			if (isset($_POST['id_idioma']) && $_POST['id_idioma'] > 0) {
				$data['id_idioma'] = $_POST["id_idioma"];
				$data['id_categoria'] = $_POST["id"];
				$resid = $this->Categoria_model->update_idioma($data, $_POST["id"], $_POST['id_idioma']);
				//if(){
				//$response["id_categoria_idioma"] = $resid;
				$response["status"] = "OK";
				$response["respuesta"] = "Categoría actualizada correctamente";
				/*}else{
						$response["status"] = "ERROR";
						$response["respuesta"] = "Error al actualizar la categoría";
					}*/
			} else {
				if ($this->Categoria_model->update($data, $_POST["id"])) {
					$response["status"] = "OK";
					$response["respuesta"] = "Categoría actualizada correctamente";
				} else {
					$response["status"] = "ERROR";
					$response["respuesta"] = "Error al actualizar la categoría";
				}
			}
		}
		echo json_encode($response);
	}
	public function update_db2()
	{
		$data = array();
		/*$data ['nombre']=$_POST["nombre"];
			$data ['descripcion']=$_POST["descripcion"];*/
		$data['activo'] = 1;
		$this->Categoria_model->update($data, 2);
	}
	public function delete_()
	{
		$this->load->view('delete_marca');
	}
	public function delete()
	{
		//header('Access-Control-Allow-Origin','*');  	
		$_POST = json_decode(file_get_contents("php://input"), true);
		$token = $_POST['token'];
		$id = $_POST["id"];

		$response = array();
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			if ($this->Categoria_model->eliminar($id)) {
				$response["status"] = "OK";
				$response["respuesta"] = "Marca eliminada correctamente";
			} else {
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al eliminar la marca";
			}
		}
		echo json_encode($response);
	}
	function categoria_marca()
	{
		$token = $_GET['token'];
		$id_marca = $_GET['id_marca'];
		$response = array();
		//verificar token
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["registros"] = $this->Categoria_model->experiencia_marca($id_marca);
		}
		//$this->load->view('Experiencias',$response); 		
		echo json_encode($response);
	}
	function categoria_subcategorias_marca()
	{

		$token = $_GET['token'];
		$id_marca = $_GET['id_marca'];
		$response = array();
		//verificar token
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["categorias"] = $this->Categoria_model->get($id_marca);
		}
		//$this->load->view('Experiencias',$response); 		
		echo json_encode($response);
	}
}
