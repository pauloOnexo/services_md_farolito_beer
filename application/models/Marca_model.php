<?php
class Marca_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get()
	{
		$query = $this->db->query("select * from Marca where 1=1 ");
		return $query->result();
	}

	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Marca', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function add($data){

		if($this->db->insert('Marca', $data)){
			return true;
		}else{
			return false;
		}

	}
	public function getById($id){
        $result = $this->db->query("select * from Marca where id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["activo"] = $column->activo;
			$response["imagen"] = $column->imagen;
			$response["ubicacion"] = $column->ubicacion;
		}
        return $response;
    }
    public function eliminar($id){
        $result = $this->db->query("select * from Marca where id = ".$id);
        $ruta ="";
        foreach($result->result() as $column){
			$ruta = getenv('DIR_FILES')."/menudigital/Marcas/". $column->imagen;
		}
		
        $this->db->where('id', $id);
        if($this->db->delete('Marca')){
            /*if( unlink($ruta)) {
    
                    //echo 'deleted successfully';
    
               } else {
    
                    //echo 'errors occured';
    
               }*/
            return true;
        }else{
            return false;
        }
    }
}