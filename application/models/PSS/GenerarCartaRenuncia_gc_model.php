<?php
class GenerarCartaRenuncia_gc_model extends CI_Model{

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

       
    //Actualizacion a pss_detalle_expediente 
    $sql = "UPDATE pss_detalle_expediente 
            set encabezado_carta_renuncia ='".$update['encabezado_carta_renuncia']."',
                motivos_renuncia ='".$update['motivos_renuncia']."'
            where id_detalle_expediente='".$update['id_detalle_expediente']."'";
    $this->db->query($sql);
    return $this->db->affected_rows();
     
    }    



}
?>


