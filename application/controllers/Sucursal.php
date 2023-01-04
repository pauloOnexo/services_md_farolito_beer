<?php
// defined('BASEPATH') OR exit('No direct script access allowed');
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Sucursal extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Sucursal_model');
		$this->load->model('login_model');
		$this->load->model('Marca_model');
		$this->load->model('Region_model');

	// PRUEBAS 04-08-22
		// $this->load->helper('gettoken');

	}
	function index2()
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
			$response["registros"] = $this->Sucursal_model->get($id_marca);
			$data = array(
				'registro' => date("Y-m-d H:i:s")
			);
			$this->Sucursal_model->addSolicitud($data);
		}
		//$this->load->view('Sucursales',$response); 
		echo json_encode($response);
	}
	function index()
	{

		//$_POST = json_decode(file_get_contents("php://input"),true);
		$token = $_GET['token'];
		$id_marca = $_GET['id_marca'];
		$latitud = '';
		$longitud = '';
		if (isset($_GET['latitud'])) {
			$latitud = $_GET['latitud'];
		}
		if (isset($_GET['longitud'])) {
			$longitud = $_GET['longitud'];
		}
		$response = array();
		//verificar token
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["registros"] = $this->Sucursal_model->get2($id_marca, $latitud, $longitud);
			$data = array(
				'registro' => date("Y-m-d H:i:s")
			);
			//$this->Sucursal_model->addSolicitud($data);
		}
		//$this->load->view('Sucursales',$response); 
		echo json_encode($response);
	}
	function sucursales_estados()
	{
		$token = $_GET['token'];
		$id_marca = $_GET['id_marca'];
		$latitud = '';
		$longitud = '';
		if (isset($_GET['latitud'])) {
			$latitud = $_GET['latitud'];
		}
		if (isset($_GET['longitud'])) {
			$longitud = $_GET['longitud'];
		}
		$response = array();
		//verificar token
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["registros"] = $this->Sucursal_model->sucursales_estados($id_marca, $latitud, $longitud);
			$data = array(
				'registro' => date("Y-m-d H:i:s")
			);
			//$this->Sucursal_model->addSolicitud($data);
		}
		//$this->load->view('Sucursales',$response); 
		echo json_encode($response);
	}

	public function add()
	{
		$response = array();
		$id_marca = $_GET['id_marca'];
		$token = $_GET['token'];
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["marcas"] = $this->Marca_model->get();
			$response["regiones"] = $this->Region_model->get_activas($id_marca);
			$response["estados"] = $this->Sucursal_model->get_estados();
			$response["alcohol"] = $this->Sucursal_model->get_alcohol();
		}
		echo json_encode($response);
		//$this->load->view('insert_sucursal',$response); 
	}
	public function add_db()
	{
		$response = array();
		//$_POST = json_decode(file_get_contents("php://input"),true);
		$token = $_POST['token'];
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$data = array(
				'nombre' => $_POST["nombre"],
				'activo' => 1,
				'latitud' => $_POST["latitud"],
				'longitud' => $_POST["longitud"],
				'id_marca' => $_POST["id_marca"],
				'id_region' => $_POST["id_region"],
				'id_estado' => $_POST["id_estado"],
				'alcohol' => $_POST["alcohol"]
				//'kilos' => $_POST["kilos"]
			);

			//print_r( $data);
			if ($this->Sucursal_model->add($data)) {
				$response["status"] = "OK";
				$response["respuesta"] = "Sucursal registrada correctamente";
			} else {
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al registrar la sucursal";
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
			$response["marcas"] = $this->Marca_model->get();
			$response["regiones"] = $this->Region_model->get_activas($id_marca);
			$response["registro"] = $this->Sucursal_model->getById($id);
			$response["estados"] = $this->Sucursal_model->get_estados();
			$response["alcohol"] = $this->Sucursal_model->get_alcohol();
		}

		echo json_encode($response);
		//$this->load->view('update_sucursal',$response); 
	}
	public function update_db()
	{
		//$_POST = json_decode(file_get_contents("php://input"),true);

		$token = $_POST['token'];
		//print_r($_POST);
		$response = array();
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$data = array(
				'nombre' => $_POST["nombre"],
				'activo' => $_POST["activo"],
				'latitud' => $_POST["latitud"],
				'longitud' => $_POST["longitud"],
				'id_marca' => $_POST["id_marca"],
				'id_region' => $_POST["id_region"],
				'id_estado' => $_POST["id_estado"],
				'alcohol' => $_POST["alcohol"]
				//'kilos' => $_POST["kilos"]
			);

			if ($this->Sucursal_model->update($data, $_POST["id"])) {
				$response["status"] = "OK";
				$response["respuesta"] = "Sucursal actualizada correctamente";
			} else {
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al actualizar la sucursal";
			}
		}
		echo json_encode($response);
	}
	function update_()
	{
		$response = array();
		$response["registro"] = $this->Sucursal_model->getById(1);

		$this->load->view('update_sucursal', $response);
	}
	public function delete()
	{

		//$_POST = json_decode(file_get_contents("php://input"),true);
		$token = $_POST['token'];
		$id = $_POST["id"];

		$response = array();
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			if ($this->Sucursal_model->eliminar($id)) {
				$response["status"] = "OK";
				$response["respuesta"] = "Sucursal eliminada correctamente";
			} else {
				$response["status"] = "ERROR";
				$response["respuesta"] = "Error al eliminar la sucursal";
			}
		}
		echo json_encode($response);
	}
	function sucursales_estados_todos()
	{

		$token = $_GET['token'];
		$id_marca = $_GET['id_marca'];
		$latitud = '';
		$longitud = '';
		if (isset($_GET['latitud'])) {
			$latitud = $_GET['latitud'];
		}
		if (isset($_GET['longitud'])) {
			$longitud = $_GET['longitud'];
		}
		$response = array();
		//verificar token
		$res = $this->login_model->buscar_token($token);
		if (count($res) == 0) {
			$response["status"] = "Error";
			$response["respuesta"] = "Error al ingresar";
		} else {
			$response["status"] = "OK";
			$response["respuesta"] = "Ingreso correcto";
			$response["estados"] = $this->Sucursal_model->get_estados_unidades($id_marca);
			$response["unidades"] = $this->Sucursal_model->obtener_activas($id_marca);
		}
		echo json_encode($response);
	}

	// PRUEBAS 04-08-22
	// SERVICIOS DE MENÚ PARA TERCEROS

	/**
	 * ID: MDS-1
	 * Método: POST
	 * Descripción: Se obtienen los parametros de entrada por metodo post, el id de la marca, la latitud y longitud del dispositivo.
	 * Autorización: La autenticación será a través de un bearer token, el cual se obtendrá a través de una función. Este viene en los headers de la petición
	 * Respuesta: Esta función devolvera un objeto con las sucursales que cumplan la reglas indicadas, en caso de que ninguna cumpla, se regresará un array vacio
	 */
	// function sucursales_cerca()
	// {
	// 	$token = getBearerToken();
	// 	$id_marca = $_POST['id_marca'];
	// 	$id_consumo = $_POST['id_consumo'];
	// 	$latitud = '';
	// 	$longitud = '';
	// 	$response = array();

	// 	if (isset($_POST['latitud'])) {
	// 		$latitud = $_POST['latitud'];
	// 	}
	// 	if (isset($_POST['longitud'])) {
	// 		$longitud = $_POST['longitud'];
	// 	}

	// 	//verificar token
	// 	$res = $this->login_model->buscar_token_client($token);
	// 	if (count($res) == 0) {
	// 		$response["status"] = "Error";
	// 		$response["respuesta"] = "Error al ingresar";
	// 		echo json_encode($response);
	// 		return;
	// 	}
			
	// 	$response["status"] = "OK";
	// 	$response["respuesta"] = "Ingreso correcto";
	// 	$response["registros"] = $this->Sucursal_model->sucursales_estados_cerca($id_marca, $latitud, $longitud, $id_consumo);
	// 	return $this->output
	// 		->set_content_type('application/json')
	// 		->set_status_header(200)
	// 		->set_output(json_encode($response));
	// 	//echo json_encode($response);
	// }

	// function sucursales_estados_todos_post()
	// {
	// 	//$this->output->enable_profiler(TRUE);
	// 	$token = getBearerToken();
	// 	$id_marca = $_POST['id_marca'];
		
	// 	$response = array();
	// 	//verificar token
	// 	$res = $this->login_model->buscar_token_client($token);
	// 	if (count($res) == 0) {
	// 		$response["status"] = "Error";
	// 		$response["respuesta"] = "Error al ingresar";
	// 		echo json_encode($response);
	// 		return;
	// 	}

	// 	$response["status"] = "OK";
	// 	$response["respuesta"] = "Ingreso correcto";

	// 	$response["estados"] = $this->Sucursal_model->get_estados_unidades($id_marca);
	// 	$response["unidades"] = $this->Sucursal_model->obtener_activas($id_marca);


	// 	return $this->output
    //         ->set_content_type('application/json')
    //         ->set_status_header(200)
    //         ->set_output(json_encode($response));
	// 		return;
	// }
	
}
