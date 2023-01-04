<?php
class Region_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get($id_marca)
	{
		$query = $this->db->query("select * from Region where 1=1  and id_marca= ".$id_marca);
		return $query->result();
	}
    public function get_activas($id_marca)
	{
		$query = $this->db->query("select * from Region where 1=1 and id_marca= ".$id_marca);
		return $query->result();
	}
	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Region', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function add($data){

		if($this->db->insert('Region', $data)){
			return true;
		}else{
			return false;
		}
	}
	public function getById($id){
        $result = $this->db->query("select * from Region where id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["descripcion"] = $column->descripcion;
			$response["activo"] = $column->activo;
			$response["id_marca"] = $column->id_marca;
		}
        return $response;
    }
    public function eliminar($id){
        $this->db->where('id', $id);
        if($this->db->delete('Region')){
            return true;
        }else{
            return false;
        }
    }
}