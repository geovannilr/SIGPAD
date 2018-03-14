<?php
class CierreExpedientePss_gc_model extends CI_Model{

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
            $sql = "UPDATE gen_estudiante 
                    set fecha_remision ='".$update['fecha_remision']."',
                        remision =1,
                        observaciones_exp_pss='".$update['observaciones_exp_pss']."'
                    where id_due='".$update['id_due']."'";
            $this->db->query($sql);
            return $this->db->affected_rows();

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
}
?>


