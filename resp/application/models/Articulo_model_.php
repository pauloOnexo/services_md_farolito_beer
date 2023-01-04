<?php
class Articulo_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get($id_marca)
	{
	    $consulta ="";
	    if($id_marca > 0){
	        $consulta =" and id_marca = ".$id_marca;
	    }
		$query = $this->db->query("select * from (
		    select case when a.id_subcategoria > 0 then (select c.id_marca  from Categoria_Marca c where FIND_IN_SET(c.id_categoria,b.categorias) limit 1) else (select f.id_marca from Categoria_Marca f where f.id_categoria = a.id_categoria limit 1) end id_marca,a.cantidad_x_porcion,a.orden,
			a.id id_articulo,a.platillo,a.nombre nombre_articulo,a.descripcion descripcion_articulo,a.simbologia,a.logo,a.id_subcategoria,a.id_experiencia,
            a.ubicacion ubicacion_articulo,e.nombre nombre_experiencia, e.descripcion descripcion_experiencia,e.ubicacion ubicacion_experiencia
            from Articulo a
            left join Subcategoria b on a.id_subcategoria = b.id
            left join Experiencia e on a.id_experiencia = e.id
                )t0 where 1=1 ".$consulta);
		return $query->result();
	}

	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Articulo', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function update_idioma($data,$id_articulo,$id_idioma)
	{
		$registros = $this->db->query("SELECT * from Articulo_Idioma where id_articulo = ".$id_articulo. ' and id_idioma='.$id_idioma);
        $existe = 0;
        foreach($registros->result() as $column){
            $existe = $column->id;
		}
		if ($existe > 0)
		{
			$this->db->where('id_articulo', $id_articulo);
			$this->db->where('id_idioma', $id_idioma);
			if($this->db->update('Articulo_Idioma',$data)){
					return true;
			}else{
				return false;
			}
		}
		else{
			if($this->db->insert('Articulo_Idioma', $data)){
					return true;
			}else{
				return false;
			}		
		}
	}
	public function update_sucursal_precio($data,$id,$id_sucursal)
	{
		 $this->db->where('id_articulo', $id);
		 $this->db->where('id_sucursal', $id_sucursal);
        if($this->db->update('Sucursal_Articulo', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function add($data){

		if($this->db->insert('Articulo', $data)){
			return $this->db->insert_id();
		}else{
			return 0;
		}
	}
	public function add_articulo_medidas($data){

		if($this->db->insert('Articulo_Medida', $data)){
			return true;
		}else{
			return false;
		}
	}
	public function add_articulo_medidas_idioma($data){

		if($this->db->insert('Articulo_Medida_Idioma', $data)){
			return true;
		}else{
			return false;
		}
	}
	public function getById($id){
        // $result = $this->db->query("SELECT * FROM `Articulo` a where a.id = ".$id);
        $result = $this->db->query("SELECT distinct  a.*,case when group_concat(distinct c.id) is not null then group_concat(distinct c.id) else d.id end id_categoria2 FROM `Articulo` a 
		left join Subcategoria b on a.id_subcategoria = b.id 
		left join Categoria c on FIND_IN_SET(c.id,b.categorias   )
		left join Categoria d on a.id_categoria = d.id where a.id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["activo"] = $column->activo;
			$response["platillo"] = $column->platillo;
			$response["descripcion"] = $column->descripcion;
			$response["categorias"] = $column->categorias;
			$response["id_subcategoria"] = $column->id_subcategoria;
			$response["id_categoria"] = $column->id_categoria2;
			$response["id_experiencia"] = $column->id_experiencia;
			$response["logo"] = $column->logo;
			$response["simbologia"] = $column->simbologia;
			$response["precio_nacional"] =round( $column->precio_nacional,0);
			$response["precio_acapulco"] = round($column->precio_acapulco,0);
			$response["precio_cc"] =round( $column->precio_cc,0);
			$response["precio_tijuana"] =round( $column->precio_tijuana,0);
			$response["precio_pl"] = round($column->precio_pl,0);
			$response["precio_aeropuerto"] =round( $column->precio_aeropuerto,0);
			$response["ubicacion"] = $column->ubicacion;
			$response["cantidad_x_porcion"] = $column->cantidad_x_porcion;
			$response["descripcion_imagen"] = $column->descripcion_imagen;
			$response["orden"] = $column->orden;
			$response["detalle_imagen"] = $column->detalle_imagen;
			$response["extra"] = $column->extra;
			$response["etiqueta_extra"] = $column->etiqueta_extra;
			$response["numero_opciones"] = $column->numero_opciones;									   
		}
        return $response;
    }
	public function getByIdIdioma($id,$id_idioma){
        // $result = $this->db->query("SELECT * FROM `Articulo` a where a.id = ".$id);
        $result = $this->db->query("
			SELECT distinct  
			a.*,case when group_concat(distinct c.id) is not null then group_concat(distinct c.id) else d.id end id_categoria2 
			FROM `Articulo_Idioma` a 
			left join Subcategoria b on a.id_subcategoria = b.id 
			left join Categoria c on FIND_IN_SET(c.id,b.categorias   )
			left join Categoria d on a.id_categoria = d.id where a.id_articulo = ".$id .' and a.id_idioma=' .$id_idioma);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $id;
			$response["nombre"] = $column->nombre;
			$response["activo"] = $column->activo;
			$response["platillo"] = $column->platillo;
			$response["descripcion"] = $column->descripcion;
			$response["categorias"] = $column->categorias;
			$response["id_subcategoria"] = $column->id_subcategoria;
			$response["id_categoria"] = $column->id_categoria2;
			$response["id_experiencia"] = $column->id_experiencia;
			$response["logo"] = $column->logo;
			$response["simbologia"] = $column->simbologia;
			$response["precio_nacional"] =round( $column->precio_nacional,0);
			$response["precio_acapulco"] = round($column->precio_acapulco,0);
			$response["precio_cc"] =round( $column->precio_cc,0);
			$response["precio_tijuana"] =round( $column->precio_tijuana,0);
			$response["precio_pl"] = round($column->precio_pl,0);
			$response["precio_aeropuerto"] =round( $column->precio_aeropuerto,0);
			$response["ubicacion"] = $column->ubicacion;
			$response["cantidad_x_porcion"] = $column->cantidad_x_porcion;
			$response["descripcion_imagen"] = $column->descripcion_imagen;
			$response["orden"] = $column->orden;
			$response["detalle_imagen"] = $column->detalle_imagen;
			$response["extra"] = $column->extra;
			$response["etiqueta_extra"] = $column->etiqueta_extra;
			$response["numero_opciones"] = $column->numero_opciones;									   
		}
        return $response;
    }
    public function get_medidas($id){
        $result = $this->db->query("SELECT  a.selected,a.id,a.numero,a.id_articulo,a.nombre nombre_medida,a.precio_nacional,a.precio_acapulco,a.precio_cc,a.precio_tijuana,a.precio_pl,a.cantidad_x_porcion_medida,a.precio_aeropuerto,a.activo 
        FROM `Articulo_Medida` a where a.id_articulo = ".$id);
        return $result->result();
    }
	public function get_medidas_idioma($id,$id_idioma){
		$registrosI = $this->db->query("SELECT  a.selected,a.id,a.numero,a.id_articulo,a.nombre nombre_medida,a.precio_nacional,a.precio_acapulco,a.precio_cc,a.precio_tijuana,a.precio_pl,a.cantidad_x_porcion_medida,a.precio_aeropuerto,a.activo 
        FROM `Articulo_Medida_Idioma` a where a.id_articulo = ".$id. ' and a.id_idioma='.$id_idioma);
        $existe = 0;
        foreach($registrosI->result() as $column){
            $existe = $column->id;
		}
		if ($existe == 0)
		{
			$result = $this->db->query("SELECT  a.selected,a.id,a.numero,a.id_articulo,a.nombre nombre_medida,a.precio_nacional,a.precio_acapulco,a.precio_cc,a.precio_tijuana,a.precio_pl,a.cantidad_x_porcion_medida,a.precio_aeropuerto,a.activo 
			FROM `Articulo_Medida` a where a.id_articulo = ".$id);
			return $result->result();
		}
		else{
			$result = $this->db->query("
			SELECT  a.selected,a.id,a.numero,a.id_articulo,a.nombre nombre_medida
			,a.precio_nacional,a.precio_acapulco,a.precio_cc,a.precio_tijuana,a.precio_pl
			,a.cantidad_x_porcion_medida,a.precio_aeropuerto,a.activo 
			FROM `Articulo_Medida_Idioma` a where a.id_articulo = ".$id. ' and a.id_idioma='.$id_idioma." 
			union all 
			SELECT  a.selected,a.id,a.numero,a.id_articulo,a.nombre nombre_medida
			,a.precio_nacional,a.precio_acapulco,a.precio_cc,a.precio_tijuana,a.precio_pl
			,a.cantidad_x_porcion_medida,a.precio_aeropuerto,a.activo 
			FROM `Articulo_Medida` a where a.id_articulo = ".$id. " and a.numero 
			not in (SELECT  b.numero FROM `Articulo_Medida_Idioma` b 
                   where b.id_articulo = ".$id." and b.id_idioma=".$id_idioma." )");
			return $result->result();
		}
    }
	public function get_medidas_precio($id,$precio,$id_sucursal,$id_subcategoria,$nombre){
		$complemento ='';
        $sucursal = $this->db->query("SELECT * FROM Sucursal a where a.id = ".$id_sucursal);
        $alcohol = 0;
        $rebanada =0;
        foreach($sucursal->result() as $column){
            $alcohol = $column->alcohol;
            $rebanada = $column->individual;
		}
		$restriccion ="";
		if($rebanada == 0 )
		     $restriccion = " and a.nombre not like '%individual%' ";
		 
		if($alcohol == 1 && $id_subcategoria== 27  )
		    $complemento = " and a.nombre not like '%draft%' ";
		if($alcohol == 4 && $id_subcategoria== 27  )
		    $complemento = " and a.nombre not like '%draft%' ";
		    
        $result = $this->db->query("select selected,id,numero,id_articulo,nombre_medida
                        ,( case when precio = 0 then 0 else precio end ) precio
                        ,cantidad_x_porcion_medida
                    from (SELECT a.selected,a.id,a.numero,a.id_articulo,a.nombre nombre_medida
                                    ,(case when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.") in (1)then ROUND(a.precio_nacional) 
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 2 then ROUND(a.precio_acapulco )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 3 then ROUND(a.precio_cc )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 4 then ROUND(a.precio_tijuana )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 5 then ROUND(a.precio_pl )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 6 then ROUND(a.precio_aeropuerto)
             end ) precio
                                    ,a.cantidad_x_porcion_medida FROM `Articulo_Medida` a where a.activo = 1 and a.id_articulo = ".$id. " ".$complemento." ".$restriccion."  )t1 where precio > 0 order by numero" );
            $response = array();
                           
        return $result->result();
    }
    //precio falta agregar condicion 
    public function get_medidas_precio_idioma($id,$precio,$id_sucursal,$id_subcategoria,$nombre,$id_idioma){
		$complemento ='';
        $sucursal = $this->db->query("SELECT * FROM Sucursal a where a.id = ".$id_sucursal);
        $alcohol = 0;
        $rebanada =0;
        foreach($sucursal->result() as $column){
            $alcohol = $column->alcohol;
            $rebanada = $column->individual;
		}
		$restriccion ="";
		if($rebanada == 0 )
		     $restriccion = " and a.nombre not like '%individual%' ";
		 
		if($alcohol == 1 && $id_subcategoria== 27  )
		    $complemento = " and a.nombre not like '%draft%' ";
		if($alcohol == 4 && $id_subcategoria== 27  )
		    $complemento = " and a.nombre not like '%draft%' ";
		    
        $result = $this->db->query("
				select distinct selected,id,numero,id_articulo,nombre_medida
                        ,( case when precio = 0 then 0 else precio end ) precio
                        ,cantidad_x_porcion_medida
                from (SELECT a.selected,a.id,a.numero,a.id_articulo,a.nombre nombre_medida
                    ,(case when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.") in (1)then ROUND(a.precio_nacional) 
					when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 2 then (select round(aa.precio_acapulco) from Articulo_Medida aa where a.id_articulo = aa.id_articulo)
					when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 3 then ROUND(a.precio_cc )
					when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 4 then ROUND(a.precio_tijuana )
					when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 5 then ROUND(a.precio_pl )
					when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 6 then ROUND(a.precio_aeropuerto)
					 end ) precio
					,a.cantidad_x_porcion_medida 
					FROM `Articulo_Medida_Idioma` a 
					-- join Articulo_Medida aa on a.id_articulo = aa.id_articulo
					where a.activo = 1 and a.id_idioma = ".$id_idioma." and a.id_articulo = ".$id. " ".$complemento." ".$restriccion."  
				)t1 
				where precio > 0 order by numero" );
            $response = array();
                           
        return $result->result();
    }
    public function get_experiencias_idioma($id_experiencias,$id_idioma){
        $result = $this->db->query("
		select a.nombre,a.descripcion,IF( a.ubicacion<>'',  a.ubicacion,  aa.ubicacion) ubicacion
		,IF( a.imagen_popup<>'',  a.imagen_popup,  aa.imagen_popup)imagen_popup
		from Experiencia_Idioma a join Experiencia aa on a.id_experiencia = aa.id
		where FIND_IN_SET(a.id_experiencia,'".$id_experiencias."') and a.id_idioma = ".$id_idioma."" );
           $response = array();
                           
        return $result->result();
    }
	public function get_experiencias($id_experiencias){
        $result = $this->db->query("select * from Experiencia where FIND_IN_SET(id,'".$id_experiencias."')" );
           $response = array();
                           
        return $result->result();
    }
    public function del_medidas($id){
        $this->db->where('id_articulo', $id);
        if($this->db->delete('Articulo_Medida')){
            return true;
        }else{
            return false;
        }
    }
	 public function del_medidas_idioma($id,$id_idioma){
        $this->db->where('id_articulo', $id);
        $this->db->where('id_idioma', $id_idioma);
        if($this->db->delete('Articulo_Medida_Idioma')){
            return true;
        }else{
            return false;
        }
    }
    public function eliminar($id){
        $result = $this->db->query("select * from Articulo where id = ".$id);
        $ruta ="";
        foreach($result->result() as $column){
			$ruta = $_SERVER['DOCUMENT_ROOT']."/menudigital/Marcas/". $column->imagen;
		}		
        $this->db->where('id', $id);
        if($this->db->delete('Articulo')){
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
    public function categoria_sucursal($id_categoria,$id_sucursal){
        $result = $this->db->query("select b.id_marca,c.id id_sucursal, c.nombre nombre_sucursal,a.id id_categoria,a.nombre nombre_categoria, a.descripcion descripcion_subcategoria,a.ubicacion ubicacion_categoria,
        '' subcategorias ,a.imagen_titulo_categoria
            from Categoria a 
            join Categoria_Marca b on a.id = b.id_categoria
            join Sucursal c on b.id_marca = c.id_marca
            where a.id=".$id_categoria." and c.id= ".$id_sucursal);
        return $result->result();
    }
    public function categoria_subcategorias($id_categoria,$id_sucursal){
        $complemento ="";
        $sucursal = $this->db->query("SELECT * FROM Sucursal a where a.id = ".$id_sucursal);
        $region = 0;
        foreach($sucursal->result() as $column){
            $region = $column->id_region;
		}
		$complemento2 ="";
		if($region == 2)
			$complemento2 = " and a.id not in (14) ";
		if($region > 0){
		    $complemento = "select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when nombre like '%TOKS a%' then 100 
			  when a.id = 4907 then 0
			  when a.id = 489 then 1 
			  when a.id = 2 then 2
              when a.id = 488 then 3 
			  when a.id = 4916 then 4
			  when a.id = 4905 then 5
			  when a.id = 15 then 6 
              when a.id = 490 then 7
              when a.id = 4906 then 8
              when a.id = 14 then 9  else a.id end)orden,a.destacado,a.color_pleca
            from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('".$region."',a.regiones) 
            group by orden ) t0 union all 
            ";
		}						   
        if($id_categoria == 1 ){
            $result = $this->db->query($complemento."select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when nombre like '%especialidad%' then -1 when nombre like '%desayunos toks%' then 0  when nombre like '%TOKS%' then 100   else a.id end)orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0");
        
        return $result->result();
        
        }
		if($id_categoria == 31){ //desayunos aeropuerto
            $result = $this->db->query($complemento."select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when nombre like '%cervezas%' then 100  when nombre like '%postres%' then 99  else a.id end)orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0");
        
        return $result->result();
        
        }
         if($id_categoria == 2  ){
            $result2 = $this->db->query($complemento."select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when nombre like '%especialidad%' then -1 when nombre like '%TOKS a%' then 100 when nombre like '%bebidas calientes%' then (100-1)  when nombre like '%vinos%' then (100-2)  when nombre like '%cervezas%' then (100-3) when nombre like '%refrescantes%' then (100-4) when nombre like '%postres%' then (100-5) else a.id end)orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden )t0 ");
        
        return $result2->result();
        }
		if($id_categoria == 32){ //comidas aeropuerto
            $result2 = $this->db->query("select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when nombre like '%bebidas calientes%' then 0  when nombre like '%cervezas%' then (100)  when nombre like '%refrescantes%' then (99) 
			 when nombre like '%especialidad%' then -1 when nombre like '%postres%' then (98) when nombre like '%enchiladas%' then 97 when nombre like '%vinos%' then 101 else a.id end)orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden )t0 ");
        
        return $result2->result();
        }
		if( $id_categoria == 3 ){
            $result2 = $this->db->query($complemento."select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when nombre like '%especialidad%' then -1  when nombre like '%TOKS a%' then 100 when nombre like '%bebidas calientes%' then (100-1)  when nombre like '%vinos%' then (100-2)  when nombre like '%cervezas%' then (100-3) when nombre like '%refrescantes%' then (100-4) when nombre like '%postres%' then (100-5) else a.id end)orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.activo = 1 and FIND_IN_SET('0',a.regiones)  ".$complemento2."
            group by orden )t0 ");
        
        return $result2->result();
        }
		if( $id_categoria == 33){ //cenas aeropuerto
            $result2 = $this->db->query("select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when nombre like '%bebidas calientes%' then 0  when nombre like '%cervezas%' then (100)  when nombre like '%refrescantes%' then (99) 
			when nombre like '%postres%' then (98) when nombre like '%enchiladas%' then 97 when nombre like '%vinos%' then 101 else a.id end)orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden )t0 ");
        
        return $result2->result();
        }
         if( $id_categoria == 4){
            $result2 = $this->db->query("select * from ( select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when nombre like '%agrega%' then a.id else a.id end)orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }
        if($id_categoria == 7){ //bar
            $result2 = $this->db->query($complemento."select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when nombre like '%vino%' then 0 when a.id = 4907 then 100
			  when a.id = 27 then 1 when a.id = 2 then 2
              when a.id = 38 then 3 
			  when a.id = 39 then 4
			  when a.id = 493 then 5 
              when a.id = 40 then 6
              when a.id = 41 then 7
              when a.id = 42 then 8 
              when a.id = 43 then 9 
              when a.id = 44 then 10
              when a.id = 45 then 11  
              else a.id end)orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }
        if($id_categoria == 11){
			
            $result2 = $this->db->query("select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,a.id orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }
								
		/*print_r("select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when nombre like '%vino%' then 100 else id end)orden
            from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");*/	
        if($id_categoria == 25 || $id_categoria == 12){
            $result2 = $this->db->query("select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,id orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 order by id_subcategoria");
        
        return $result2->result();
        }
		if($id_categoria == 29 || $id_categoria == 30 ){
            $result2 = $this->db->query($complemento."select * from (select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when a.nombre like '%kilo%' then 0  when a.nombre like '%cazuelas%' then 1  else a.id end) orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }
		if( $id_categoria == 46){
            $result2 = $this->db->query("select * from ( select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when nombre like '%agrega%' then a.id else a.id end)orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }
		if( $id_categoria == 47){ ///platillos del mes
            $result2 = $this->db->query($complemento."select * from ( select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when a.id = 489 then 1 
              when a.id = 488 then 3 
			  when a.id = 4905 then 4
			  when a.id = 15 then 5 
              when a.id = 490 then 6
              when a.id = 4906 then 7
              when a.id = 14 then 8 else a.id end)orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1 ".$complemento2."
            group by orden) t0 order by orden ");        
        return $result2->result();
        }
		if( $id_categoria == 57){ //kilos
            $result2 = $this->db->query("select * from ( select a.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            , a.id orden
            ,a.destacado,a.color_pleca
			from Subcategoria a 
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }
    }
    public function categoria_subcategorias_idioma($id_categoria,$id_sucursal,$id_idioma){
        $complemento ="";
        $sucursal = $this->db->query("SELECT * FROM Sucursal a where a.id = ".$id_sucursal);
        $region = 0;
        foreach($sucursal->result() as $column){
            $region = $column->id_region;
		}
		$complemento2 ="";
		$complementoini ="select + from (";
		$complementofin =" )t1";
		if($region == 2)
			$complemento2 = " and a.id not in (14) ";
		if($region > 0){
		    $complemento = "select * from (select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%TOKS a%' then 100 
			  when aa.id = 4907 then 0
			  when aa.id = 489 then 1 when a.id = 2 then 2
              when aa.id = 488 then 3 
              when aa.id = 4916 then 4 
			  when aa.id = 4905 then 5
			  when aa.id = 15 then 6 
              when aa.id = 490 then 7
              when aa.id = 4906 then 8
              when aa.id = 14 then 9  else aa.id end)orden,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('".$region."',a.regiones) 
            group by orden ) t0 union all 
            ";
		}						   
        if($id_categoria == 1 ){
            $result = $this->db->query($complemento."select * from (select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%especialidad%' then -1 when aa.nombre like '%desayunos toks%' then 0  when aa.nombre like '%TOKS%' then 100   else aa.id end)orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0");
        
        return $result->result();
        
        }
		if($id_categoria == 31){ //desayunos aeropuerto
            $result = $this->db->query($complemento."select * from (select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%cervezas%' then 100  when aa.nombre like '%postres%' then 99  else aa.id end)orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0");
        
        return $result->result();
        
        }
         if($id_categoria == 2  ){
            $result2 = $this->db->query($complemento."select * from (select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%especialidad%' then -1 when aa.nombre like '%TOKS a%' then 100 when aa.nombre like '%bebidas calientes%' then (100-1)  when aa.nombre like '%vinos%' then (100-2)  when aa.nombre like '%cervezas%' then (100-3) when aa.nombre like '%refrescantes%' then (100-4) when aa.nombre like '%postres%' then (100-5) else aa.id end)orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden )t0 ");
        
        return $result2->result();
        }
		if($id_categoria == 32){
            $result2 = $this->db->query("select * from (select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%bebidas calientes%' then 0  when aa.nombre like '%cervezas%' then (100)  when aa.nombre like '%refrescantes%' then (99) 
			 when aa.nombre like '%especialidad%' then -1 when aa.nombre like '%postres%' then (98) when aa.nombre like '%enchiladas%' then 97 when aa.nombre like '%vino%' then 101 else aa.id end)orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',aa.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',aa.regiones) and a.activo = 1
            group by orden )t0 ");
        
        return $result2->result();
        }
		if( $id_categoria == 3 ){
            $result2 = $this->db->query($complemento."select * from (select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%especialidad%' then -1  when aa.nombre like '%TOKS a%' then 100 when aa.nombre like '%bebidas calientes%' then (100-1)  when aa.nombre like '%vinos%' then (100-2)  when aa.nombre like '%cervezas%' then (100-3) when aa.nombre like '%refrescantes%' then (100-4) when aa.nombre like '%postres%' then (100-5) else aa.id end)orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.activo = 1 and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',a.regiones)  ".$complemento2."
            group by orden )t0 ");
        
        return $result2->result();
        }
		if( $id_categoria == 33){
            $result2 = $this->db->query("select * from (select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%bebidas calientes%' then 0  when aa.nombre like '%cervezas%' then (100)  when aa.nombre like '%refrescantes%' then (99) 
			when aa.nombre like '%postres%' then (98) when aa.nombre like '%enchiladas%' then 97 when aa.nombre like '%vino%' then 101 else aa.id end)orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',aa.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',aa.regiones) and a.activo = 1
            group by orden )t0 ");
        
        return $result2->result();
        }
         if( $id_categoria == 4){
            $result2 = $this->db->query("select * from ( select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%agrega%' then a.id else aa.id end)orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }
        if($id_categoria == 7){
            $result2 = $this->db->query($complemento."select * from (select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%vino%' then 0 when a.id = 4907 then 100
			  when aa.id = 27 then 1
              when aa.id = 38 then 3 
			  when aa.id = 39 then 4
			  when aa.id = 493 then 5 
              when aa.id = 40 then 6
              when aa.id = 41 then 7
              when aa.id = 42 then 8 
              when aa.id = 43 then 9 
              when aa.id = 44 then 10
              when aa.id = 45 then 11  
              else aa.id end)orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }
        if($id_categoria == 11){
			
            $result2 = $this->db->query("select * from (select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,a.id orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }	
        if($id_categoria == 25 || $id_categoria == 12){
            $result2 = $this->db->query("select * from (select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,aa.id orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 order by id_subcategoria");
        
        return $result2->result();
        }
		if($id_categoria == 29 || $id_categoria == 30 ){
            $result2 = $this->db->query($complemento."select * from (select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%kilo%' then 0 else aa.id end) orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }
		if( $id_categoria == 46){
            $result2 = $this->db->query("select * from ( select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%agrega%' then aa.id else aa.id end)orden
            ,a.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',a.regiones) and a.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }
		if( $id_categoria == 47){ ///platillos del mes
            $result2 = $this->db->query('select distinct * from( '.$complemento."select * from ( select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.id = 489 then 1 when aa.id = 2 then 2
              when aa.id = 488 then 3 
			  when aa.id = 4905 then 4
			  when aa.id = 15 then 5 
              when aa.id = 490 then 6
              when aa.id = 4906 then 7
              when aa.id = 14 then 8 else aa.id end)orden
            ,aa.destacado,aa.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and a.id_idioma = ".$id_idioma." and FIND_IN_SET('0',a.regiones) and a.activo = 1 ".$complemento2."
            group by orden) t0 order by orden )t1 ");        
        return $result2->result();
        }
		if( $id_categoria == 57){ //kilos
            $result2 = $this->db->query("select * from ( select aa.id id_subcategoria,a.nombre nombre_subcategoria, a.descripcion descripcion_subcategoria
            ,(case when aa.nombre like '%agrega%' then a.id else a.id end)orden
            ,a.destacado,a.color_pleca
            from Subcategoria_Idioma a 
			join Subcategoria aa on a.id_subcategoria = aa.id
            where FIND_IN_SET('".$id_categoria."',a.categorias) and FIND_IN_SET('0',aa.regiones) and aa.activo = 1
            group by orden) t0 ");
        
        return $result2->result();
        }
    }
    public function articulo_subcategorias($id_subcategoria){
        $result = $this->db->query("select a.cantidad_x_porcion,a.orden,
            a.id id_articulo,a.platillo,a.nombre nombre_articulo,a.descripcion descripcion_articulo,a.simbologia,a.logo,a.id_subcategoria,a.id_subcategoria,a.id_experiencia,
            a.ubicacion ubicacion_articulo,a.precio_tijuana precio,e.nombre nombre_experiencia, e.descripcion descripcion_experiencia,e.ubicacion ubicacion_experiencia,
            a.descripcion_imagen
            from Articulo a
            left join Experiencia e on a.id_experiencia = e.id
            where a.activo = 1 and a.id_subcategoria = ".$id_subcategoria. " order by a.orden ");
        
            //where a.activo = 1 and a.id_subcategoria = ".$id_subcategoria." and c.id = ".$id_sucursal);
        return $result->result();
    }
 public function articulo_subcategorias2($id_subcategoria,$id_sucursal){
		$complemento ='';
		$complemento2 ='';
		$id_estado =0;
        $sucursal = $this->db->query("SELECT * FROM Sucursal a where a.id = ".$id_sucursal);
        $alcohol = 0;
        foreach($sucursal->result() as $column){
            $alcohol = $column->alcohol;
            $id_estado = $column->id_estado;
		}
		if($alcohol == 2)
		    $complemento = ' and a.id_subcategoria not in (27,28)';
		if($id_sucursal == 225)   //interlomas
			$complemento = ' and a.id_subcategoria not in (110,111)';
		if($alcohol == 4)
		    $complemento = ' and a.id_subcategoria not in (28)';
		
		/*if($id_estado != 9 && $id_estado != 15)
		    $complemento2 = ' and a.id not in (308)'; //pan frances*/
		$result = $this->db->query("select * from (select a.cantidad_x_porcion,a.orden,".$id_sucursal."
			,a.extra,a.etiqueta_extra,a.numero_opciones
            ,a.id id_articulo,a.platillo,a.nombre nombre_articulo,a.descripcion descripcion_articulo,a.simbologia,a.logo,a.id_subcategoria,a.id_experiencia,
             a.ubicacion ubicacion_articulo,IF(a.imagen_min IS NULL OR a.imagen_min = '' or a.imagen_min = '/menudigital/Articulos/','',CONCAT('".base_url()."Articulos/'".",a.imagen_min))  ubicacion_articulo_min,(case when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.") in (1)then ROUND(a.precio_nacional )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 2 then ROUND(a.precio_acapulco )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 3 then ROUND(a.precio_cc )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 4 then ROUND(a.precio_tijuana )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 5 then ROUND(a.precio_pl )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 6 then ROUND(a.precio_aeropuerto)
             end ) precio,e.nombre nombre_experiencia, e.descripcion descripcion_experiencia,e.ubicacion ubicacion_experiencia,
            a.descripcion_imagen,a.detalle_imagen
            from Articulo a
            left join Experiencia e on a.id_experiencia = e.id
            where a.activo = 1 and a.id_subcategoria = ".$id_subcategoria. " ". $complemento."  ".$complemento2.")t0 where precio > 0 order by orden");
        	
        return $result->result();
    }
	public function articulo_subcategorias2_idioma($id_subcategoria,$id_sucursal,$id_idioma){
		$complemento ='';
		$complemento2 ='';
		$id_estado =0;
        $sucursal = $this->db->query("SELECT * FROM Sucursal a where a.id = ".$id_sucursal);
        $alcohol = 0;
        foreach($sucursal->result() as $column){
            $alcohol = $column->alcohol;
            $id_estado = $column->id_estado;
		}
		if($alcohol == 2)
		    $complemento = ' and aa.id_subcategoria not in (27,28)';
		    
		if($alcohol == 4)
		    $complemento = ' and aa.id_subcategoria not in (28)';
		
		if($id_estado != 9 && $id_estado != 15)
		    $complemento2 = ' and a.id not in (308)'; //pan frances
		$result = $this->db->query("select * from (select a.cantidad_x_porcion,aa.orden
			,aa.extra,a.etiqueta_extra,aa.numero_opciones
            ,a.id_articulo id_articulo,a.platillo,a.nombre nombre_articulo,a.descripcion descripcion_articulo,a.simbologia,a.logo
			,aa.id_subcategoria,aa.id_experiencia
            ,IF( a.ubicacion<>'',  a.ubicacion,  aa.ubicacion) ubicacion_articulo
			,CONCAT('".base_url()."Articulos/'".",(IF( a.imagen_min<>'',  a.imagen_min,  aa.imagen_min)))  ubicacion_articulo_min
			,(case when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.") in (1)then ROUND(aa.precio_nacional )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 2 then ROUND(aa.precio_acapulco )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 3 then ROUND(aa.precio_cc )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 4 then ROUND(aa.precio_tijuana )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 5 then ROUND(aa.precio_pl )
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 6 then ROUND(aa.precio_aeropuerto)
             end ) precio,e.nombre nombre_experiencia, e.descripcion descripcion_experiencia,e.ubicacion ubicacion_experiencia
            ,a.descripcion_imagen,a.detalle_imagen
            from Articulo_Idioma a
			join Articulo aa on a.id_articulo = aa.id
            left join Experiencia e on aa.id_experiencia = e.id
            where aa.activo = 1 and a.id_idioma = ".$id_idioma." and aa.id_subcategoria = ".$id_subcategoria. " ". $complemento."  ".$complemento2."
			)t0 where precio > 0 order by orden");
        	
        return $result->result();
    }
    public function articulo_sin_subcategorias($id_categoria,$id_sucursal){
		$complemento ='';
        $sucursal = $this->db->query("SELECT * FROM Sucursal a where a.id = ".$id_sucursal);
        $alcohol = 0;   
        $result = $this->db->query("select * from (select a.cantidad_x_porcion,a.orden,
            a.id id_articulo,a.platillo,a.nombre nombre_articulo,a.descripcion descripcion_articulo,a.simbologia,a.logo,a.id_subcategoria,a.id_experiencia,
             a.ubicacion  ubicacion_articulo,IF(a.imagen_min IS NULL OR a.imagen_min = '','',CONCAT('".base_url()."Articulos/'".",a.imagen_min))  ubicacion_articulo_min,(case when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.") in (1)then a.precio_nacional 
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 2 then a.precio_acapulco 
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 3 then a.precio_cc 
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 4 then a.precio_tijuana 
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 5 then a.precio_pl 
            when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 6 then a.precio_aeropuerto
             end ) precio,
            a.descripcion_imagen,a.detalle_imagen
            from Articulo a
            left join Experiencia e on a.id_experiencia = e.id
            where a.activo = 1 and a.id_categoria = ".$id_categoria. " )t0 where precio > 0 order by orden");
        
            //where a.activo = 1 and a.id_subcategoria = ".$id_subcategoria." and c.id = ".$id_sucursal);
        return $result->result();
    }
    public function articulo_nombre_sucursal($nombre,$id_marca){
        $complemento ="";
        if(nombre!= "")
            $complemento = "and  (a.platillo like '%".$nombre."%' or a.nombre like '%".$nombre."%')";
        $result = $this->db->query("select a.cantidad_x_porcion,a.orden,b.cantidad_x_porcion_medida cantidad_x_porcion_medida,
            a.id id_articulo,a.platillo,a.nombre nombre_articulo,a.descripcion descripcion_articulo,a.simbologia,a.logo,a.id_subcategoria,a.id_subcategoria,a.id_experiencia,
            a.ubicacion ubicacion_articulo,a.precio_nacional precio,c.nombre descripcion_medida,e.nombre nombre_experiencia, e.descripcion descripcion_experiencia,e.ubicacion ubicacion_experiencia
            from Articulo a
            join Articulo_Medida c on c.id_articulo = a.id
            left join Experiencia e on a.id_experiencia = e.id
            join Categoria_Marca f on a.id_categoria = f.id_categoria 
            where a.activo = 1 and f.id_marca = ".$id_marca." ".$complemento . " order by a.orden ");
        
        return $result->result();
    }
	public function sucursal_region($marca, $region, $kilos, $individual, $alcohol ){
		$sucursal =0;
        $result = $this->db->query("
							SELECT * 
							FROM sucursal 
							WHERE id_marca =".$marca." and individual = ".$individual." and kilos = ".$kilos." and alcohol = ".$alcohol ." and id_region = ".$region ." limit 1");
        foreach($result->result() as $column){
            $sucursal = $column->id;
		}
		return $sucursal;
    }
	public function get_extras($extra,$id_sucursal){
		$result = $this->db->query("
			select nombre,selected, precio
			from (
				SELECT a.nombre,a.selected
							,(case when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.") in (1)then ROUND(a.precio_nacional) 
				when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 2 then ROUND(a.precio_acapulco )
				when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 3 then ROUND(a.precio_cc )
				when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 4 then ROUND(a.precio_tijuana )
				when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 5 then ROUND(a.precio_pl )
				when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 6 then ROUND(a.precio_aeropuerto)
				 end ) precio
				 FROM Extra a where a.activo = 1 and FIND_IN_SET(a.id,'".$extra."')
				)t1 " );
            $response = array();                           
        return $result->result();
    }
	public function get_extras_idioma($extra,$id_sucursal,$id_idioma){
		$result = $this->db->query("
			select nombre,selected, precio
			from (
				SELECT a.nombre,a.selected
							,(case when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.") in (1)then ROUND(aa.precio_nacional) 
				when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 2 then ROUND(aa.precio_acapulco )
				when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 3 then ROUND(aa.precio_cc )
				when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 4 then ROUND(aa.precio_tijuana )
				when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 5 then ROUND(aa.precio_pl )
				when (select b.id_precio from Sucursal a join Region b on a.id_region = b.id where a.id = ".$id_sucursal.")= 6 then ROUND(aa.precio_aeropuerto)
				 end ) precio
				 FROM Extra_Idioma a 
				 join Extra aa on a.id_extra = aa.id
				 where a.activo = 1 and a.id_idioma = ".$id_idioma." and FIND_IN_SET(a.id_extra,'".$extra."')
				)t1 " );
            $response = array();                           
        return $result->result();
    }
}