<?php
class RegisAnteproy_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    
    function EncontrarIdEquipoFiltrar($id_due){
        $sql = "SELECT DISTINCT id_equipo_tg FROM conforma
                WHERE id_due='".$id_due."'";
        $query = $this->db->query($sql);                    
        foreach ($query->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
        }
        if (empty($id_equipo_tg)){
          $id_equipo_tg=0;
        }          
        return $id_equipo_tg;
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
 
        //Mapeando si fue ingresado por el equipo
        $anteproy_ingresado_x_equipo=1;
        //Obteniendo el id_detalle_pdg a traves del identificador de equipo y anio_tg y ciclo_tg
        $sql="SELECT id_detalle_pdg FROM pdg_detalle
            WHERE id_equipo_tg='".$update['id_equipo_tg']."'
            AND anio_tg='".$update['anio_tg']."'
            AND ciclo_tg='".$update['ciclo_tg']."'";
        $query = $this->db->query($sql) ;           
        foreach ($query->result_array() as $row)
        {
                $id_detalle_pdg=$row['id_detalle_pdg'];

        }   
        //Obteniendo el identificador de tipo de documento asociado al anteproyecto    
        $sql="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
              WHERE siglas IN ('A')";
        $query = $this->db->query($sql) ;           
        foreach ($query->result_array() as $row)
        {
                $id_tipo_documento_pdg=$row['id_tipo_documento_pdg'];

        }           
        /*$sql="insert into nuevo(valor) values('".$update['id_equipo_tg']."')";
        $this->db->query($sql);
        $sql="insert into nuevo(valor) values('".$update['anio_tg']."')";
        $this->db->query($sql);   
        $sql="insert into nuevo(valor) values('".$update['ciclo_tg']."')";
        $this->db->query($sql);                 
        $sql="insert into nuevo(valor) values('".$id_detalle_pdg."')";
        $this->db->query($sql);
        $sql="insert into nuevo(valor) values('".$update['ruta']."')";
        $this->db->query($sql);
        $sql="insert into nuevo(valor) values('".$id_tipo_documento_pdg."')";
        $this->db->query($sql);*/

        //Actualizando las fechas en pdg_detalle
        $sql="update pdg_detalle 
              set fecha_eva_anteproyecto='".$update['fecha_eva_anteproyecto']."',
              fecha_eva_etapa1='".$update['fecha_eva_etapa1']."',
              fecha_eva_etapa2='".$update['fecha_eva_etapa2']."',
              fecha_eva_publica='".$update['fecha_eva_publica']."',
              anteproy_ingresado_x_equipo='".$anteproy_ingresado_x_equipo."'
              WHERE id_detalle_pdg='".$id_detalle_pdg."'";
        $this->db->query($sql);
        //Actualizando la pdg_documento
        $sql = "UPDATE pdg_documento set ruta='".$update['ruta']."'
                where id_tipo_documento_pdg='".$id_tipo_documento_pdg."'
                and id_detalle_pdg='".$id_detalle_pdg."'";      
        $this->db->query($sql);
        return $this->db->affected_rows();

    }      
}
?>


