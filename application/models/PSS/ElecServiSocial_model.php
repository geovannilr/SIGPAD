<?php
class ElecServiSocial_model extends CI_Model{

    function __construct(){
    	   parent::__construct();
        $this->load->database();
    }
    function Get_Candidatos()
    {
        $query = $this->db->query("SELECT a.id_servicio_social,d.nombre AS institucion,b.modalidad,
                                    a.nombre_servicio_social,a.cantidad_estudiante,a.disponibilidad,a.descripcion,
                                    a.nombre_contacto_ss,a.email_contacto_ss
                                    FROM pss_servicio_social a, pss_modalidad b,pss_contacto c,pss_institucion d
                                    WHERE  a.id_modalidad=b.id_modalidad
                                    AND a.id_contacto=c.id_contacto
                                    AND c.id_institucion=d.id_institucion
                                    AND a.estado_aprobacion='A'
                                    AND a.estado IN ('D')
                                    AND a.disponibilidad>0;
                                    ");


        return $query->result();
    } 

    function Obtener_Costo_Hora()
    {
            $sql ="SELECT valor FROM cat_parametro_general
                    WHERE parametro='PVHSS'";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $costo_hora=$row['valor'];

            };         
            return $costo_hora;

    }
    function Actualizar_Disponibilidad_Estudiante_x_SS($id_servicio_social)
    {
           


            //Disminucion de estudiante por servicio social 
            $sql = "UPDATE pss_servicio_social set disponibilidad=disponibilidad-1
                    WHERE id_servicio_social='".$id_servicio_social."'";
            $this->db->query($sql);
            

            //Validar si la disponibilidad es igual a cero

            $sql ="SELECT disponibilidad FROM pss_servicio_social
                   WHERE id_servicio_social='".$id_servicio_social."'";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $disponibilidad=$row['disponibilidad'];

            };         

            //Si la disponibilidadad  es igual a cero cambiar estado a valor "L" de Lleno
            if($disponibilidad==0){
                $sql = "UPDATE pss_servicio_social SET estado='L'
                        WHERE id_servicio_social='".$id_servicio_social."'";
                $this->db->query($sql);
            }


            return $this->db->affected_rows();

    }
    function Obtener_Numero_Detalle()
    {
     
            //Encontrando el´proximo id_detalle_expediente  
            $sql ="SELECT CASE WHEN MAX(CAST(id_detalle_expediente AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_detalle_expediente AS UNSIGNED))+1 END AS maximo
                    FROM pss_detalle_expediente";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_detalle_expediente=$row['maximo'];

            };           
            return $id_detalle_expediente;

    }     
    function Crear_Expediente($id_detalle_expediente_new,$id_servicio_social,$id_due,$costo_hora)
    {
            
            /*Encontrando la fecha actal desde la BD*/
            //Se comentario porque la fecha de inicio se debe poner cuando se oficializa el servicio social
            /*$sql ="SELECT CURDATE() fecha";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $fecha_inicio=$row['fecha'];

            };   */          

            $sql = "INSERT INTO pss_detalle_expediente (id_detalle_expediente,id_servicio_social,id_due,estado,costo_hora)
                    VALUES('".$id_detalle_expediente_new."',
                        '".$id_servicio_social."',
                        '".$id_due."',
                        'I',
                        '".$costo_hora."'
                        )";
            $this->db->query($sql);
            return $this->db->affected_rows();


    }
}

?>

