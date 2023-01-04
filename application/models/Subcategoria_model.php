<?php
class Subcategoria_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get($id_marca)
	{
		$query = $this->db->query("select distinct a.id,a.nombre,a.descripcion,a.destacado 
								from Subcategoria a join Categoria_Marca b on FIND_IN_SET(b.id_categoria,a.categorias    )
		                            where 1=1 and b.id_marca = ".$id_marca);
		return $query->result();
	}
    public function get_categoria($id_marca)
	{
		$query = $this->db->query("select distinct a.id,concat(concat (a.nombre,' '),a.ubicacion) nombre ,a.descripcion,a.destacado ,a.color_pleca  
									from Subcategoria a join Categoria_Marca b on FIND_IN_SET(b.id_categoria,a.categorias  )
		                            where 1=1 and a.activo = 1 and b.id_marca = ".$id_marca);
		return $query->result();
	}
	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Subcategoria', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function update_idioma($data,$id_categoria,$id_idioma)
	{
		$registros = $this->db->query("SELECT * from Subcategoria_Idioma where id_subcategoria = ".$id_categoria. ' and id_idioma='.$id_idioma);
        $existe = 0;
        foreach($registros->result() as $column){
            $existe = $column->id;
		}
		if ($existe > 0)
		{
			$this->db->where('id_subcategoria', $id_categoria);
			$this->db->where('id_idioma', $id_idioma);
			if($this->db->update('Subcategoria_Idioma',$data)){
					return true;
			}else{
				return false;
			}
		}
		else{
			if($this->db->insert('Subcategoria_Idioma', $data)){
					return true;
			}else{
				return false;
			}
		
		}
	}
	public function add($data){

		if($this->db->insert('Subcategoria', $data)){
			return true;
		}else{
			return false;
		}

	}
	public function getByIdIdioma($id,$id_idioma){
        $result = $this->db->query("select * from Subcategoria_Idioma where id_subcategoria = ".$id. ' and id_idioma='.$id_idioma);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $id;
			$response["nombre"] = $column->nombre;
			$response["activo"] = $column->activo;
			$response["descripcion"] = $column->descripcion;
			$response["categorias"] = $column->categorias;
			$response["regiones"] = $column->regiones;
			$response["destacado"] = $column->destacado;
			$response["color_pleca"] = $column->color_pleca;
		}
        return $response;
    }
	public function getById($id){
        $result = $this->db->query("select * from Subcategoria where id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["activo"] = $column->activo;
			$response["descripcion"] = $column->descripcion;
			$response["categorias"] = $column->categorias;
			$response["regiones"] = $column->regiones;
			$response["destacado"] = $column->destacado;
			$response["color_pleca"] = $column->color_pleca;
		}
        return $response;
    }
    public function eliminar($id){
        $result = $this->db->query("select * from Subcategoria where id = ".$id);
        $ruta ="";
        foreach($result->result() as $column){
			$ruta = getenv('DIR_FILES')."/menudigital/Subcategorias/". $column->imagen;
		}
		
        $this->db->where('id', $id);
        if($this->db->delete('Subcategoria')){
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