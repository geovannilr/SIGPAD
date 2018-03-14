<?php
class AperturaExpedientePss_gc_model extends CI_Model{

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

    /////////////////////////////////////////////////
        
   function Update($update)
    {
        //Actualizacion a gen_estudiante
        $sql = "UPDATE  gen_estudiante a, es b,gen_tipo_estudiante c 
                set dui ='".$update['dui']."',
                apertura_expediente_pss ='".$update['apertura_expediente_pss']."',
                carta_aptitud_pss ='".$update['carta_aptitud_pss']."'
                WHERE a.id_due=b.id_due
                AND b.id_tipo_estudiante=c.id_tipo_estudiante
                AND c.id_tipo_estudiante='PSS'
                AND a.apertura_expediente_pss IS NULL
                AND a.id_due='".$update['id_due']."'";

        $this->db->query($sql);
        return $this->db->affected_rows();
    }    



}
?>


