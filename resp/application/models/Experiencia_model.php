<?php
class Experiencia_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get($id_marca)
	{
		$query = $this->db->query("select a.id,a.nombre,a.descripcion,a.ubicacion,a.imagen_popup from Experiencia a join Experiencia_Marca b 
						  
		                           on b.id_experiencia = a.id where 1=1 and activo =1 and b.id_marca = ".$id_marca);
										
		return $query->result();
	}
    public function get_todas($id_marca)
	{
		$query = $this->db->query("select a.id,a.nombre,a.descripcion,a.ubicacion,a.imagen_popup from Experiencia a join Experiencia_Marca b 
		                           on b.id_experiencia = a.id  where 1=1 and b.id_marca = ".$id_marca);
		return $query->result();
	}
		
	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Experiencia', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function update_idioma($data,$id_categoria,$id_idioma)
	{
		$registros = $this->db->query("SELECT * from Experiencia_Idioma where id_experiencia = ".$id_categoria. ' and id_idioma='.$id_idioma);
        $existe = 0;
        foreach($registros->result() as $column){
            $existe = $column->id;
		}
		if ($existe > 0)
		{
			$this->db->where('id_experiencia', $id_categoria);
			$this->db->where('id_idioma', $id_idioma);
			if($this->db->update('Experiencia_Idioma',$data)){
					return true;
			}else{
				return false;
			}
		}
		else{
			if($this->db->insert('Experiencia_Idioma', $data)){
					return true;
			}else{
				return false;
			}
		
		}
	}
	public function add($data,$id_marca){

		$data2 = array();
		if($this->db->insert('Experiencia', $data)){
		    $data2 ['id_experiencia'] = $this->db->insert_id();
		    $data2 ['id_marca'] = $id_marca; 
		    if($this->db->insert('Experiencia_Marca', $data2)){
			    return true;
		    }else{
    			return false;
    		}
		}else{
			return false;
		}

	}
	public function getById($id){
        $result = $this->db->query("select * from Experiencia where id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["descripcion"] = $column->descripcion;
			$response["activo"] = $column->activo;
			$response["imagen"] = $column->imagen;
			$response["ubicacion"] = $column->ubicacion;
			$response["imagen_popup"] = $column->imagen_popup;
					 
		}
        return $response;
    }
	public function getByIdIdioma($id,$id_idioma){
        $result = $this->db->query("select * from Experiencia_Idioma where id_experiencia= ".$id. ' and id_idioma='.$id_idioma);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $id;
			$response["nombre"] = $column->nombre;
			$response["descripcion"] = $column->descripcion;
			$response["activo"] = $column->activo;
			$response["imagen"] = $column->imagen;
			$response["ubicacion"] = $column->ubicacion;
			$response["imagen_popup"] = $column->imagen_popup;
		}
        return $response;
    }
    public function eliminar($id){
        $result = $this->db->query("select * from Experiencia where id = ".$id);
        $ruta ="";
        foreach($result->result() as $column){
			$ruta = $_SERVER['DOCUMENT_ROOT']."/menudigital/Experiencias/". $column->imagen;
		}
		
        $this->db->where('id', $id);
        if($this->db->delete('Experiencia')){
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
    public function experiencia_marca($id_marca){
        $result = $this->db->query("SELECT 
			b.id_experiencia,a.nombre nombre_experiencia,a.descripcion descripcion_experiencia,a.ubicacion, 
			b.id_marca,c.nombre nombre_marca,a.imagen_popup
			FROM Experiencia a 
			join Experiencia_Marca b on a.id = b.id_experiencia 
			join Marca c on b.id_marca = c.id
			WHERE a.activo = 1 and b.id_marca = ".$id_marca);
        /*/$response = array();
        foreach($result->result() as $column){
            $response["id_experiencia"] = $column->id_experiencia;
			$response["nombre_experiencia"] = $column->nombre_experiencia;
			$response["descripcion_experiencia"] = $column->descripcion_experiencia;
			$response["id_marca"] = $column->id_marca;
			$response["nombre_marca"] = $column->nombre_marca;
			$response["ubicacion"] = $column->ubicacion;
		}*/
		
        return $result->result();
    }
}