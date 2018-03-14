<?php
class AproDeneCambioNombreTG_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    
    
  function Update($update)
    {

         
    	//Obteniendo el identificador del doumento a actualizar a partir del id_solicitud_academica
        $this->db->select('id_documento_pdg');
        $this->db->from('pdg_solicitud_academica');     
        $this->db->where('id_solicitud_academica',$update['id_solicitud_academica']);
        $query = $this->db->get();
        
        foreach ($query->result_array() as $row)
        {
                $id_documento_pdg=$row['id_documento_pdg'];

        }
   
        //Obteniendo el nombre_propuesto del tema a partir del id_solicitud_academica
        $this->db->select('nombre_propuesto');
        $this->db->from('pdg_solicitud_academica');     
        $this->db->where('id_solicitud_academica',$update['id_solicitud_academica']);
        $query = $this->db->get();
        
        foreach ($query->result_array() as $row)
        {
                $nombre_propuesto=$row['nombre_propuesto'];

        }

        //Obteniendo el id_detalle a partir del id_solicitud_academica
        $this->db->select('id_detalle_pdg');
        $this->db->from('pdg_solicitud_academica');     
        $this->db->where('id_solicitud_academica',$update['id_solicitud_academica']);
        $query = $this->db->get();
        
        foreach ($query->result_array() as $row)
        {
                $id_detalle_pdg=$row['id_detalle_pdg'];

        }

        //Obteniendo el id_equipo_tg a partir del id_detalle_pdg
        $this->db->select('id_equipo_tg');
        $this->db->from('pdg_detalle');     
        $this->db->where('id_detalle_pdg',$id_detalle_pdg);
        $query = $this->db->get();
        
        foreach ($query->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];

        }

        //Obteniendo el anio_tg a partir del id_detalle_pdg
        $this->db->select('anio_tg');
        $this->db->from('pdg_detalle');     
        $this->db->where('id_detalle_pdg',$id_detalle_pdg);
        $query = $this->db->get();
        
        foreach ($query->result_array() as $row)
        {
                $anio_tg=$row['anio_tg'];

        }


        //Obteniendo el ciclo_tg a partir del id_detalle_pdg
        $this->db->select('ciclo_tg');
        $this->db->from('pdg_detalle');     
        $this->db->where('id_detalle_pdg',$id_detalle_pdg);
        $query = $this->db->get();
        
        foreach ($query->result_array() as $row)
        {
                $ciclo_tg=$row['ciclo_tg'];

        }

		//Actualizando la pdg_documento
        $sql = "UPDATE pdg_documento set id_tipo_documento_pdg='".$update['id_tipo_documento']."',ruta='".$update['ruta']."'
                where id_documento_pdg='".$id_documento_pdg."'";		
		$this->db->query($sql);

        //Actualizacion a pdg_solicitud_academica
        $sql = "UPDATE pdg_solicitud_academica set estado='".$update['estado']."'
                where id_solicitud_academica='".$update['id_solicitud_academica']."'";
        $this->db->query($sql);

        //si el 
        //Actualizacion del nuevo nombre del tema
        
        if ($update['estado']=='A') {
                //Actualizacion de pdg_equipo_tg
                $sql = "UPDATE pdg_equipo_tg set tema='".$nombre_propuesto."'
                        where id_equipo_tg='".$id_equipo_tg."'
                        and anio_tg='".$anio_tg."'
                        and ciclo_tg='".$ciclo_tg."'
                        ";
                $this->db->query($sql);        
                //Actualizacion de pdg_nota_anteproyecto
                $sql = "UPDATE pdg_nota_anteproyecto set tema='".$update['estado']."'
                        where id_equipo_tg='".$id_equipo_tg."'
                        and anio_tg='".$anio_tg."'
                        and ciclo_tg='".$ciclo_tg."'";
                $this->db->query($sql);        
                //Actualizacion de pdg_nota_etapa1
                $sql = "UPDATE pdg_nota_etapa1 set tema='".$nombre_propuesto."'
                        where id_equipo_tg='".$id_equipo_tg."'
                        and anio_tg='".$anio_tg."'
                        and ciclo_tg='".$ciclo_tg."'";
                $this->db->query($sql);        
                //Actualizacion de pdg_nota_etapa2
                $sql = "UPDATE pdg_nota_etapa2 set tema='".$nombre_propuesto."'
                        where id_equipo_tg='".$id_equipo_tg."'
                        and anio_tg='".$anio_tg."'
                        and ciclo_tg='".$ciclo_tg."'";
                $this->db->query($sql);        
                //Actualizacion de pdg_nota_defensa_publica
                $sql = "UPDATE pdg_nota_defensa_publica set tema='".$nombre_propuesto."'
                        where id_equipo_tg='".$id_equipo_tg."'
                        and anio_tg='".$anio_tg."'
                        and ciclo_tg='".$ciclo_tg."'";
                $this->db->query($sql);        
                //Actualizacion de pdg_consolidado_notas
                $sql = "UPDATE pdg_consolidado_notas set tema='".$nombre_propuesto."'
                        where id_equipo_tg='".$id_equipo_tg."'
                        and anio_tg='".$anio_tg."'
                        and ciclo_tg='".$ciclo_tg."'";
                $this->db->query($sql);              
        }
      
  

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
   function ObtenerNuevoTema($id_solicitud_academica){
                /*buscando el nuevo tema */
       $sql ="SELECT  nombre_propuesto FROM pdg_solicitud_academica
                WHERE id_solicitud_academica='".$id_solicitud_academica."'";

        $query = $this->db->query($sql) ; 
        foreach ($query->result_array() as $row)
        {
                $nombre_propuesto=$row['nombre_propuesto'];
        }
        return $nombre_propuesto;
    }     
}

?>




