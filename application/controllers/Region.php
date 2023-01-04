<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Region extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('Region_model');
		$this->load->model('Marca_model');
	}
	function index()
	{
		$_POST = json_decode(file_get_contents("php://input"), true);
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
			$response["registros"] = $this->Region_model->get($id_marca);
		}
		$response["registros"] = $this->Region_model->get($id_marca);
		//$this->load->view('Regiones',$response); 
		echo json_encode($response);
	}

	public function add()
	{

		$token = $_GET['token'];
		$id_marca = $_GET['id_marca'];
		$response = array();
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["marcas"] = $this->Marca_model->get_activas($id_marca);
		}
		echo json_encode($response);
	}
	public function add_db()
	{
		$_POST = json_decode(file_get_contents("php://input"), true);
		$token = $_POST['token'];
		$res = $this->login_model->buscar_token($token);
		$data = array(
			'nombre' => $_POST["nombre"],
			'descripcion' => $_POST["descripcion"],
			'id_marca' => $_POST["id_marca"],
			'activo' => 1
		);

		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			if ($this->Region_model->add($data)) {
				$response["status"] = "OK";
				$response["respuesta"] = "Region registrada correctamente";
			} else {
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al registrar la region";
			}
		}
		echo json_encode($response);
	}
	function update()
	{
		$id = $_GET['id'];
		$id_marca = $_GET['id_marca'];
		$response = array();
		$token = $_GET['token'];
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["registro"] = $this->Region_model->getById($id);
			$response["marcas"] = $this->Marca_model->get();
		}
		echo json_encode($response);
		//$this->load->view('update_region',$response); 
	}
	public function update_db()
	{

		$_POST = json_decode(file_get_contents("php://input"), true);
		$token = $_POST['token'];

		$response = array();
		$data = array(
			'nombre' => $_POST["nombre"],
			'descripcion' => $_POST["descripcion"],
			'activo' => $_POST["activo"],
			'id' => $_POST["id"],
			'id_marca' => $_POST["id_marca"],
		);
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			if ($this->Region_model->update($data, $_POST["id"])) {
				$response["status"] = "OK";
				$response["respuesta"] = "Sucursal actualizada correctamente";
			} else {
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al actualizar la sucursl";
			}
		}
		echo json_encode($response);
	}
	public function delete()
	{


		$token = $_POST['token'];
		$id = $_POST["id"];

		$response = array();
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			if ($this->Region_model->eliminar($id)) {
				$response["status"] = "OK";
				$response["respuesta"] = "Region eliminada correctamente";
			} else {
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al eliminar la region";
			}
		}
		echo json_encode($response);
	}
}
