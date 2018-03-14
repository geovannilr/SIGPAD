<?php
class ControlAsesorias_gc_model extends CI_Model{

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
    function EncontrarIdEquipoFiltrarDocente($id_docente){
        $sql = "SELECT DISTINCT id_equipo_tg FROM conforma
                WHERE id_due IN (SELECT DISTINCT id_due FROM asignado
                WHERE id_docente='".$id_docente."'
                AND id_proceso='PDG'
                AND id_cargo=2)";
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

function Create($insert)
    {

        //Generando el id_bitacora
        $sql ="SELECT CASE WHEN MAX(CAST(id_bitacora AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_bitacora AS UNSIGNED))+1 END AS maximo
				FROM pdg_bitacora_control";
        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_bitacora=$row['maximo'];

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
                 
                
        //Inserción a pdg_bitacora_control
        $sql = "INSERT INTO pdg_bitacora_control(id_bitacora,id_detalle_pdg,ciclo,
        	anio,fecha,tema,
        	tematica_tratar,hora_inicio,hora_fin,
        	id_due1,hora_inicio_alumno_1,hora_fin_alumno_1,
        	id_due2,hora_inicio_alumno_2,hora_fin_alumno_2,
        	id_due3,hora_inicio_alumno_3,hora_fin_alumno_3,
        	id_due4,hora_inicio_alumno_4,hora_fin_alumno_4,
            id_due5,hora_inicio_alumno_5,hora_fin_alumno_5,
        	id_docente,hora_inicio_docente,hora_fin_docente,
        	lugar,observaciones)
        VALUES ('".$id_bitacora."',
                '".$id_detalle_pdg."',
                ".$this->db->escape($insert['ciclo_asesoria']).",
                ".$this->db->escape($insert['anio_asesoria']).",
                ".$this->db->escape($insert['fecha']).",
                ".$this->db->escape($insert['tema_asesoria']).",
                ".$this->db->escape($insert['tematica_tratar']).",
                STR_TO_DATE(".$this->db->escape($insert['hora_inicio']).", '%d/%m/%Y %H:%i:%s'),
                STR_TO_DATE(".$this->db->escape($insert['hora_fin']).", '%d/%m/%Y %H:%i:%s'),                
                ".$this->db->escape($insert['id_due1']).",
                STR_TO_DATE(".$this->db->escape($insert['hora_inicio_alumno_1']).", '%d/%m/%Y %H:%i:%s'),
                STR_TO_DATE(".$this->db->escape($insert['hora_fin_alumno_1']).", '%d/%m/%Y %H:%i:%s'),
                ".$this->db->escape($insert['id_due2']).",
                STR_TO_DATE(".$this->db->escape($insert['hora_inicio_alumno_2']).", '%d/%m/%Y %H:%i:%s'),                
                STR_TO_DATE(".$this->db->escape($insert['hora_fin_alumno_2']).", '%d/%m/%Y %H:%i:%s'),
                ".$this->db->escape($insert['id_due3']).",
                STR_TO_DATE(".$this->db->escape($insert['hora_inicio_alumno_3']).", '%d/%m/%Y %H:%i:%s'),                
                STR_TO_DATE(".$this->db->escape($insert['hora_fin_alumno_3']).", '%d/%m/%Y %H:%i:%s'),                
                ".$this->db->escape($insert['id_due4']).",
                STR_TO_DATE(".$this->db->escape($insert['hora_inicio_alumno_4']).", '%d/%m/%Y %H:%i:%s'),                                                
                STR_TO_DATE(".$this->db->escape($insert['hora_fin_alumno_4']).", '%d/%m/%Y %H:%i:%s'),                            
                ".$this->db->escape($insert['id_due5']).",
                STR_TO_DATE(".$this->db->escape($insert['hora_inicio_alumno_5']).", '%d/%m/%Y %H:%i:%s'),                                  
                STR_TO_DATE(".$this->db->escape($insert['hora_fin_alumno_5']).", '%d/%m/%Y %H:%i:%s'),                
				".$this->db->escape($insert['id_docente']).",
                STR_TO_DATE(".$this->db->escape($insert['hora_inicio_docente']).", '%d/%m/%Y %H:%i:%s'),                                               
                STR_TO_DATE(".$this->db->escape($insert['hora_fin_docente']).", '%d/%m/%Y %H:%i:%s'),                           
                ".$this->db->escape($insert['lugar']).",
                ".$this->db->escape($insert['observaciones'])."                
                )";
            $this->db->query($sql);
            $comprobar=$this->db->affected_rows();
            return $comprobar;
    }

    function Get_Datos_Tema($get)
    {
        $this->db->select('tema,anio_tg,ciclo_tg');
        $this->db->from('pdg_equipo_tg');
        $this->db->where('id_equipo_tg',$get);
        /*$sql ="SELECT b.tema,b.anio_tg,a.id_due FROM conforma a,pdg_equipo_tg b
				WHERE a.id_equipo_tg=b.id_equipo_tg
				AND a.anio_tg=b.anio_tg
				AND a.id_equipo_tg='1'";
        
        $query = $this->db->query($sql) ;     
        return $query->result();*/
        $query = $this->db->get();
        return $query->result();
    } 
    function Get_Datos_Due1($get)
    {
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
				WHERE a.id_equipo_tg=b.id_equipo_tg
				AND a.anio_tg=b.anio_tg
                AND a.ciclo_tg=b.ciclo_tg
				AND a.id_equipo_tg='".$get."'
				ORDER BY a.id_due
				LIMIT 1";
        
        $query = $this->db->query($sql) ;     
        return $query->result();
	}
    function Get_Datos_Due2($get)
    {
    	/*Obteniendo id_due1*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
		WHERE a.id_equipo_tg=b.id_equipo_tg
		AND a.anio_tg=b.anio_tg
        AND a.ciclo_tg=b.ciclo_tg
		AND a.id_equipo_tg='".$get."'
		ORDER BY a.id_due
		LIMIT 1";

        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_due1=$row['id_due'];
        };     


        /*Obteniendo id_due2*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
				WHERE a.id_equipo_tg=b.id_equipo_tg
				AND a.anio_tg=b.anio_tg
                AND a.ciclo_tg=b.ciclo_tg
				AND a.id_equipo_tg='".$get."'
				AND a.id_due NOT IN ('".$id_due1."')
				ORDER BY a.id_due
				LIMIT 1";
        
        $query = $this->db->query($sql) ;     
        return $query->result();
	}
    function Get_Datos_Due3($get)
    {
    	/*Obteniendo id_due1*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
		WHERE a.id_equipo_tg=b.id_equipo_tg
		AND a.anio_tg=b.anio_tg
        AND a.ciclo_tg=b.ciclo_tg
		AND a.id_equipo_tg='".$get."'
		ORDER BY a.id_due
		LIMIT 1";

        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_due1=$row['id_due'];
        };     
    	/*Obteniendo id_due2*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
				WHERE a.id_equipo_tg=b.id_equipo_tg
				AND a.anio_tg=b.anio_tg
                AND a.ciclo_tg=b.ciclo_tg
				AND a.id_equipo_tg='".$get."'
				AND a.id_due NOT IN ('".$id_due1."')
				ORDER BY a.id_due
				LIMIT 1";

        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_due2=$row['id_due'];
        };     

    	/*Obteniendo id_due3*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
				WHERE a.id_equipo_tg=b.id_equipo_tg
				AND a.anio_tg=b.anio_tg
                AND a.ciclo_tg=b.ciclo_tg
				AND a.id_equipo_tg='".$get."'
				AND a.id_due NOT IN ('".$id_due1."','".$id_due2."')
				ORDER BY a.id_due
				LIMIT 1";
        $query = $this->db->query($sql) ;     
        return $query->result();
	}
    function Get_Datos_Due4($get)
    {
    	/*Obteniendo id_due1*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
		WHERE a.id_equipo_tg=b.id_equipo_tg
		AND a.anio_tg=b.anio_tg
        AND a.ciclo_tg=b.ciclo_tg
		AND a.id_equipo_tg='".$get."'
		ORDER BY a.id_due
		LIMIT 1";

        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_due1=$row['id_due'];
        };     
    	/*Obteniendo id_due2*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
				WHERE a.id_equipo_tg=b.id_equipo_tg
				AND a.anio_tg=b.anio_tg
                AND a.ciclo_tg=b.ciclo_tg
				AND a.id_equipo_tg='".$get."'
				AND a.id_due NOT IN ('".$id_due1."')
				ORDER BY a.id_due
				LIMIT 1";

        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_due2=$row['id_due'];
        };     
    	/*Obteniendo id_due3*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
				WHERE a.id_equipo_tg=b.id_equipo_tg
				AND a.anio_tg=b.anio_tg
                AND a.ciclo_tg=b.ciclo_tg
				AND a.id_equipo_tg='".$get."'
				AND a.id_due NOT IN ('".$id_due1."','".$id_due2."')
				ORDER BY a.id_due
				LIMIT 1";

        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_due3=$row['id_due'];
        };     


    	/*Obteniendo id_due4*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
				WHERE a.id_equipo_tg=b.id_equipo_tg
				AND a.anio_tg=b.anio_tg
                AND a.ciclo_tg=b.ciclo_tg
				AND a.id_equipo_tg='".$get."'
				AND a.id_due NOT IN ('".$id_due1."','".$id_due2."','".$id_due3."')
				ORDER BY a.id_due
				LIMIT 1";
        $query = $this->db->query($sql) ;     
        return $query->result();
	}

    function Get_Datos_Due5($get)
    {
        /*Obteniendo id_due1*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
        WHERE a.id_equipo_tg=b.id_equipo_tg
        AND a.anio_tg=b.anio_tg
        AND a.ciclo_tg=b.ciclo_tg
        AND a.id_equipo_tg='".$get."'
        ORDER BY a.id_due
        LIMIT 1";

        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_due1=$row['id_due'];
        };     
        /*Obteniendo id_due2*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
                WHERE a.id_equipo_tg=b.id_equipo_tg
                AND a.anio_tg=b.anio_tg
                AND a.ciclo_tg=b.ciclo_tg
                AND a.id_equipo_tg='".$get."'
                AND a.id_due NOT IN ('".$id_due1."')
                ORDER BY a.id_due
                LIMIT 1";

        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_due2=$row['id_due'];
        };     
        /*Obteniendo id_due3*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
                WHERE a.id_equipo_tg=b.id_equipo_tg
                AND a.anio_tg=b.anio_tg
                AND a.ciclo_tg=b.ciclo_tg
                AND a.id_equipo_tg='".$get."'
                AND a.id_due NOT IN ('".$id_due1."','".$id_due2."')
                ORDER BY a.id_due
                LIMIT 1";

        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_due3=$row['id_due'];
        };     

        /*Obteniendo id_due4*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
                WHERE a.id_equipo_tg=b.id_equipo_tg
                AND a.anio_tg=b.anio_tg
                AND a.ciclo_tg=b.ciclo_tg
                AND a.id_equipo_tg='".$get."'
                AND a.id_due NOT IN ('".$id_due1."','".$id_due2."','".$id_due3."')
                ORDER BY a.id_due
                LIMIT 1";

        $query = $this->db->query($sql) ;        
        $query->result_array();
        foreach ($query->result_array() as $row)
        {
                $id_due4=$row['id_due'];
        }; 


        /*Obteniendo id_due5*/
        $sql ="SELECT a.id_due FROM conforma a,pdg_equipo_tg b
                WHERE a.id_equipo_tg=b.id_equipo_tg
                AND a.anio_tg=b.anio_tg
                AND a.ciclo_tg=b.ciclo_tg
                AND a.id_equipo_tg='".$get."'
                AND a.id_due NOT IN ('".$id_due1."','".$id_due2."','".$id_due3."','".$id_due4."')
                ORDER BY a.id_due
                LIMIT 1";
        $query = $this->db->query($sql) ;     
        return $query->result();
    }

    function Get_Datos_Docente($get1,$get2,$get3)
    {
        /*Ubicando los id de los asesores de trabajo de graduacion segun equipo de tesis seleccionado*/
        /*$sql ="SELECT DISTINCT id_docente FROM asignado
				WHERE id_cargo='2'
				AND id_proceso='PDG'
				AND es_docente_director_pdg=1
				AND id_due IN (SELECT id_due FROM conforma
				WHERE anio_tg='2016'
				AND id_equipo_tg='".$get1."')
				LIMIT 1";*/
        $sql ="SELECT carnet FROM gen_docente WHERE id_docente=(SELECT DISTINCT id_docente FROM asignado
                WHERE id_cargo='2'
                AND id_proceso='PDG'
                AND es_docente_director_pdg=1
                AND id_due IN (SELECT id_due FROM conforma
                WHERE anio_tg='".$get2."'
                AND ciclo_tg='".$get3."'
                AND id_equipo_tg='".$get1."')
                LIMIT 1)";                
		$query = $this->db->query($sql) ;     
		return $query->result();
	}
    function Update($update)
    {
                
        //Actualizacion a pdg_bitacora_control
        $sql = "UPDATE pdg_bitacora_control set
                tema='".$update['tema_asesoria']."',
                tematica_tratar='".$update['tematica_tratar']."',
                observaciones='".$update['observaciones']."'
                where id_bitacora='".$update['id_bitacora']."'";

        $this->db->query($sql);
        return $this->db->affected_rows();

    }        

}
?>
