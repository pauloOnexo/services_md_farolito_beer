<?php
class Cliente_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	public function get()
	{
		$query = $this->db->query("select * from Cliente where 1=1 ");
		return $query->result();
	}

    public function categorias($id_sucursal)
	{
		$query = $this->db->query("select a.nombre,a.id, a.ubicacion
            from Categoria a
            join Categoria_Marca b on a.id = b.id_categoria
            join Sucursal c on b.id_marca = c.id_marca
            where a.activo = 1 and c.id = ".$id_sucursal."
            group by  a.nombre,a.id, a.ubicacion");
		return $query->result();
	}
	public function update($data,$id)
	{
		 $this->db->where('id', $id);
        if($this->db->update('Cliente', $data)){
            return true;
        }else{
            return false;
        }
	}
	public function add($data){

		if($this->db->insert('Cliente', $data)){
			return true;
		}else{
			return false;
		}

	}
	public function getById($id){
        $result = $this->db->query("select * from Cliente where id = ".$id);
        $response = array();
        foreach($result->result() as $column){
            $response["id"] = $column->id;
			$response["nombre"] = $column->nombre;
			$response["correo"] = $column->correo;
			$response["contrasenha"] = $column->contrasenha;
			$response["telefono"] = $column->telefono;
		}
        return $response;
    }
	public function getByEmail($correo){
        $result = $this->db->query("select * from Cliente where correo = '".$correo."'");
        $response = array();
        foreach($result->result() as $column){
			$response["nombre"] = $column->nombre;
			$response["correo"] = $column->correo;
			$response["contrasenha"] = $column->contrasenha;
			$response["telefono"] = $column->telefono;
		}
        return $response;
    }
	public function login($nombre, $contrasena){
		$query = $this->db->get_where('Cliente', array('correo' => $nombre,'contrasenha'=> $contrasena));
		return $query->result();
	}
}