<?php
class ConfEquiTg_model extends CI_Model{

    function __construct(){
    	   parent::__construct();
        $this->load->database();
    }
    function Get_Candidatos()
    {
        $query = $this->db->query("SELECT a.id_due,a.nombre,a.apellido FROM gen_estudiante a,es b
                                    WHERE a.id_due=b.id_due
                                    AND b.id_tipo_estudiante='PDG'
                                    AND a.correlativo_equipo IS NULL
                                    UNION
                                    SELECT a.id_due,a.nombre,a.apellido FROM gen_estudiante a,es b
                                    WHERE a.id_due=b.id_due
                                    AND b.id_tipo_estudiante='PDG'
                                    AND a.correlativo_equipo IS NOT NULL
                                    AND a.estado_tesis IN ('R','E');
                                    ");
        return $query->result();
    } 

    function Obtener_Numero_Equipo($anio)
    {
     
            //Generando el id_equipo_tg
            $sql ="SELECT CASE WHEN MAX(CAST(id_equipo_tg AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_equipo_tg AS UNSIGNED))+1 END AS maximo
                    FROM pdg_equipo_tg
                    WHERE anio_tg='".$anio."'";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_equipo_tg=$row['maximo'];

            };         
            return $id_equipo_tg;

    }

    function Crear_Equipo($id_equipo_tg_new,$anio_tg,$ciclo_tg,$tema_tg,$siglas_tg)
    {
     
            $sql = "INSERT INTO pdg_equipo_tg (id_equipo_tg,anio_tg,ciclo_tg,tema,sigla)
                    VALUES('".$id_equipo_tg_new."','".$anio_tg."','".$ciclo_tg."','".$tema_tg."','".$siglas_tg."')";
			$this->db->query($sql);
			return $this->db->affected_rows();

    }
    function Actualizar_Gen_Estudiante($id_equipo_tg_new,$id_due)
    {
            $sql = "UPDATE gen_estudiante SET correlativo_equipo='".$id_equipo_tg_new."', estado_tesis='P'
                    WHERE id_due='".$id_due."'";
            $this->db->query($sql);
            return $this->db->affected_rows();

    }
    function Crear_Conforma($id_equipo_tg_new,$anio_tg,$ciclo_tg,$id_due)
    {
            

            $sql = "INSERT INTO conforma(id_equipo_tg,anio_tg,ciclo_tg,id_due)
                    VALUES('".$id_equipo_tg_new."','".$anio_tg."','".$ciclo_tg."','".$id_due."')";
            $this->db->query($sql);
            return $this->db->affected_rows();


    }
    function Obtener_Numero_Detalle()
    {
     
            //Encontrando el´proximo id_detalle_pdg  
            $sql ="SELECT CASE WHEN MAX(CAST(id_detalle_pdg AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_detalle_pdg AS UNSIGNED))+1 END AS maximo
                    FROM pdg_detalle";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_detalle_pdg=$row['maximo'];

            };           
            return $id_detalle_pdg;

    }    
    function Crear_Detalle($id_detalle_pdg_new,$id_equipo_tg_new,$anio_tg,$ciclo_tg)
    {          

            $sql = "INSERT INTO pdg_detalle(id_detalle_pdg,id_equipo_tg,anio_tg,ciclo_tg)
                    VALUES('".$id_detalle_pdg_new."','".$id_equipo_tg_new."','".$anio_tg."','".$ciclo_tg."')";
            $this->db->query($sql);
            return $this->db->affected_rows();

    }
    function Crear_Documentos($id_detalle_pdg_new)
    {
     
            //obtenendo el maaximo correlativo de id_documento_pdg
            $sql ="SELECT CASE WHEN MAX(CAST(id_documento_pdg AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_documento_pdg AS UNSIGNED))+1 END AS maximo
                    FROM pdg_documento";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_documento_pdg_new=$row['maximo'];

            };           

            //Creacion de documento de perfil     
            $sql = "INSERT INTO pdg_documento (id_documento_pdg,id_tipo_documento_pdg,id_detalle_pdg)
                    VALUES('".$id_documento_pdg_new."','1','".$id_detalle_pdg_new."')";
            $this->db->query($sql);        
            //Creacion de documento de anteproyecto
            $id_documento_pdg_new=$id_documento_pdg_new+1;
            $sql = "INSERT INTO pdg_documento (id_documento_pdg,id_tipo_documento_pdg,id_detalle_pdg)
                    VALUES('".$id_documento_pdg_new."','2','".$id_detalle_pdg_new."')";
            $this->db->query($sql);             
            //Creacion de documento de etapa 1
            $id_documento_pdg_new=$id_documento_pdg_new+1;
            $sql = "INSERT INTO pdg_documento (id_documento_pdg,id_tipo_documento_pdg,id_detalle_pdg)
                    VALUES('".$id_documento_pdg_new."','3','".$id_detalle_pdg_new."')";
            $this->db->query($sql);              
            //Creacion de documento de etapa 2
            $id_documento_pdg_new=$id_documento_pdg_new+1;
            $sql = "INSERT INTO pdg_documento (id_documento_pdg,id_tipo_documento_pdg,id_detalle_pdg)
                    VALUES('".$id_documento_pdg_new."','4','".$id_detalle_pdg_new."')";
            $this->db->query($sql);              
            //Creacion de documento de defensa publica
            $id_documento_pdg_new=$id_documento_pdg_new+1;
            $sql = "INSERT INTO pdg_documento (id_documento_pdg,id_tipo_documento_pdg,id_detalle_pdg)
                    VALUES('".$id_documento_pdg_new."','5','".$id_detalle_pdg_new."')";
            $this->db->query($sql);               
            //Creacion de documento de aprobacion/denegacion de perfil
            $id_documento_pdg_new=$id_documento_pdg_new+1;
              $sql = "INSERT INTO pdg_documento (id_documento_pdg,id_tipo_documento_pdg,id_detalle_pdg)
                    VALUES('".$id_documento_pdg_new."','16','".$id_detalle_pdg_new."')";
            $this->db->query($sql);              
            //Creacion de documento de aprobacion/denegacion de anteproyecto
            $id_documento_pdg_new=$id_documento_pdg_new+1;
            $sql = "INSERT INTO pdg_documento (id_documento_pdg,id_tipo_documento_pdg,id_detalle_pdg)
                    VALUES('".$id_documento_pdg_new."','17','".$id_detalle_pdg_new."')";
            $this->db->query($sql);           
            return $this->db->affected_rows();

    }
   function Obtener_Numero_Perfil()
    {
     
            //Encontrando el´proximo id_perfil  
            $sql ="SELECT CASE WHEN MAX(CAST(id_perfil AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_perfil AS UNSIGNED))+1 END AS maximo
                    FROM pdg_perfil";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_perfil=$row['maximo'];

            };           
            return $id_perfil;

    }        
    function Crear_Perfil($id_perfil_new,$id_detalle_pdg_new)
    {          
            //Encontrando el´max id_detalle_pdg   asociado al tipo de documento 1 es decir de "Perfil"
            $sql ="SELECT MAX(CAST(id_documento_pdg AS UNSIGNED)) maximo FROM pdg_documento
                    WHERE id_tipo_documento_pdg='1'";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_documento_pdg=$row['maximo'];

            };           

            $sql = "INSERT INTO pdg_perfil(id_perfil,id_detalle_pdg,id_documento_pdg)
                    VALUES('".$id_perfil_new."','".$id_detalle_pdg_new."','".$id_documento_pdg."')";
            $this->db->query($sql);
            return $this->db->affected_rows();

    }        
    function Crear_Notas_Anteproyecto($id_equipo_tg_new,$tema,$anio_tg,$ciclo_tg)
    {
            //Encontrando el´max id_nota_anteproyecto
            $sql ="SELECT CASE WHEN MAX(CAST(id_nota_anteproyecto AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_nota_anteproyecto AS UNSIGNED))+1 END AS maximo
                    FROM pdg_nota_anteproyecto";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_nota_anteproyecto=$row['maximo'];

            };        

            $sql = "INSERT INTO pdg_nota_anteproyecto (id_nota_anteproyecto,id_equipo_tg,tema,anio_tg,ciclo_tg,estado_nota,
                    cod_criterio1,cod_criterio2,cod_criterio3,cod_criterio4,cod_criterio5,cod_criterio6,cod_criterio7,cod_criterio8,
                    cod_criterio9,cod_criterio10,cod_criterio11,cod_criterio12,
                    nota_criterio1,nota_criterio2,nota_criterio3,nota_criterio4,nota_criterio5,nota_criterio6,nota_criterio7,
                    nota_criterio8,nota_criterio9,nota_criterio10,nota_criterio11,nota_criterio12,
                    nota_documento,nota_exposicion,nota_anteproyecto)
                    VALUES('".$id_nota_anteproyecto."','".$id_equipo_tg_new."','".$tema."','".$anio_tg."','".$ciclo_tg."','A',
                    '1','2','3','4','5','6','7','8','9','10','11','12',
                    '0','0','0','0','0','0','0','0','0','0','0','0',
                    '0','0','0')";
            $this->db->query($sql);
            return $this->db->affected_rows();

    }
    function Crear_Notas_Etapa1($id_equipo_tg_new,$tema,$anio_tg,$ciclo_tg,$id_due)
    {
            //Encontrando el´max id_nota_etapa1
            $sql ="SELECT CASE WHEN MAX(CAST(id_nota_etapa1 AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_nota_etapa1 AS UNSIGNED))+1 END AS maximo
                    FROM pdg_nota_etapa1";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_nota_etapa1=$row['maximo'];

            };        
     
            $sql = "INSERT INTO pdg_nota_etapa1 (id_nota_etapa1,id_equipo_tg,tema,anio_tg,ciclo_tg,id_due,estado_nota,
                    cod_criterio1,cod_criterio2,cod_criterio3,cod_criterio4,cod_criterio5,cod_criterio6,
                    nota_criterio1,nota_criterio2,nota_criterio3,nota_criterio4,nota_criterio5,nota_criterio6,
                    nota_documento,nota_exposicion,nota_etapa1)
                    VALUES('".$id_nota_etapa1."','".$id_equipo_tg_new."','".$tema."','".$anio_tg."','".$ciclo_tg."','".$id_due."','A',
                    '1','2','3','4','5','6',
                    '0','0','0','0','0','0',
                    '0','0','0')";
            $this->db->query($sql);
            return $this->db->affected_rows();

    }
    function Crear_Notas_Etapa2($id_equipo_tg_new,$tema,$anio_tg,$ciclo_tg,$id_due)
    {
     
            //Encontrando el´max id_nota_etapa2
            $sql ="SELECT CASE WHEN MAX(CAST(id_nota_etapa2 AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_nota_etapa2 AS UNSIGNED))+1 END AS maximo
                    FROM pdg_nota_etapa2";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_nota_etapa2=$row['maximo'];

            };        

            $sql = "INSERT INTO pdg_nota_etapa2 (id_nota_etapa2,id_equipo_tg,tema,anio_tg,ciclo_tg,id_due,estado_nota,
                    cod_criterio1,cod_criterio2,cod_criterio3,cod_criterio4,cod_criterio5,cod_criterio6,
                    nota_criterio1,nota_criterio2,nota_criterio3,nota_criterio4,nota_criterio5,nota_criterio6,
                    nota_documento,nota_exposicion,nota_etapa2)
                    VALUES('".$id_nota_etapa2."','".$id_equipo_tg_new."','".$tema."','".$anio_tg."','".$ciclo_tg."','".$id_due."','A',
                    '1','2','3','4','5','6',
                    '0','0','0','0','0','0',
                    '0','0','0')";
            $this->db->query($sql);
            return $this->db->affected_rows();

    }
    function Crear_Notas_Defensa_Publica($id_equipo_tg_new,$tema,$anio_tg,$ciclo_tg,$id_due)
    {
            
            //Encontrando el´max id_nota_defensa_publica
            $sql ="SELECT CASE WHEN MAX(CAST(id_nota_defensa_publica AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_nota_defensa_publica AS UNSIGNED))+1 END AS maximo
                    FROM pdg_nota_defensa_publica";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_nota_defensa_publica=$row['maximo'];

            }; 
            //Para docente Asesor cargo 2
            $sql = "INSERT INTO pdg_nota_defensa_publica(id_nota_defensa_publica,id_equipo_tg,tema,anio_tg,ciclo_tg,id_due,estado_nota,
                    id_cargo,nota_defensa_publica)
                    VALUES('".$id_nota_defensa_publica."','".$id_equipo_tg_new."','".$tema."','".$anio_tg."','".$ciclo_tg."','".$id_due."','A',
                    '2','0')";
            $this->db->query($sql);            
            //Para docente 1 del tribunal evaluador cargo 5
            $id_nota_defensa_publica=$id_nota_defensa_publica+1;
            $sql = "INSERT INTO pdg_nota_defensa_publica(id_nota_defensa_publica,id_equipo_tg,tema,anio_tg,ciclo_tg,id_due,estado_nota,
                    id_cargo,nota_defensa_publica)
                    VALUES('".$id_nota_defensa_publica."','".$id_equipo_tg_new."','".$tema."','".$anio_tg."','".$ciclo_tg."','".$id_due."','A',
                    '5','0')";
            $this->db->query($sql);            
            //Para docente 2 del tribunal evaluador cargo 6
            $id_nota_defensa_publica=$id_nota_defensa_publica+1;
            $sql = "INSERT INTO pdg_nota_defensa_publica(id_nota_defensa_publica,id_equipo_tg,tema,anio_tg,ciclo_tg,id_due,estado_nota,
                    id_cargo,nota_defensa_publica)
                    VALUES('".$id_nota_defensa_publica."','".$id_equipo_tg_new."','".$tema."','".$anio_tg."','".$ciclo_tg."','".$id_due."','A',
                    '6','0')";
            $this->db->query($sql); 
   
            return $this->db->affected_rows();

    }
  function Crear_Consolidado_Notas($id_equipo_tg_new,$tema,$anio_tg,$ciclo_tg,$id_due)
    {
            //Encontrando el´max id_consolidado_notas
            $sql ="SELECT CASE WHEN MAX(CAST(id_consolidado_notas AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(id_consolidado_notas AS UNSIGNED))+1 END AS maximo
                    FROM pdg_consolidado_notas";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_consolidado_notas=$row['maximo'];

            }; 
            $sql = "INSERT INTO pdg_consolidado_notas(id_consolidado_notas,id_equipo_tg,tema,anio_tg,ciclo_tg,id_due,
                    nota_anteproyecto,nota_etapa1,nota_etapa2,nota_defensa_publica)
                    VALUES('".$id_consolidado_notas."','".$id_equipo_tg_new."','".$tema."','".$anio_tg."','".$ciclo_tg."','".$id_due."',
                    '0','0','0','0')";
            $this->db->query($sql);
            return $this->db->affected_rows();

    }
    //De prueba despues borrar
    function Crear($insertar)
    {
          //$this->db->insert('mytable', $data);  // Produces: INSERT INTO mytable (title, name, date) VALUES ('{$title}', '{$name}', '{$date}')
          /*$this->db->insert('pdg_perfil',$insert);
          return $this->db->affected_rows();*/


            //$sql = "INSERT INTO pdg_perfil (id_perfil,id_detalle_pdg,ciclo,anio,objetivo_general,objetivo_especifico,descripcion) VALUES (".$this->db->escape($title).", ".$this->db->escape($name).")";
            /*$sql = "INSERT INTO tabla (valor) 
            VALUES (
                    ".$insert['checkbox']."
                    )";*/
            $sql = "INSERT INTO tabla (valor) 
            VALUES (
                    ".$insertar."
                    )";
            $this->db->query($sql);
            //$filas_afectadas=$this->db->affected_rows();
            return $this->db->affected_rows();

    }    
}

?>

