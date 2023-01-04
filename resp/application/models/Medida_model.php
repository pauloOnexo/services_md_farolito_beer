<?php
class Medida_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get()
	{
		$complemento ="";
		$query = $this->db->query("select * from Medida where 1=1 ".$complemento);
		return $query->result();
	}

	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Medida', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function add($data){

		if($this->db->insert('Medida', $data)){
			return true;
		}else{
			return false;
		}

	}
	public function getById($id){
        $result = $this->db->query("select * from Medida where id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["descripcion"] = $column->descripcion;
		}
        return $response;
    }
	public function eliminar($id){
        $this->db->where('id', $id);
        if($this->db->delete('Medida')){
            return true;
        }else{
            return false;
        }
    }
}