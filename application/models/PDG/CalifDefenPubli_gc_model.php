<?php
class CalifDefenPubli_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    
    function EncontrarIdEquipoFiltrarDocente($id_docente){
        $sql = "SELECT DISTINCT id_equipo_tg FROM conforma
                WHERE id_due IN (SELECT DISTINCT id_due FROM asignado
                WHERE id_docente='".$id_docente."'
                AND id_proceso='PDG'
                AND id_cargo=2)";
        $query = $this->db->query($sql); 
        foreach ($query->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
        }
        if (empty($id_equipo_tg)){
          $id_equipo_tg=0;
        }        
        return $id_equipo_tg;
    }    
    function EncontrarIdEquipoFiltrarDocenteAse1($id_docente){
        $sql = "SELECT DISTINCT id_equipo_tg FROM conforma
                WHERE id_due IN (SELECT DISTINCT id_due FROM asignado
                WHERE id_docente='".$id_docente."'
                AND id_proceso='PDG'
                AND id_cargo=5)";
        $query = $this->db->query($sql); 
        foreach ($query->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
        }
        if (empty($id_equipo_tg)){
          $id_equipo_tg=0;
        }        
        return $id_equipo_tg;
    }    
        
    function EncontrarIdEquipoFiltrarDocenteAse2($id_docente){
        $sql = "SELECT DISTINCT id_equipo_tg FROM conforma
                WHERE id_due IN (SELECT DISTINCT id_due FROM asignado
                WHERE id_docente='".$id_docente."'
                AND id_proceso='PDG'
                AND id_cargo=6)";
        $query = $this->db->query($sql); 
        foreach ($query->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
        }
        if (empty($id_equipo_tg)){
          $id_equipo_tg=0;
        }        
        return $id_equipo_tg;
    }    
        




  function Update($update)
    {

 
		//Actualizando la pdg_nota_defensa_publica
        $sql = "UPDATE pdg_nota_defensa_publica 
        set nota_defensa_publica ='".$update['nota_defensa_publica']."'
        where id_nota_defensa_publica='".$update['id_nota_defensa_publica']."'";		
		$this->db->query($sql);

        //Actualizacion a pdg_solicitud_academica

        return $this->db->affected_rows();

    }    

}
?>

