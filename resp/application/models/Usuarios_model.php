<?php
class Usuarios_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	
	public function add($data){

		if($this->db->insert('Usuario', $data)){
			return true;
		}else{
			return false;
		}

	}
	
	public function get()
	{
		$query = $this->db->query("select * from Usuario");
		return $query->result();
	}
	
	public function getById($id){
        $result = $this->db->query("select * from Usuario where id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["correo"] = $column->correo;
			$response["id_rol"] = $column->id_rol;
			$response["id_marca"] = $column->id_marca;
			$response["telefono"] = $column->telefono;
		}
        return $response;
    }
    
    public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Usuario', $data)){
            return true;
        }else{
            return false;
        }
	}
	
	public function eliminar($id){
        $result = $this->db->query("select * from Usuario where id = ".$id);
        
		
        $this->db->where('id', $id);
        if($this->db->delete('Usuario')){
           
            return true;
        }else{
            return false;
        }
    }
}