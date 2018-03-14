<?php
class AproDenePerfil_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
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
        
function Update($update)
    {

        //Obteniendo el id_detalle_pdg del doumento a actualizar a partir del id_equipo_tg y anio_tg y ciclo_tg
        $sql="select id_detalle_pdg from pdg_detalle       
              where id_equipo_tg='".$update['id_equipo_tg']."'
              and anio_tg='".$update['anio_tg']."'
              and ciclo_tg='".$update['ciclo_tg']."'";
        $query = $this->db->query($sql);
        
        foreach ($query->result_array() as $row)
        {
                $id_detalle_pdg=$row['id_detalle_pdg'];

        }
    
        /*verificando si el detalle_pdg para el perfil modificado tiene un tipo de documento distinto al ADPPP*/
        $sql="SELECT COUNT(*) cantTipoDocAproDenePerfil FROM pdg_documento
        WHERE id_detalle_pdg='".$id_detalle_pdg."'
        AND id_tipo_documento_pdg IN (SELECT id_tipo_documento_pdg FROM pdg_tipo_documento WHERE siglas IN ('MAP','MDP'))";
        $query=$this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
                $cantTipoDocAproDenePerfil=$row['cantTipoDocAproDenePerfil'];

        } 

        if($cantTipoDocAproDenePerfil==0){
            /*Obteniendo el tipo documento asociado a Aprobacion/denegacion Pendiente Perfil ADPPP
            16->Aprobacion/denegacion Pendiente Perfil   ADPPP*/
            $sql="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
            WHERE siglas IN ('ADPPP')";  
            $query=$this->db->query($sql);
            foreach ($query->result_array() as $row)
            {
                    $id_tipo_documento_pdg_old=$row['id_tipo_documento_pdg'];

            }             
        }
        else{
            /*Obteniendo el tipo documento actual del  Perfil */
            $sql="SELECT id_tipo_documento_pdg FROM pdg_documento
                  WHERE id_detalle_pdg= '".$id_detalle_pdg."' AND id_tipo_documento_pdg 
                  IN (SELECT id_tipo_documento_pdg FROM pdg_tipo_documento WHERE siglas IN ('MAP','MDP'))";  
            $query=$this->db->query($sql);
            foreach ($query->result_array() as $row)
            {
                    $id_tipo_documento_pdg_old=$row['id_tipo_documento_pdg'];

            }   
        }
        //Actualizando la pdg_documento
        $sql = "UPDATE pdg_documento 
                set id_tipo_documento_pdg='".$update['id_tipo_documento']."',
                ruta='".$update['ruta']."'
                where id_detalle_pdg='".$id_detalle_pdg."'
                and id_tipo_documento_pdg='".$id_tipo_documento_pdg_old."'";        
        $this->db->query($sql);
        $primeraActualizacion=$this->db->affected_rows();
        //Actualizacion a pdg_detalle
        $sql = "UPDATE pdg_detalle 
                set estado_perfil ='".$update['estado_perfil']."',
                observaciones_perfil ='".$update['observaciones_perfil']."',
                perfil_ingresado_x_equipo ='".$update['perfil_ingresado_x_equipo']."',
                entrega_copia_perfil ='".$update['entrega_copia_perfil']."',
                numero_acta_perfil ='".$update['numero_acta_perfil']."',
                punto_perfil ='".$update['punto_perfil']."',
                acuerdo_perfil ='".$update['acuerdo_perfil']."',
                fecha_aprobacion_perfil ='".$update['fecha_aprobacion_perfil']."'
                where id_detalle_pdg='".$id_detalle_pdg."'";
                $segundaActualizacion=$this->db->affected_rows();
        $this->db->query($sql);
        return $primeraActualizacion+$segundaActualizacion;
    }    
   
    function ObtenerCorreosIntegrantesEquipo($id_equipo_tg,$anio_tg,$ciclo_tg){
        /*$sql="INSERT INTO tabla(valor) VALUES('".$id_equipo_tg."')";
        $this->db->query($sql); 
        $sql="INSERT INTO tabla(valor) VALUES('".$anio_tg."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$ciclo_tg."')";
        $this->db->query($sql);   */

                /*buscando el correo */
        $sql = "SELECT b.id_due,c.email FROM pdg_equipo_tg a, conforma b,gen_estudiante c
                WHERE  a.id_equipo_tg=b.id_equipo_tg
                AND b.id_due=c.id_due
                AND a.id_equipo_tg='".$id_equipo_tg."'
                AND a.anio_tg=".$anio_tg."
                AND a.ciclo_tg=".$ciclo_tg."";
        $query = $this->db->query($sql);
        return $query->result_array();
    }  
}
?>

