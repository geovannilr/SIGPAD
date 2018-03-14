<?php
class CierreServiciosSociales_gc_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }
    function DatosServicioSocial($datos)
    {

        $sql="SELECT nombre_servicio_social FROM pss_servicio_social
              WHERE id_servicio_social='".$datos."'";
        $query = $this->db->query($sql) ;  
        foreach ($query->result_array() as $row)
        {
                $valor=$row['nombre_servicio_social'];

        }               
        return $valor;
        
    }     
    //////////////////////////////////////////////////// 
    //Convierte fecha de mysql a normal 
    //////////////////////////////////////////////////// 
    function cambiaf_a_normal($fecha){ 
    ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
    $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 
    return $lafecha; 
    } 
    //////////////////////////////////////////////////// 

    //Convierte fecha de normal a mysql 
    //////////////////////////////////////////////////// 

    function cambiaf_a_mysql($fecha){ 
    ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 

    return $lafecha; 
    } 

    /////////////////////////////////////////////////      
  function Update($update)
    {
   
        //Actualizacion a pss_detalle_expediente cuando ele estado sea Cerrado
        if ($update['estado']=='C'){
            $sql = "UPDATE pss_detalle_expediente 
                    set estado ='".$update['estado']."',
                        fecha_fin ='".$update['fecha_fin']."',
                        cierre_modalidad =1
                    where id_detalle_expediente='".$update['id_detalle_expediente']."'";
            $this->db->query($sql);
            return $this->db->affected_rows();
        }else{
            //Actualizacion a pss_detalle_expediente cuando ele estado sea Abandonado
            if ($update['estado']=='A'){
                $sql = "UPDATE pss_detalle_expediente 
                        set estado ='".$update['estado']."',
                            fecha_fin ='".$update['fecha_fin']."',
                            cierre_modalidad =0
                        where id_detalle_expediente='".$update['id_detalle_expediente']."'";
                $this->db->query($sql);
                return $this->db->affected_rows();
            }else{
                return 1;//para que sea valido y no muestre mensaje de eror
            }   
        }
     


    }  
    function ObtenerCorreoEstudiantePss($id_due){
        /*buscando el correo */
        $this->db->select('email');
        $this->db->from('gen_estudiante');
        $this->db->where('id_due',$id_due);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $email=$row['email'];
        }
        return $email;
    }   
    function ObtenerNombreApellidoEstudiantePss($id_due){
        /*buscando el Nombre Apellido del estudiante*/
        $sql = "SELECT CONCAT(nombre,', ',apellido) nombre_apellido FROM gen_estudiante
                WHERE id_due='".$id_due."'";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
                $nombre_apellido=$row['nombre_apellido'];
        }
        return $nombre_apellido;
    }             

}
?>


