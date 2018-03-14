<?php
class IngresoSoliCambioNombreTG_gc_model extends CI_Model{

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
  function Create($insert)
    {
        //Mapeando si fue ingresado por el equipo
        $ingresado_x_equipo=1;

        //Generando el id_solicitud_academica
        $sql ="SELECT CASE WHEN MAX(CAST(id_solicitud_academica AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_solicitud_academica AS UNSIGNED))+1 END AS maximo
              FROM pdg_solicitud_academica";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_solicitud_academica=$row['maximo'];

        };            

        //Encontrando el id_detalle_pdg  apartir de id_equipo_tg y anio_tg y ciclo_tg
        $sql ="SELECT id_detalle_pdg FROM pdg_detalle
        WHERE id_equipo_tg=".$this->db->escape($insert['id_equipo_tg'])."
        AND anio_tg=".$this->db->escape($insert['anio_tg'])."
        AND ciclo_tg=".$this->db->escape($insert['ciclo_tg'])."";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_detalle_pdg=$row['id_detalle_pdg'];

        };               

        /*Encontrando el correlativo+1 de pdg_documentos*/
        $sql="SELECT  CASE WHEN MAX(CAST(id_documento_pdg AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_documento_pdg AS UNSIGNED))+1 END AS maximo_documento_pdg
              FROM pdg_documento";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_documento_pdg=$row['maximo_documento_pdg'];

        };                
        //Inserción a pdg_solicitud_academica
        $sql = "INSERT INTO pdg_solicitud_academica(id_solicitud_academica,id_detalle_pdg,ciclo,anio,tipo_solicitud,justificacion,nombre_actual,nombre_propuesto,ingresado_x_equipo,id_documento_pdg)
        VALUES ('".$id_solicitud_academica."',
                '".$id_detalle_pdg."',
                ".$this->db->escape($insert['ciclo']).",
                ".$this->db->escape($insert['anio']).",
                ".$this->db->escape($insert['tipo_solicitud']).",
                ".$this->db->escape($insert['justificacion']).",
                ".$this->db->escape($insert['nombre_actual']).",
                ".$this->db->escape($insert['nombre_propuesto']).",
                ".$ingresado_x_equipo.",
                '".$id_documento_pdg."'
                )";


            $this->db->query($sql);
            $comprobar=$this->db->affected_rows();
            //Si se inserto en pdg_solicitud_academica proceder a insertar en pdg_documento
            if ($comprobar >=1){
                
                /*Si la inserción fue corrrecta se ingresa una instancia de (ADPCN) Aprobacion/denegacion Pendiente Cambio Nombre de Trabajo de Graduación*/
                $sql ="SELECT id_tipo_documento_pdg FROM pdg_tipo_documento
                WHERE siglas IN ('ADPCN')";
                $query = $this->db->query($sql) ;        
                $query->result_array();
                foreach ($query->result_array() as $row)
                {
                        $id_tipo_documento_pdg=$row['id_tipo_documento_pdg'];


                        //Inserción a pdg_documento 
                        $sql = "INSERT INTO pdg_documento(id_documento_pdg,id_tipo_documento_pdg,id_detalle_pdg)
                        VALUES ('".$id_documento_pdg."',
                                '".$id_tipo_documento_pdg."',
                                '".$id_detalle_pdg."'
                                )";
                        $this->db->query($sql);
                        $exitoso=$this->db->affected_rows();
                        if ($exitoso==0) {
                            return 0;
                        }

                };             
                //Enviar 1  para mostrar que todas las inserciones fueron exitosas
                return 1;
                

            }
            else{
                //significa que no se pudo insertar en la pdg_solicitud_academica
                return 0;
            }
                 
    }

    function Get_Datos_Tema($get)
    {
        $this->db->select('tema,anio_tg,ciclo_tg');
        $this->db->from('pdg_equipo_tg');
        $this->db->where('id_equipo_tg',$get);
        $query = $this->db->get();
        return $query->result();

    } 


}

?>






