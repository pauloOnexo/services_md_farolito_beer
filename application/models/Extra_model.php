<?php
class Extra_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get()
	{
		$query = $this->db->query("select * from Extra where 1=1 ");
		return $query->result();
	}

	public function get_activos()
	{
		$query = $this->db->query("select * from Extra where activo = 1 ");
		return $query->result();
	}
	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Extra', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function update_idioma($data,$id_extra,$id_idioma)
	{
		$registros = $this->db->query("SELECT * from Extra_Idioma where id_extra = ".$id_extra. ' and id_idioma='.$id_idioma);
        $existe = 0;
        foreach($registros->result() as $column){
            $existe = $column->id;
		}
		if ($existe > 0)
		{
			$this->db->where('id_extra', $id_extra);
			$this->db->where('id_idioma', $id_idioma);
			if($this->db->update('Extra_Idioma',$data)){
					return true;
			}else{
				return false;
			}
		}
		else{
			if($this->db->insert('Extra_Idioma', $data)){
					return true;
			}else{
				return false;
			}
		
		}
	}
	public function add($data){

		if($this->db->insert('Extra', $data)){
			return true;
		}else{
			return false;
		}

	}
	public function getById($id){
        $result = $this->db->query("select * from Extra where id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["activo"] = $column->activo;
			$response["descripcion"] = $column->descripcion;
			$response["precio_nacional"] = $column->precio_nacional;
			$response["precio_acapulco"] = $column->precio_acapulco;
			$response["precio_cc"] = $column->precio_cc;
			$response["precio_tijuana"] = $column->precio_tijuana;
			$response["precio_pl"] = $column->precio_pl;
			$response["precio_aeropuerto"] = $column->precio_aeropuerto;
		}
        return $response;
    }
	public function getByIdIdioma($id,$id_idioma){
        $result = $this->db->query("select * from Extra_Idioma where id_extra = ".$id." and id_idioma=".$id_idioma);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $id;
			$response["nombre"] = $column->nombre;
			$response["activo"] = $column->activo;
			$response["descripcion"] = $column->descripcion;
			$response["precio_nacional"] = $column->precio_nacional;
			$response["precio_acapulco"] = $column->precio_acapulco;
			$response["precio_cc"] = $column->precio_cc;
			$response["precio_tijuana"] = $column->precio_tijuana;
			$response["precio_pl"] = $column->precio_pl;
			$response["precio_aeropuerto"] = $column->precio_aeropuerto;
		}
        return $response;
    }
//comentario
    public function eliminar($id){
        $result = $this->db->query("select * from Extra where id = ".$id);
        $this->db->where('id', $id);
        if($this->db->delete('Extra')){
            return true;
        }else{
            return false;
        }
    }
}