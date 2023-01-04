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
}