<?php
class Categoria_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get($id_marca)
	{
		$query = $this->db->query("select distinct a.id,a.descripcion,a.nombre,a.id_marca,a.ubicacion ubicacion ,a.imagen_titulo_categoria
		                            ,a.imagen_fondo
									from Categoria a join Categoria_Marca b on a.id = b.id_categoria 
                                    where 1=1  and b.id_marca = ".$id_marca);
		//print_r("select id,descripcion,nombre,id_marca,CONCAT('".base_url()."categorias/'".",a.imagen) ubicacion from Categoria where 1=1 and activo = 1");
		return $query->result();
	}
	public function get_activas_sucursal($id_sucursal,$id_marca,$kilos)
	{
	    $complemento ='';
	    $consulta ='';
	    $complemento_infantil='';
		$complemento_sinkilos ='';
		if($kilos == 0)
			$complemento_sinkilos = " and a.nombre not like '%en casa%' ";
		else
			$complemento_sinkilos = " ";
	    if($id_sucursal == 208){
	        $consulta = "   
	                    select id id_categoria,descripcion descripcion_categoria,nombre nombre_categoria
						,id_marca,a.ubicacion ubicacion_categoria ,imagen_titulo_categoria,imagen_fondo
						,(case when nombre like '%desayuno%' then 1 when nombre like '%comida%' then 2 when nombre like '%cenas%' then 3
						when nombre like '%trago%' then 4 else id end) orden
		                            from Categoria a where 1=1 and a.id in(31,32,33,7,46) order by orden ";
	    }
		else{
			$consulta = "select distinct (case when a.id=47 then 0 else a.id end) orden, a.id id_categoria,descripcion descripcion_categoria
			,nombre nombre_categoria,a.id_marca,a.ubicacion ubicacion_categoria ,imagen_titulo_categoria,a.imagen_fondo
		                            from Categoria a join Categoria_Marca b on b.id_categoria = a.id 
		                            where 1=1 and activo = 1  and b.id_marca=".$id_marca.$complemento_sinkilos." order by orden";
		}
		$query = $this->db->query($consulta);
		//print_r($consulta);
		return $query->result();
	}
	public function get_activas_sucursal_idioma($id_sucursal,$id_marca,$id_idioma,$kilos)
	{
	    $complemento ='';
	    $consulta ='';
	    $complemento_infantil='';
		$complemento_sinkilos ='';
		if($kilos == 0)
			$complemento_sinkilos = " and aa.nombre not like '%en casa%' ";
		else
			$complemento_sinkilos = " ";
	    if($id_sucursal == 208){
	        $consulta = "   
				select aa.id id_categoria,a.descripcion descripcion_categoria,a.nombre nombre_categoria
				,aa.id_marca
				,IF(a.ubicacion<>'', a.ubicacion, aa.ubicacion) ubicacion_categoria 
				,IF(a.imagen_titulo_categoria<>'', a.imagen_titulo_categoria, aa.imagen_titulo_categoria) imagen_titulo_categoria
				,IF(a.imagen_fondo<>'', a.imagen_fondo, aa.imagen_fondo) imagen_fondo
				,(case when aa.nombre like '%desayuno%' then 1 when aa.nombre like '%comida%' then 2 when aa.nombre like '%cenas%' then 3
				when aa.nombre like '%trago%' then 4 else aa.id end) orden
				from Categoria_Idioma a 
				join Categoria aa  on a.id_categoria = aa.id
				where 1=1 and aa.id in(31,32,33,7,46) and a.id_idioma = ".$id_idioma." order by orden";
	    }
		else{
			$consulta = "
			select distinct (case when aa.id=47 then 0 else aa.id end) orden
            ,aa.id id_categoria,a.descripcion descripcion_categoria
			,a.nombre nombre_categoria,a.id_marca
            ,IF(a.ubicacion<>'', a.ubicacion, aa.ubicacion) ubicacion_categoria 
            ,IF(a.imagen_titulo_categoria<>'', a.imagen_titulo_categoria, aa.imagen_titulo_categoria) imagen_titulo_categoria
            ,IF(a.imagen_fondo<>'', a.imagen_fondo, aa.imagen_fondo) imagen_fondo
			from Categoria_Idioma a 
            join Categoria aa on a.id_categoria = aa.id 
            join Categoria_Marca b on b.id_categoria = aa.id 
			where 1=1 and a.activo = 1  and b.id_marca=".$id_marca." and a.id_idioma = ".$id_idioma.$complemento_sinkilos." order by orden";
		}
		$query = $this->db->query($consulta);
		return $query->result();
	}
	public function get_activas($id_sucursal,$id_marca)
	{
	    $complemento ='';
	    $consulta ='';
	    $complemento_infantil='';
	    if($id_sucursal == 208){
	        $consulta = "   
	                    select id,descripcion,nombre,id_marca,a.ubicacion ubicacion ,imagen_titulo_categoria,imagen_fondo
						,(case when nombre like '%desayuno%' then 1 when nombre like '%comida%' then 2 when nombre like '%cenas%' then 3
						when nombre like '%trago%' then 4 else id end) orden
		                            from Categoria a where 1=1 and a.id in(31,32,33,7,46) order by orden ";
	    }
		else{
			$consulta = "select distinct (case when a.id=47 then 0 else a.id end) orden, a.id,descripcion,nombre,a.id_marca,a.ubicacion ubicacion 
									,imagen_titulo_categoria,a.imagen_fondo
		                            from Categoria a join Categoria_Marca b on b.id_categoria = a.id 
		                            where 1=1 and activo = 1  and b.id_marca=".$id_marca."  order by orden";
		}
		$query = $this->db->query($consulta);
		//print_r("select id,descripcion,nombre,id_marca,CONCAT('".base_url()."categorias/'".",a.imagen) ubicacion from Categoria where 1=1 and activo = 1");
		return $query->result();
	}
    public function get_estado($id_sucursal)
	{
	    //falta agregar la condicion de cuando sea aeropuerto
	    $consulta ='';
		$query = $this->db->query("select * from Categoria where 1=1 and activo = 1");
		return $query->result();
	}
	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Categoria', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function update_idioma($data,$id_categoria,$id_idioma)
	{
		$registros = $this->db->query("SELECT * from Categoria_Idioma where id_categoria = ".$id_categoria. ' and id_idioma='.$id_idioma);
        $existe = 0;
        foreach($registros->result() as $column){
            $existe = $column->id;
		}
		if ($existe > 0)
		{
			$this->db->where('id_categoria', $id_categoria);
			$this->db->where('id_idioma', $id_idioma);
			if($this->db->update('Categoria_Idioma',$data)){
					return true;
			}else{
				return false;
			}
		}
		else{
			if($this->db->insert('Categoria_Idioma', $data)){
					return true;
			}else{
				return false;
			}
		
		}
	}
	public function add($data){
        $data2 = array();
		if($this->db->insert('Categoria', $data)){
		    $data2 ['id_categoria'] = $this->db->insert_id();
		    $data2 ['id_marca'] = $data ['id_marca']; 
		    if($this->db->insert('Categoria_Marca', $data2)){
			    return true;
		    }else{
    			return false;
    		}
		}else{
			return false;
		}

	}
    public function getById($id){
        $result = $this->db->query("select * from Categoria where id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["descripcion"] = $column->descripcion;
			$response["activo"] = $column->activo;
			$response["imagen"] = $column->imagen;
			$response["ubicacion"] = $column->ubicacion;
			$response["imagen_titulo_categoria"] = $column->imagen_titulo_categoria;
			$response["imagen_fondo"] = $column->imagen_fondo;
		}
        return $response;
    }
	public function getByIdIdioma($id,$id_idioma){
        $result = $this->db->query("select * from Categoria_Idioma where id_categoria = ".$id. ' and id_idioma='.$id_idioma);
        $response = array();
		$registros=0;
        foreach($result->result() as $column){
            $response["id"] = $id;
            $response["id_categoria_idioma"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["descripcion"] = $column->descripcion;
			$response["activo"] = $column->activo;
			$response["imagen"] = $column->imagen;
			$response["ubicacion"] = $column->ubicacion;
			$response["imagen_titulo_categoria"] = $column->imagen_titulo_categoria;
			$response["imagen_fondo"] = $column->imagen_fondo;
			$response["id_idioma"] = $column->id_idioma;
			$response["id_categoria"] = $column->id_categoria;
			$registros ++;
		}
		if($registros == 0){
			$response["id_categoria_idioma"] =0;
            /*$response["id_categoria_idioma"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["descripcion"] = $column->descripcion;
			$response["activo"] = $column->activo;
			$response["imagen"] = $column->imagen;
			$response["ubicacion"] = $column->ubicacion;
			$response["imagen_titulo_categoria"] = $column->imagen_titulo_categoria;
			$response["imagen_fondo"] = $column->imagen_fondo;
			$response["id_idioma"] = $column->id_idioma;
			$response["id_categoria"] = $column->id_categoria;*/
		}
        return $response;
    }
    public function categoria_marca($id_marca){
        $result = $this->db->query("SELECT 
            b.id_categoria,a.nombre nombre_categoria,a.descripcion descripcion_categoria,a.ubicacion, b.id_marca,c.nombre nombre_marca 
            ,a.imagen_fondo
			FROM Categoria a join Categoria_Marca b on a.id = b.id_categoria join Marca c on b.id_marca = c.id 
            WHERE a.activo = 1 and b.id_marca = ".$id_marca);
        /*$response = array();
        foreach($result->result() as $column){
            $response["id_categoria"] = $column->id_categoria;
			$response["nombre_categoria"] = $column->nombre_categoria;
			$response["descripcion_categoria"] = $column->descripcion_categoria;
			$response["id_marca"] = $column->id_marca;
			$response["nombre_marca"] = $column->nombre_marca;
			$response["ubicacion"] = $column->ubicacion;
		}*/
        return $result->result();
    }
    public function eliminar($id){
        $result = $this->db->query("select * from Categoria where id = ".$id);
        $ruta ="";
        foreach($result->result() as $column){
			$ruta = $_SERVER['DOCUMENT_ROOT']."/menudigital/categorias/". $column->imagen;
		}
        $this->db->where('id', $id);
        if($this->db->delete('Categoria')){
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
}