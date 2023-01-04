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
						WHERE 1=1 and activo = 1 and id_marca = ".$id_marca." and FIND_IN_SET('".$id_sucursal."',id_local)
					union ALL
					SELECT a.* FROM comunicado a  
						WHERE 1=1 and activo = 1 and id_marca =  ".$id_marca." 
						and ".$id_sucursal." in 
						(select id from sucursal b where FIND_IN_SET(cast(b.id_region as char),a.ids_regiones))
					union ALL
					SELECT a.* FROM comunicado a  
						WHERE 1=1 and activo = 1 and id_marca =  ".$id_marca." and id_local = '-1' and ids_regiones = '-1' and CURDATE() between fecha_inicio and fecha_fin
					";
		$query = $this->db->query($consulta);
		return $query->result();
	}
    public function get_marca_sucursal_idioma($id_sucursal,$id_marca,$idioma)
	{
	    $complemento ='';
	    $consulta ='';
	    $complemento_infantil='';
	    
		$consulta = "SELECT a.activo,a.fecha_fin,a.fecha_inicio,a.id,a.id_local,a.id_marca,a.ids_regiones,a.imagen,a.link,a.tipo_comunicado 
					FROM comunicado a  
						WHERE 1=1 and activo = 1 and id_marca = ".$id_marca." and FIND_IN_SET('".$id_sucursal."',id_local)
					union ALL
					SELECT a.activo,a.fecha_fin,a.fecha_inicio,a.id,a.id_local,a.id_marca,a.ids_regiones,a.imagen,a.link,a.tipo_comunicado 
					FROM comunicado a  
						WHERE 1=1 and activo = 1 and id_marca =  ".$id_marca." 
						and ".$id_sucursal." in 
						(select id from sucursal b where FIND_IN_SET(cast(b.id_region as char),a.ids_regiones))
					union ALL
					SELECT aa.activo,aa.fecha_fin,aa.fecha_inicio,aa.id,a.id_local,aa.id_marca,aa.ids_regiones,a.imagen,aa.link,aa.tipo_comunicado 
					FROM comunicado_idioma a  
					join comunicado aa on a.id_comunicado = aa.id
					WHERE 1=1 and aa.activo = 1 and aa.id_marca =  ".$id_marca." and aa.id_local = '-1' and aa.ids_regiones = '-1' 
					and CURDATE() between aa.fecha_inicio and aa.fecha_fin and a.id_idioma =".$idioma;
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
	public function update_idioma($data,$id_comunicado,$id_idioma)
	{
		$registros = $this->db->query("SELECT * from Comunicado_Idioma where id_comunicado = ".$id_comunicado. ' and id_idioma='.$id_idioma);
        $existe = 0;
        foreach($registros->result() as $column){
            $existe = $column->id;
		}
		if ($existe > 0)
		{
			$this->db->where('id_comunicado', $id_comunicado);
			$this->db->where('id_idioma', $id_idioma);
			if($this->db->update('Comunicado_Idioma',$data)){
					return true;
			}else{
				return false;
			}
		}
		else{
			if($this->db->insert('Comunicado_Idioma', $data)){
					return true;
			}else{
				return false;
			}
		
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
			$response["id_marca"] = $column->id_marca;
			$response["id_local"] = $column->id_local;
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
    public function getByIdIdioma($id,$id_idioma){
        $result = $this->db->query("select * from Comunicado_idioma where id_comunicado = ".$id .' and id_idioma ='.$id_idioma);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $id;
			$response["id_marca"] = $column->id_marca;
			$response["id_local"] = $column->id_local;
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