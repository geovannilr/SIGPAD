<?php
class OficializacionPss_gc_model extends CI_Model{

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
    function EncontrarModalidadSS($datos)
    {
        /*encontrar la modalidad del un determinado expediente de serviicio social*/
        $sql="SELECT b.id_modalidad  FROM pss_detalle_expediente a, pss_servicio_social b
                WHERE a.id_servicio_social=b.id_servicio_social
                AND a.id_detalle_expediente='".$datos."'";
        $query = $this->db->query($sql) ;  
        foreach ($query->result_array() as $row)
        {
                $valor=$row['id_modalidad'];

        }               
        return $valor;
        
    }      
function Update($update)
    {
        /*Encontrando la fecha actal desde la BD*/
        //Se comentario porque la fecha de inicio se debe poner cuando se oficializa el servicio social
        $sql ="SELECT CURDATE() fecha";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $fecha_inicio=$row['fecha'];

        };   

        if ($update['estado']=='O'){
            //Actualizacion a pss_detalle_expediente cuando se cambia a estado Oficializado
            $sql = "UPDATE pss_detalle_expediente 
                    set estado ='".$update['estado']."',
                        fecha_inicio ='".$fecha_inicio."',
                        oficializacion =1,
                        encabezado_oficializacion ='".$update['encabezado_oficializacion']."'
                    where id_detalle_expediente='".$update['id_detalle_expediente']."'";
            $this->db->query($sql);
            return $this->db->affected_rows();
        }else{
            //Actualizacion a pss_detalle_expediente cuando se cambia a estado diferente a Oficializado
            $sql = "UPDATE pss_detalle_expediente 
                    set estado ='".$update['estado']."'
                    where id_detalle_expediente='".$update['id_detalle_expediente']."'";
            $this->db->query($sql);
            return $this->db->affected_rows();            
        }

    }    



}
?>


