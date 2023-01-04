<?php

class Rol_model extends CI_Model{

    private $id_table = "id";

    private $name_table = "Rol";

	public function __construct(){
		parent::__construct();
	}
	
	public function get(){

       $query = $this->db->query("select * from Rol");
		return $query->result();
    }
}