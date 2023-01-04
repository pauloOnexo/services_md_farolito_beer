<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Index extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function adios()
	{
		$this->load->view('login');
	}
	public function saludo(){
		 $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);    
		echo json_encode( $arr );
	}
	function index() {  
		$this->load->view('form'); 
    } 
	function validar_datos() 
	{ 
		$this->load->model('login');
		$arrayRespuesta = array();
		$nombre = $_POST['correo'];
		$contrasena = sha1($_POST['contrasena']);
		if($this->login->validar_usuario($nombre,$contrasena)){
			//generar token
			$nuevoToken = true;
			$token = "";
			while ($nuevoToken){
				$token = bin2hex(random_bytes(64));
				//revisar que en la base no haya un token igual
				$res = $this->login->buscar_token($token);
				if(count($res)== 0){
					$nuevoToken = false;
					$this->login->update_usuario($_POST['correo'],$token);				
				}
			} 
			$arrayRespuesta["status"] = "OK";
			$arrayRespuesta["respuesta"] = "Usuario correcto";
			$arrayRespuesta["token"] = $token;
		}else{

                $arrayRespuesta["status"] = "ERROR";
                $arrayRespuesta["respuesta"] = "Usuario o contraseña incorrecta";
				$arrayRespuesta["token"] = '';
                $myJSON = json_encode($arrayRespuesta);
            }

		$myJSON = json_encode($arrayRespuesta);
		echo $myJSON;
	} 
}