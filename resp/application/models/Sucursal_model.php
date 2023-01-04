<?php
class Sucursal_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get($id_marca)
	{
		$complemento ="";
		if($id_marca > 0 )
			$complemento =' and  id_marca = '.$id_marca;
		$query = $this->db->query("select * from Sucursal where 1=1 ".$complemento);
		return $query->result();
	}
	public function get2($id_marca,$latitud,$longitud)
	{
		$complemento ="";
		$consulta="";
		if($id_marca > 0 )
			$complemento =' and  id_marca = '.$id_marca;
		$consulta = "select * from Sucursal where 1=1 ".$complemento;
		if($latitud != "" && $longitud!=""){
		    $consulta= "SELECT id, nombre, ( 6371 * ACOS( 
                                 COS( RADIANS('".$latitud."') ) 
                                 * COS(RADIANS( latitud ) ) 
                                 * COS(RADIANS( longitud ) 
                                 - RADIANS('".$longitud."') ) 
                                 + SIN( RADIANS('".$latitud."') ) 
                                 * SIN(RADIANS( latitud ) ) 
                                )
                   ) AS distance ,latitud,longitud
                    FROM Sucursal where 1=1 ".$complemento."
                    ORDER BY distance ASC";
		}
		$query = $this->db->query($consulta);
		return $query->result();
	}
    public function get_estados()
	{
		$query = $this->db->query("select * from Estados ");
		return $query->result();
	}
	public function get_estados_unidades($id_marca)
	{
		$query = $this->db->query("select * from(
										select id,nombre nombre_estado,abrev,(select count(*) from Sucursal b where b.id_estado = a.id and b.id_marca = ".$id_marca." and activo = 1 ) unidades from Estados a
								) t0 where unidades > 0 order by nombre_estado");
		return $query->result();
	}
	
	public function obtener_activas($id_marca)
	{
		$complemento =" and activo = 1 ";
		if($id_marca > 0 )
			$complemento =' and  id_marca = '.$id_marca;
		$query = $this->db->query("select * from Sucursal where 1=1 and activo = 1 ".$complemento ." order by nombre");
		return $query->result();
	}
	public function get_alcohol()
	{
		$query = $this->db->query("select * from Region_alcohol ");
		return $query->result();
	}
	public function sucursales_estados($id_marca,$latitud,$longitud)
	{
	    $complemento ="";
		if($id_marca > 0 )
		    $complemento =' and  a.id_marca = '.$id_marca;
        
		$consulta="";
		$consulta= "SELECT 
                    a.id id_sucursal,a.nombre nombre_sucursal,a.id_estado, b.nombre nombre_estado,b.abrev,a.latitud,a.longitud
        FROM Sucursal a
        join Estados b on a.id_estado = b.id 
        where a.activo = 1 ".$complemento. " order by a.nombre";
        if($latitud != "" && $longitud!=""){
		    $consulta= "SELECT a.id id_sucursal,a.nombre nombre_sucursal,a.id_estado,
		                        ( 6371 * ACOS( 
                                 COS( RADIANS('".$latitud."') ) 
                                 * COS(RADIANS( latitud ) ) 
                                 * COS(RADIANS( longitud ) 
                                 - RADIANS('".$longitud."') ) 
                                 + SIN( RADIANS('".$latitud."') ) 
                                 * SIN(RADIANS( latitud ) ) 
                                )
                   ) AS distance , b.nombre nombre_estado,b.abrev,a.latitud,a.longitud
                    FROM Sucursal a 
                    join Estados b on a.id_estado = b.id 
                    where 1=1 and a.activo = 1 ".$complemento."
                    ORDER BY distance ASC";
		}
		$query = $this->db->query($consulta);
		return $query->result();
	}
	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Sucursal', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function add($data){

		if($this->db->insert('Sucursal', $data)){
			return true;
		}else{
			return false;
		}

	}
	public function addSolicitud($data){

		if($this->db->insert('Solicitud', $data)){
			return true;
		}else{
			return false;
		}

	}
	public function getById($id){
        $result = $this->db->query("select * from Sucursal where id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["longitud"] = $column->longitud;
			$response["latitud"] = $column->latitud;
			$response["activo"] = $column->activo;
			$response["marca"] = $column->id_marca;
			$response["region"] = $column->id_region;
			$response["id_estado"] = $column->id_estado;
			$response["alcohol"] = $column->alcohol;
			$response["kilos"] = $column->kilos;
		}
        return $response;
    }
    public function eliminar($id){
        $this->db->where('id', $id);
        if($this->db->delete('Sucursal')){
            return true;
        }else{
            return false;
        }
    }
}