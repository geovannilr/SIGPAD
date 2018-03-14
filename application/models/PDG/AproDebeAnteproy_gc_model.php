<?php
class AproDebeAnteproy_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    
function Update($update)
    {

    	//Obteniendo el id_detalle_pdg del doumento a actualizar a partir del id_equipo_tg y anio_tg
        $sql="select id_detalle_pdg from pdg_detalle       
        	  where id_equipo_tg='".$update['id_equipo_tg']."'
        	  and anio_tg='".$update['anio_tg']."'
              and ciclo_tg='".$update['ciclo_tg']."'";
        $query = $this->db->query($sql);
        
        foreach ($query->result_array() as $row)
        {
                $id_detalle_pdg=$row['id_detalle_pdg'];

        }
    
        /*verificando si el detalle_pdg para el antepropyecto modificado tiene un tipo de documento distinto al ADPAA*/
        $sql="SELECT COUNT(*) cantTipoDocAproDeneAnteproy FROM pdg_documento
        WHERE id_detalle_pdg='".$id_detalle_pdg."'
        AND id_tipo_documento_pdg IN (SELECT id_tipo_documento_pdg FROM pdg_tipo_documento WHERE siglas IN ('MAA','MDA'))";
        $query=$this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
                $cantTipoDocAproDeneAnteproy=$row['cantTipoDocAproDeneAnteproy'];

        } 

        if($cantTipoDocAproDeneAnteproy==0){
            /*Obteniendo el tipo documento asociado a Aprobacion/denegacion Pendiente Anteproyecto ADPAA
            11->Memorandum de Denegación de Cambio de Nombre    MDCN*/
            $sql="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
            WHERE siglas IN ('ADPAA')";  
            $query=$this->db->query($sql);
            foreach ($query->result_array() as $row)
            {
                    $id_tipo_documento_pdg_old=$row['id_tipo_documento_pdg'];

            }             
        }
        else{
            /*Obteniendo el tipo documento actual del  Anteproyecto */
            $sql="SELECT id_tipo_documento_pdg FROM pdg_documento
                  WHERE id_detalle_pdg= '".$id_detalle_pdg."' AND id_tipo_documento_pdg 
                  IN (SELECT id_tipo_documento_pdg FROM pdg_tipo_documento WHERE siglas IN ('MAA','MDA'))";  
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

        //Actualizacion a pdg_detalle
        /*$sql = "UPDATE pdg_detalle 
        		set estado_anteproyecto ='".$update['estado_anteproyecto']."',
        		anteproy_ingresado_x_equipo ='".$update['anteproy_ingresado_x_equipo']."',
        		entrega_copia_anteproy_doc_ase ='".$update['entrega_copia_anteproy_doc_ase']."',
        		entrega_copia_anteproy_tribu_eva1 ='".$update['entrega_copia_anteproy_tribu_eva1']."',
        		entrega_copia_anteproy_tribu_eva2 ='".$update['entrega_copia_anteproy_tribu_eva2']."'
                where id_detalle_pdg='".$id_detalle_pdg."'";*/
        $sql = "UPDATE pdg_detalle 
                set estado_anteproyecto ='".$update['estado_anteproyecto']."'
                where id_detalle_pdg='".$id_detalle_pdg."'";                
        $this->db->query($sql);
        return $this->db->affected_rows();
    }    

   function ObtenerCorreosIntegrantesEquipo($id_equipo_tg,$anio_tg,$ciclo_tg){
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

