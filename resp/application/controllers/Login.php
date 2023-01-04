<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
class Login extends CI_Controller {

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
	    $_POST = json_decode(file_get_contents("php://input"),true);
		$this->load->model('Login_model');
		$arrayRespuesta = array();
		$nombre = $_POST['correo'];
		$contrasena = sha1($_POST['contrasena']);
		$resusuario = $this->Login_model->validar_usuario($nombre,$contrasena);
		if(count($resusuario)>0){
			//generar token
			$nuevoToken = true;
			$token = "";
			while ($nuevoToken){
				$token = bin2hex(random_bytes(64));
				//revisar que en la base no haya un token igual
				$res = $this->Login_model->buscar_token($token);
				if(count($res)== 0){
					$nuevoToken = false;
					$this->Login_model->update_usuario($_POST['correo'],$token);				
				}
			} 
			$arrayRespuesta["status"] = "OK";
			$arrayRespuesta["id_marca"] = $resusuario[0]->id_marca;
			$arrayRespuesta["id_rol"] = $resusuario[0]->id_rol;
			$arrayRespuesta["nombre"] = $resusuario[0]->nombre;
			$arrayRespuesta["respuesta"] = "Usuario correcto";
			$arrayRespuesta["token"] = $token;
		}else{

                $arrayRespuesta["status"] = "ERROR";
                $arrayRespuesta["respuesta"] = "Usuario o contraseña incorrecta";
				$arrayRespuesta["token"] = '';
            }

		$myJSON = json_encode($arrayRespuesta);
		echo $myJSON;
	} 
}