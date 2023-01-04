<?php
class Comunicado_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get($id_marca)
	{
		$query = $this->db->query("select * from Comunicado 
                                    where 1=1  and id_marca = ".$id_marca);
		return $query->result();
	}
	
	public function get_marca_sucursal($id_sucursal,$id_marca)
	{
	    $complemento ='';
	    $consulta ='';
	    $complemento_infantil='';
	    
		$consulta = "SELECT a.* FROM comunicado a  
						WHERE 1=1 and id_marca = ".$id_marca." and FIND_IN_SET('".$id_sucursal."',id_local)
					union ALL
					SELECT a.* FROM comunicado a  
						WHERE 1=1 and id_marca =  ".$id_marca." 
						and ".$id_sucursal." in 
						(select id from sucursal b where FIND_IN_SET(cast(b.id_region as char),a.ids_regiones))
					union ALL
					SELECT a.* FROM comunicado a  
						WHERE 1=1 and id_marca =  ".$id_marca." and id_local = '-1' and ids_regiones = '-1'
					";
		$query = $this->db->query($consulta);
		return $query->result();
	}
    
	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Comunicado', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function add($data){
        $data2 = array();
		if($this->db->insert('Comunicado', $data)){
		    return true;
		    
		}else{
			return false;
		}

	}
    public function getById($id){
        $result = $this->db->query("select * from Comunicado where id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["id_marca"] = $column->nombre;
			$response["id_local"] = $column->nombre;
			$response["ids_regiones"] = $column->ids_regiones;
			$response["activo"] = $column->activo;
			$response["fecha_inicio"] = $column->fecha_inicio;
			$response["fecha_fin"] = $column->fecha_fin;
			$response["imagen"] = $column->imagen;
			$response["tipo_comunicado"] = $column->tipo_comunicado;
			$response["link"] = $column->link;
		}
        return $response;
    }
    
    public function eliminar($id){
        $result = $this->db->query("select * from Comunicado where id = ".$id);
        $ruta ="";
        foreach($result->result() as $column){
			$ruta = getenv('DIR_FILES'). $column->imagen;
		}
        $this->db->where('id', $id);
        if($this->db->delete('Comunicado')){
            /*if(is_readable($ruta) && unlink($ruta) && unlink($ruta)) {
    
                    //echo 'deleted successfully';
    
               } else {
    
                    //echo 'errors occured';
    
               }*/
            return true;
        }else{
            return false;
       }
    }
	public function getCombinaciones($region){
        $query = $this->db->query("
				SELECT distinct 
					(select url from marca d where b.id_marca = d.id) marca,
					(select nombre from region a where a.id=b.id_region) nombre_region,
					b.id_region,(select c.nombre from region_alcohol c where c.id = b.alcohol) nombre_alcohol,alcohol,individual,kilos 
					FROM `sucursal` b where b.id_region = ".$region);
        return $query->result();
    }
}