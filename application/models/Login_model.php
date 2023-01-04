<?php
class Login_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function buscar_token($token){
		$query = $this->db->query("select * from Usuario where token = "."'".$token."'");
		return $query->result();
	}
	public function update_usuario($usuario,$token_)
	{
			$this->token    = $token_;
			$this->db->update('Usuario', $this, array('correo' => $usuario));

	}
	public function validar_usuario($nombre, $contrasena){
		$query = $this->db->get_where('Usuario', array('correo' => $nombre,'contrasenha'=> $contrasena));
		
		/*if($query->num_rows() == 1)
		{				
			return true;
		}
		else
			return false;*/
		return $query->result();
	}

	// PRUEBAS 04-08-22

	// Busqueda de token existente
	// public function buscar_token_client($token){
	// 	$query = $this->db->query("select token from auth_client where token = '$token' limit 1");
	// 	return $query->result();
	// }
	// Inserción de nuevo token validado
	// public function new_client_session($id_client,$token){

	// 	$this->db->insert('auth_client',array('client_id' => $id_client, 'token' => $token));
		
	// }
	// Se validan las credenciales para aplicaciones externas	
	// public function validar_cliente($clientUser, $clientPass){
	// 	$query = $this->db->get_where('client', array('client_user' => $clientUser,'client_pass'=> $clientPass));

	// 	return $query->result();
	// }
}