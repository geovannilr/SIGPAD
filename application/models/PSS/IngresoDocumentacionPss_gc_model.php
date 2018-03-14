<?php
class IngresoDocumentacionPss_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    /*function DatosContactoInstitucion($datos)
    {

        $sql="SELECT nombre_apellido_contacto_e_institucion FROM view_contacto_x_intitucion_pss
              WHERE id_contacto='".$datos."'";
        $query = $this->db->query($sql) ;  
        foreach ($query->result_array() as $row)
        {
                $valor=$row['nombre_apellido_contacto_e_institucion'];

        }               
        return $valor;
        
    }  */
function Update($update)
    {
        //Encontrando el id_due a partir del id_detalle_expediente
          $sql="SELECT a.id_due FROM gen_estudiante a, pss_detalle_expediente b
                WHERE a.id_due=b.id_due
                AND b.id_detalle_expediente='".$update['id_detalle_expediente']."'";
                $query = $this->db->query($sql) ;           
                foreach ($query->result_array() as $row)
                {
                        $id_due=$row['id_due'];

                }                       
        //Actualizacion a pss_detalle_expediente 
        $sql = "UPDATE pss_detalle_expediente 
                set horas_prestadas ='".$update['horas_prestadas']."',
                    perfil_proyecto ='".$update['perfil_proyecto']."',
                    plan_trabajo ='".$update['perfil_proyecto']."',
                    informe_parcial ='".$update['informe_parcial']."',
                    informe_final ='".$update['informe_final']."',
                    memoria ='".$update['memoria']."',
                    control_actividades ='".$update['control_actividades']."',
                    carta_finalizacion_horas_sociales ='".$update['carta_finalizacion_horas_sociales']."'
                where id_detalle_expediente='".$update['id_detalle_expediente']."'";
        $this->db->query($sql);

        $sql = "UPDATE gen_estudiante 
                set lugar_trabajo ='".$update['lugar_trabajo']."',
                    telefono_trabajo ='".$update['telefono_trabajo']."'
                where id_due='".$id_due."'";
        $this->db->query($sql);

        return $this->db->affected_rows();


    }    



}
?>


