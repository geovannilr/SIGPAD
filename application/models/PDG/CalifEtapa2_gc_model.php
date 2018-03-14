<?php
class CalifEtapa2_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }

    function EncontrarIdEquipoFiltrarDocente($id_docente){

        $sql="SELECT DISTINCT id_equipo_tg FROM pdg_nota_etapa2
        WHERE id_equipo_tg IN (
        SELECT DISTINCT id_equipo_tg FROM conforma
        WHERE id_due IN (SELECT DISTINCT id_due FROM asignado
        WHERE id_docente='".$id_docente."'
        AND id_proceso='PDG'
        AND id_cargo=2))
        AND estado_nota='A'";

        $query = $this->db->query($sql); 
        return $query;

    }     
    
    
function EncuentraCriterio($var)
    {

       //Encontrando el descripcion de criterio
        $sql ="SELECT criterio FROM pdg_criterio
			  WHERE id_criterio=".$this->db->escape($var['cod_criterio'])."";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $criterio=$row['criterio'];

        };

        return $criterio;
    }

  function Update($update)
    {

        //Calculo de la nota de exposicion de la etapa 2
      $suma_nota_criterios_exposicion=($update['nota_criterio1']*0.10)+($update['nota_criterio2']*0.10)+
      ($update['nota_criterio3']*0.20)+($update['nota_criterio4']*0.20)+($update['nota_criterio5']*0.20)+
      ($update['nota_criterio6']*0.20);

      //$nota_exposicion=$suma_nota_criterios_exposicion/6;
      $nota_exposicion=$suma_nota_criterios_exposicion;

		  //Calculo de la nota de etapa 2

   		$nota_etapa2=round((($update['nota_documento'])*0.60 + ($nota_exposicion)*0.40),2);

		//Actualizando la pdg_nota_etapa2
        $sql = "UPDATE pdg_nota_etapa2 set nota_criterio1='".$update['nota_criterio1']."',
        nota_criterio1='".$update['nota_criterio1']."',nota_criterio2='".$update['nota_criterio2']."',
        nota_criterio3='".$update['nota_criterio3']."',nota_criterio4='".$update['nota_criterio4']."',
        nota_criterio5='".$update['nota_criterio5']."',nota_criterio6='".$update['nota_criterio6']."',
    		nota_documento='".$update['nota_documento']."',nota_exposicion='".$nota_exposicion."',
    		nota_etapa2='".$nota_etapa2."'
        where id_nota_etapa2='".$update['id_nota_etapa2']."'";		
		$this->db->query($sql);

        //Actualizacion a pdg_solicitud_academica

        return $this->db->affected_rows();

    }    

}
?>

