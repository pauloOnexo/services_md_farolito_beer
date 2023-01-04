<?php

class Registros_model extends CI_Model{

    private $id_table = "id";

    private $name_table = "Registros";

	public function __construct(){
		parent::__construct();
	}
	public function add($data){

		if($this->db->insert('Registros', $data)){
			return true;
		}else{
			return false;
		}
	}
	public function get($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f,$id_marca){
		$complemento ='';
		if($id_marca > 0)
			$complemento = " and c.id =".$id_marca."  ";
		
       $query = $this->db->query("SELECT c.nombre nombre_marca,b.nombre nombre_sucursal
	    ,(select count(aa.ip) from registros aa where aa.hora_solicitud 
		between '".$hora_solicitud_i."' and '".$hora_solicitud_f."' and aa.fecha_solicitud = '".$fecha_solicitud."' and a.id_sucursal = aa.id_sucursal ) cantidad
		FROM `registros` a 
		join Sucursal b on a.id_sucursal = b.id
		join Marca c on b.id_marca = c.id
		where a.hora_solicitud 
		between '".$hora_solicitud_i."' and '".$hora_solicitud_f."' and fecha_solicitud = '".$fecha_solicitud."' 
		".$complemento." group by b.nombre,c.nombre
		");
		return $query->result();
    }
	public function get_ip($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f){

       $query = $this->db->query("SELECT a.ip,count(*) cantidad
		FROM `registros` a 
		where hora_solicitud 
		between '".$hora_solicitud_i."' and '".$hora_solicitud_f."' and fecha_solicitud = '".$fecha_solicitud."'
		group by a.ip
		");
		return $query->result();
    }
	public function get_marca($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f){

       $query = $this->db->query("SELECT c.nombre,count(*) cantidad
		FROM `registros` a 
		join Sucursal b on a.id_sucursal = b.id
		join Marca c on b.id_marca = c.id
		where hora_solicitud 
		between '".$hora_solicitud_i."' and '".$hora_solicitud_f."' and fecha_solicitud = '".$fecha_solicitud."'
		group by c.nombre
		");
		return $query->result();
    }
	public function get_unidad_ip($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f,$id_marca){
		$complemento ='';
		if($id_marca > 0)
			$complemento = " and c.id =".$id_marca."  ";
			
       $query = $this->db->query("SELECT c.nombre nombre_marca,b.nombre nombre_sucursal
	    ,a.ip,count(*) cantidad
		FROM `registros` a 
		join Sucursal b on a.id_sucursal = b.id
		join Marca c on b.id_marca = c.id
		where hora_solicitud 
		between '".$hora_solicitud_i."' and '".$hora_solicitud_f."' and fecha_solicitud = '".$fecha_solicitud."'
		".$complemento." group by b.nombre,c.nombre,a.ip
		");
		return $query->result();
    }
	public function get_unidad_marca($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f,$id_marca){
		$complemento ='';
		if($id_marca > 0)
			$complemento = " and c.id =".$id_marca."  ";
			
       $query = $this->db->query("SELECT c.nombre nombre_marca,b.nombre nombre_sucursal
	    ,(select count(*) from 
		(select distinct aa.ip,aa.id_sucursal from registros aa where aa.fecha_solicitud = '".$fecha_solicitud."' ) 
		t1 where t1.id_sucursal = a.id_sucursal) cantidad
		FROM `registros` a 
		join Sucursal b on a.id_sucursal = b.id
		join Marca c on b.id_marca = c.id
		where hora_solicitud 
		between '".$hora_solicitud_i."' and '".$hora_solicitud_f."' and fecha_solicitud = '".$fecha_solicitud."'
		".$complemento." group by b.nombre,c.nombre
		");
		return $query->result();
    }
	public function get_ip_marca($fecha_solicitud,$hora_solicitud_i,$hora_solicitud_f,$id_marca){
		$complemento ='';
		if($id_marca > 0)
			$complemento = " and c.id =".$id_marca."  ";
			
       $query = $this->db->query("select * from (SELECT c.nombre nombre_marca,c.id marca
	    ,(select count(*) from 
		(select distinct aa.ip,cc.id from registros aa join Sucursal bb on aa.id_sucursal = bb.id
		join Marca cc on bb.id_marca = cc.id
			where aa.hora_solicitud 
		between '".$hora_solicitud_i."' and '".$hora_solicitud_f."' and aa.fecha_solicitud = '".$fecha_solicitud."' ) 
		t1 where t1.id = c.id) cantidad
		FROM `registros` a 
		join Sucursal b on a.id_sucursal = b.id
		join Marca c on b.id_marca = c.id
		where hora_solicitud 
		between '".$hora_solicitud_i."' and '".$hora_solicitud_f."' and fecha_solicitud = '".$fecha_solicitud."'
		".$complemento." group by c.nombre,c.id order ) order by cantidad desc
		");
		return $query->result();
    }
}