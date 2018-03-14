<?php
class AproDeneServicioSocial_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    function DatosContactoInstitucion($datos)
    {

        $sql="SELECT nombre_apellido_contacto_e_institucion FROM view_contacto_x_intitucion_pss
              WHERE id_contacto='".$datos."'";
        $query = $this->db->query($sql) ;  
        foreach ($query->result_array() as $row)
        {
                $valor=$row['nombre_apellido_contacto_e_institucion'];

        }               
        return $valor;
        
    }  
function Update($update)
    {
        //Actualizacion a pss_servicio_social
        $sql = "UPDATE pss_servicio_social 
                set estado_aprobacion ='".$update['estado_aprobacion']."'
                where id_servicio_social='".$update['id_servicio_social']."'";
        $this->db->query($sql);
        return $this->db->affected_rows();
    }    

    function ObtenerCorreoEstudiantePss()
    {  
        $sql="SELECT a.id_due,a.email FROM gen_estudiante a, es b, gen_tipo_estudiante c
            WHERE a.id_due=b.id_due
            AND b.id_tipo_estudiante=c.id_tipo_estudiante
            AND c.id_tipo_estudiante='PSS'
            AND a.fecha_remision IS NULL";
        $query = $this->db->query($sql);
        return $query->result_array();
    } 
}
?>


