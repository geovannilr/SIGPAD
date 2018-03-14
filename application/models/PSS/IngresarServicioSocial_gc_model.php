<?php
class IngresarServicioSocial_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }

    function Create($insert)
    {
        //Generando el id_servicio_social
        $sql ="SELECT CASE WHEN MAX(CAST(id_servicio_social AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_servicio_social AS UNSIGNED))+1 END AS maximo
              FROM pss_servicio_social";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_servicio_social=$row['maximo'];
        };            
          
        //Inserción a pss_servicio_social
        $sql = "INSERT INTO pss_servicio_social(id_servicio_social,id_contacto,id_modalidad,nombre_servicio_social,cantidad_estudiante,disponibilidad,estado,objetivo,importancia,presupuesto,logro,localidad_proyecto,beneficiario_directo,beneficiario_indirecto,descripcion,nombre_contacto_ss,email_contacto_ss)
        VALUES ('".$id_servicio_social."',
                ".$this->db->escape($insert['id_contacto']).",
                ".$this->db->escape($insert['id_modalidad']).",
                ".$this->db->escape($insert['nombre_servicio_social']).",
                ".$this->db->escape($insert['cantidad_estudiante']).",
                ".$this->db->escape($insert['cantidad_estudiante']).",
                'D',
                ".$this->db->escape($insert['objetivo']).",
                ".$this->db->escape($insert['importancia']).",
                ".$this->db->escape($insert['presupuesto']).",
                ".$this->db->escape($insert['logro']).",
                ".$this->db->escape($insert['localidad_proyecto']).",
                ".$this->db->escape($insert['beneficiario_directo']).",
                ".$this->db->escape($insert['beneficiario_indirecto']).",
                ".$this->db->escape($insert['descripcion']).",
                ".$this->db->escape($insert['nombre_contacto_ss']).",
                ".$this->db->escape($insert['email_contacto_ss'])."
                )";
        $this->db->query($sql);
        $comprobar=$this->db->affected_rows();

                 
    }    
}
?>


