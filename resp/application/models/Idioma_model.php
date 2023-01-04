<?php
class Idioma_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get()
	{
		$query = $this->db->query("select * from Idioma where 1=1 ");
		return $query->result();
	}

	public function get_activos()
	{
		$query = $this->db->query("select * from Idioma where activo = 1 ");
		return $query->result();
	}
	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Idioma', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function add($data){

		if($this->db->insert('Idioma', $data)){
			return true;
		}else{
			return false;
		}

	}
	public function getById($id){
        $result = $this->db->query("select * from Idioma where id = ".$id);
        return $query->result();
    }
//comentario
    public function eliminar($id){
        $result = $this->db->query("select * from Idioma where id = ".$id);
        $this->db->where('id', $id);
        if($this->db->delete('Idioma')){
            return true;
        }else{
            return false;
        }
    }
}