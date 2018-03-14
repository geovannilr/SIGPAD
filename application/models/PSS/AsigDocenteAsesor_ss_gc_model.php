<?php
class AsigDocenteAsesor_ss_gc_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }
    


    function Create($insert)
    {
            
        /*$sql="INSERT INTO tabla(valor) VALUES('".$insert['id_due']."')";
        $this->db->query($sql); 
        $sql="INSERT INTO tabla(valor) VALUES('".$insert['id_docente']."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$insert['id_proceso']."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$insert['id_cargo']."')";
        $this->db->query($sql);  
        $sql="INSERT INTO tabla(valor) VALUES('".$insert['correlativo_tutor_ss']."')";
        $this->db->query($sql);  
        $sql="INSERT INTO tabla(valor) VALUES('".$insert['es_docente_pss_principal']."')";
        $this->db->query($sql);  
        $sql="INSERT INTO tabla(valor) VALUES('".$insert['id_detalle_expediente']."')";
        $this->db->query($sql);  */
         
        /*----------------------------------------------------------------------------------*/
        //cuando el es_docente_principal =1 se hacen las giuientes validaciones
        if($insert['es_docente_pss_principal']==1){
           /*Valida si el xpediente con id_due en cuestion ya se le ha asigando docente principal*/
           $sql ="SELECT COUNT(*) cantidad FROM asignado
                    WHERE id_proceso='PSS'
                     AND  id_due='".$insert['id_due']."'
                     AND id_detalle_expediente='".$insert['id_detalle_expediente']."'
                     AND es_docente_pss_principal=".$insert['es_docente_pss_principal']."";

            $query = $this->db->query($sql) ; 
            foreach ($query->result_array() as $row)
            {
                    $cantidad=$row['cantidad'];
            }
            if ($cantidad>=1){
                return 0;//para qaue muestre mensaje que a ese expediente con ese due ya se le ha asignado un docente principal
            }else{
                    /*Valida si el docente asesor de servicio social ya ha sido ingresado para el mismo expediente */
                     $sql ="SELECT count(*) cantidad_ FROM asignado
                             WHERE id_proceso='PSS'
                             AND  id_due='".$insert['id_due']."'
                             AND id_detalle_expediente='".$insert['id_detalle_expediente']."'
                             AND id_docente='".$insert['id_docente']."'";

                    $query = $this->db->query($sql) ; 
                    foreach ($query->result_array() as $row)
                    {
                            $cantidad_=$row['cantidad_'];
                    }
                    if($cantidad_>=1){
                        return 0;// para que muestre mensaje que a ese expediente con ese due ya se le ha asignado el mismo docente
                    }else{
                        //creacion
                       //Inserción a asignado
                        $sql = "INSERT INTO asignado(id_due,id_docente,id_proceso,id_cargo,correlativo_tutor_ss,es_docente_pss_principal,id_detalle_expediente)
                        VALUES (".$this->db->escape($insert['id_due']).",
                                ".$this->db->escape($insert['id_docente']).",
                                ".$this->db->escape($insert['id_proceso']).",
                                ".$this->db->escape($insert['id_cargo']).",
                                ".$insert['correlativo_tutor_ss'].",
                                ".$this->db->escape($insert['es_docente_pss_principal']).",
                                ".$this->db->escape($insert['id_detalle_expediente'])."
                                )";
                        $this->db->query($sql);
                        return $this->db->affected_rows();                         
                    }
            }
        }// fin if($insertar['es_docente_pss_principal']=1)
        //cuando el es_docente_principal =1 se hacen las giuientes validaciones
        if($insert['es_docente_pss_principal']==0){
                    /*Valida si el docente asesor de servicio social ya ha sido ingresado para el mismo expediente */
                     $sql ="SELECT count(*) cantidad_ FROM asignado
                             WHERE id_proceso='PSS'
                             AND  id_due='".$insert['id_due']."'
                             AND id_detalle_expediente='".$insert['id_detalle_expediente']."'
                             AND id_docente='".$insert['id_docente']."'";
                    $query = $this->db->query($sql) ;                   
                    foreach ($query->result_array() as $row)
                    {
                            $cantidad_=$row['cantidad_'];
                    }
                    if($cantidad_>=1){
                        return 0;// para que muestre mensaje que a ese expediente con ese due ya se le ha asignado el mismo docente
                    }else{
                        //creacion

                       //Inserción a asignado
                        $sql = "INSERT INTO asignado(id_due,id_docente,id_proceso,id_cargo,correlativo_tutor_ss,es_docente_pss_principal,id_detalle_expediente)
                        VALUES (".$this->db->escape($insert['id_due']).",
                                ".$this->db->escape($insert['id_docente']).",
                                ".$this->db->escape($insert['id_proceso']).",
                                ".$this->db->escape($insert['id_cargo']).",
                                ".$insert['correlativo_tutor_ss'].",
                                ".$this->db->escape($insert['es_docente_pss_principal']).",
                                ".$this->db->escape($insert['id_detalle_expediente'])."
                                )";
                        $this->db->query($sql);
                        return $this->db->affected_rows(); 

                    }//
        }  //fin de if($insertar['es_docente_pss_principal']=0)      
        
    }


    function Update($update)
    {

        /*$sql="INSERT INTO tabla(valor) VALUES(".$update['id_detalle_expediente'].")";
        $this->db->query($sql); 
        $sql="INSERT INTO tabla(valor) VALUES('".$update['id_due']."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$update['id_docente']."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$update['id_proceso']."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$update['id_cargo']."')";
        $this->db->query($sql); 
        $sql="INSERT INTO tabla(valor) VALUES('".$update['es_docente_pss_principal']."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$update['id_docente_old']."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$update['es_docente_principal_pss_old']."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$update['correlativo_tutor_ss_old']."')";
        $this->db->query($sql); */
                         
        //Actualizacion a asignado
        /*$sql = "UPDATE asignado set id_docente='".$update['id_docente']."',
                es_docente_director_pdg='".$update['es_docente_director_pdg']."'
                where id_due='".$update['id_due']."'
                and id_proceso='".$update['id_proceso']."'
                and id_docente='".$update['id_docente_old']."'
                and id_cargo='".$update['id_cargo']."'";


        $this->db->query($sql);
        //validar que sea docente principal
        if($update['es_docente_director_pdg']==1){
            //Actualizar en tabla pdg_nota_defensa_publica el alumno en cuestion asociado al id_equipo, anio_tg y ciclo_tgy cargo=2
            $sql = "UPDATE pdg_nota_defensa_publica SET id_docente='".$update['id_docente']."'
                WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                AND anio_tg='".$update['anio_tesis']."'
                AND ciclo_tg='".$update['ciclo_tesis']."'
                AND id_due='".$update['id_due']."'
                AND id_Cargo='2'";  
            $this->db->query($sql); 
        }       
        return $this->db->affected_rows();*/
        /*----------------------------------------------------------------------------------*/
        //cuando el es_docente_principal =1 se hacen las giuientes validaciones
        if($update['es_docente_pss_principal']==1){
           /*Valida si el xpediente con id_due en cuestion ya se le ha asigando docente principal*/
           $sql ="SELECT COUNT(*) cantidad FROM asignado
                    WHERE id_proceso='PSS'
                     AND  id_due='".$update['id_due']."'
                     AND id_detalle_expediente='".$update['id_detalle_expediente']."'
                     AND es_docente_pss_principal=".$update['es_docente_pss_principal']."";

            $query = $this->db->query($sql) ; 
            foreach ($query->result_array() as $row)
            {
                    $cantidad=$row['cantidad'];
            }
            if ($cantidad>=1){
                return 0;//para qaue muestre mensaje que a ese expediente con ese due ya se le ha asignado un docente principal
            }else{
                    /*Valida si el docente asesor de servicio social ya ha sido ingresado para el mismo expediente */
                     $sql ="SELECT count(*) cantidad_ FROM asignado
                             WHERE id_proceso='PSS'
                             AND  id_due='".$update['id_due']."'
                             AND id_detalle_expediente='".$update['id_detalle_expediente']."'
                             AND id_docente='".$update['id_docente']."'";

                    $query = $this->db->query($sql) ; 
                    foreach ($query->result_array() as $row)
                    {
                            $cantidad_=$row['cantidad_'];
                    }
                    if($cantidad_>=1){
                        return 0;// para que muestre mensaje que a ese expediente con ese due ya se le ha asignado el mismo docente
                    }else{
                        //creacion
                       //Inserción a asignado
                        /*$sql = "INSERT INTO asignado(id_due,id_docente,id_proceso,id_cargo,correlativo_tutor_ss,es_docente_pss_principal,id_detalle_expediente)
                        VALUES (".$this->db->escape($update['id_due']).",
                                ".$this->db->escape($update['id_docente']).",
                                ".$this->db->escape($update['id_proceso']).",
                                ".$this->db->escape($update['id_cargo']).",
                                ".$insert['correlativo_tutor_ss'].",
                                ".$this->db->escape($update['es_docente_pss_principal']).",
                                ".$this->db->escape($update['id_detalle_expediente'])."
                                )";*/
                        $sql ="UPDATE asignado set id_docente='".$update['id_docente']."',
                                                    es_docente_pss_principal='".$update['es_docente_pss_principal']."'
                        WHERE id_due='".$update['id_due']."'
                        AND id_docente='".$update['id_docente_old']."'
                        AND id_proceso='".$update['id_proceso']."'
                        AND id_cargo='".$update['id_cargo']."'
                        AND correlativo_tutor_ss='".$update['correlativo_tutor_ss_old']."'
                        AND es_docente_pss_principal='".$update['es_docente_principal_pss_old']."'
                        ";
                        $this->db->query($sql);
                        return 1;
                        //return $this->db->affected_rows();                         
                    }
            }
        }// fin if($insertar['es_docente_pss_principal']=1)
        //cuando el es_docente_principal =0 se hacen las giuientes validaciones
        if($update['es_docente_pss_principal']==0){
                    /*Valida si el docente asesor de servicio social ya ha sido ingresado para el mismo expediente */
                     $sql ="SELECT count(*) cantidad_ FROM asignado
                             WHERE id_proceso='PSS'
                             AND  id_due='".$update['id_due']."'
                             AND id_detalle_expediente='".$update['id_detalle_expediente']."'
                             AND id_docente='".$update['id_docente']."'";
                    $query = $this->db->query($sql) ;                   
                    foreach ($query->result_array() as $row)
                    {
                            $cantidad_=$row['cantidad_'];
                    }
                    if($cantidad_>=1){
                        return 0;// para que muestre mensaje que a ese expediente con ese due ya se le ha asignado el mismo docente
                    }else{
                        //creacion

                       //Inserción a asignado
                        /*$sql = "INSERT INTO asignado(id_due,id_docente,id_proceso,id_cargo,correlativo_tutor_ss,es_docente_pss_principal,id_detalle_expediente)
                        VALUES (".$this->db->escape($update['id_due']).",
                                ".$this->db->escape($update['id_docente']).",
                                ".$this->db->escape($update['id_proceso']).",
                                ".$this->db->escape($update['id_cargo']).",
                                ".$insert['correlativo_tutor_ss'].",
                                ".$this->db->escape($update['es_docente_pss_principal']).",
                                ".$this->db->escape($update['id_detalle_expediente'])."
                                )";*/
                        $sql ="UPDATE asignado set id_docente='".$update['id_docente']."',
                                                    es_docente_pss_principal='".$update['es_docente_pss_principal']."'
                        WHERE id_due='".$update['id_due']."'
                        AND id_docente='".$update['id_docente_old']."'
                        AND id_proceso='".$update['id_proceso']."'
                        AND id_cargo='".$update['id_cargo']."'
                        AND correlativo_tutor_ss='".$update['correlativo_tutor_ss_old']."'
                        AND es_docente_pss_principal='".$update['es_docente_principal_pss_old']."'
                        ";
                        $this->db->query($sql);
                        return 1;
                        //return $this->db->affected_rows(); 

                    }//
        }  //fin de if($insertar['es_docente_pss_principal']=0) 
    }    

    function EncontrarLLavesDelete($llaves_delete)
    {  
        $this->db->select('id_due,id_det_expediente,id_docente,correlativo_tutor_ss');
        $this->db->from('asig_docente_aux_pss');
        $this->db->where('id',$llaves_delete['primary_key']);
        $query = $this->db->get();
        return $query->result_array();
    } 

    function Delete($delete)
    {
        /*$sql="INSERT INTO tabla(valor) VALUES('".$delete['id_due']."')";
        $this->db->query($sql); 
        $sql="INSERT INTO tabla(valor) VALUES('".$delete['id_detalle_expediente']."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$delete['id_docente']."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$delete['id_proceso']."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$delete['id_cargo']."')";
        $this->db->query($sql); 
        $sql="INSERT INTO tabla(valor) VALUES('".$delete['correlativo_tutor_ss']."')";
        $this->db->query($sql);   */

        $sql = "DELETE FROM asignado
                 WHERE id_due='".$delete['id_due']."'
                 AND id_detalle_expediente='".$delete['id_detalle_expediente']."'
                 AND id_docente='".$delete['id_docente']."'
                 AND id_proceso='".$delete['id_proceso']."'
                 AND id_cargo='".$delete['id_cargo']."'
                 AND correlativo_tutor_ss=".$delete['correlativo_tutor_ss']."";


        $this->db->query($sql);
        return $this->db->affected_rows();
    }    
    function ObtenerCorrelativoTutorSS($id_due,$id_docente){

          /*$sql="INSERT INTO tabla(valor) VALUES('".$id_due."')";
        $this->db->query($sql); 
         $sql="INSERT INTO tabla(valor) VALUES('".$id_docente."')";
        $this->db->query($sql); */

       $sql ="SELECT CASE WHEN MAX(CAST(correlativo_tutor_ss AS UNSIGNED)) IS NULL THEN 1 ELSE MAX(CAST(correlativo_tutor_ss AS UNSIGNED))+1 END AS maximo
            FROM asignado
            WHERE id_due='".$id_due."'
            AND id_docente='".$id_docente."'
            AND id_proceso='PSS'
            AND id_cargo='4'";
        $query = $this->db->query($sql) ; 
        foreach ($query->result_array() as $row)
        {
                $resultado=$row['maximo'];
        }
        return $resultado;
    }      
    function Get_Datos_Expediente($get)
    {

        /*$sql ="SELECT a.id_detalle_expediente,a.id_servicio_social,c.nombre_servicio_social,b.id_due,b.nombre,b.apellido
            FROM  pss_detalle_expediente a, gen_estudiante b, pss_servicio_social c
            WHERE a.id_due=b.id_due
            AND a.id_servicio_social=c.id_servicio_social
            AND (a.id_due,'PSS','4') NOT IN (SELECT id_due,id_proceso,id_cargo FROM asignado)";*/
        $sql="SELECT a.id_detalle_expediente,a.id_servicio_social,c.nombre_servicio_social,b.id_due,b.nombre,b.apellido
            FROM  pss_detalle_expediente a, gen_estudiante b, pss_servicio_social c
            WHERE a.id_due=b.id_due
            AND a.id_servicio_social=c.id_servicio_social
            AND a.id_detalle_expediente=".$get."";
        $query = $this->db->query($sql) ;        
        return $query->result_array();
  
    } 
    function Get_Datos_Docentes($get)
    {
        $this->db->select('nombre,apellido,email');
        $this->db->from('gen_docente');     
        $this->db->where('id_docente',$get);
        $query = $this->db->get();
        return $query->result();

    } 
    function Get_Valida_Data($get1,$get2,$get3,$get4)
    {
        
        
        /*$sql="INSERT INTO tabla(valor) VALUES('".$get1."')";
        $this->db->query($sql); 
        $sql="INSERT INTO tabla(valor) VALUES('".$get2."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$get3."')";
        $this->db->query($sql);   
        $sql="INSERT INTO tabla(valor) VALUES('".$get4."')";
        $this->db->query($sql);*/                   
        //cuando el es_docente_principal =1 se hacen las giuientes validaciones
        if($get4==1){
           /*Valida si el xpediente con id_due en cuestion ya se le ha asigando docente principal*/
           $sql ="SELECT COUNT(*) cantidad FROM asignado
                    WHERE id_proceso='PSS'
                     AND  id_due='".$get2."'
                     AND id_detalle_expediente='".$get1."'
                     AND es_docente_pss_principal=".$get4."";
            $query = $this->db->query($sql) ; 
            foreach ($query->result_array() as $row)
            {
                    $cantidad=$row['cantidad'];
            }
            if ($cantidad>=1){
                return 1;//para qaue muestre mensaje que a ese expediente con ese due ya se le ha asignado un docente principal
            }else{
                    /*Valida si el docente asesor de servicio social ya ha sido ingresado para el mismo expediente */
                     $sql ="SELECT count(*) cantidad_ FROM asignado
                             WHERE id_proceso='PSS'
                             AND  id_due='".$get2."'
                             AND id_detalle_expediente='".$get1."'
                             AND id_docente='".$get3."'";
                    $query = $this->db->query($sql) ; 
                    foreach ($query->result_array() as $row)
                    {
                            $cantidad_=$row['cantidad_'];
                    }
                    if($cantidad_>=1){
                        return 2;// para que muestre mensaje que a ese expediente con ese due ya se le ha asignado el mismo docente
                    }else{
                        return 0;//para que en el contraolador no sea tomado en cuenta y no mueste me3nsaje de error
                    }
            }
        }// fin if($get4=1)
        //cuando el es_docente_principal =1 se hacen las giuientes validaciones
        if($get4==0){
                    /*Valida si el docente asesor de servicio social ya ha sido ingresado para el mismo expediente */
                     $sql ="SELECT count(*) cantidad_ FROM asignado
                             WHERE id_proceso='PSS'
                             AND  id_due='".$get2."'
                             AND id_detalle_expediente='".$get1."'
                             AND id_docente='".$get3."'";
                    $query = $this->db->query($sql) ; 
                    foreach ($query->result_array() as $row)
                    {
                            $cantidad_=$row['cantidad_'];
                    }
                    if($cantidad_>=1){
                        return 2;// para que muestre mensaje que a ese expediente con ese due ya se le ha asignado el mismo docente
                    }else{
                        return 0;//para que en el contraolador no sea tomado en cuenta y no mueste me3nsaje de error
                    }
        }

    }     

    function ConsultaDocenteAsesorOld($aux1){
        /*buscando el id_docente old*/
        $this->db->select('id_docente');
        $this->db->from('asig_docente_aux_pss');
        $this->db->where('id',$aux1);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $resultado=$row['id_docente'];
        }
        return $resultado;
    }   
    function ConsultaEsDocentePrincipalOld($aux1){
        /*buscando el id_docente old*/
        $this->db->select('es_docente_pss_principal');
        $this->db->from('asig_docente_aux_pss');
        $this->db->where('id',$aux1);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $resultado=$row['es_docente_pss_principal'];
        }
        return $resultado;
    }   
    function ConsultaCorrelativoTutorOld($aux1){
        /*buscando el correlativo_tutor old*/
        $this->db->select('correlativo_tutor_ss');
        $this->db->from('asig_docente_aux_pss');
        $this->db->where('id',$aux1);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $resultado=$row['correlativo_tutor_ss'];
        }
        return $resultado;
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
    function NombreServicioSocial($id_servicio_social){
        /*buscando el nombre del servicio social segun codigo */
        $this->db->select('nombre_servicio_social');
        $this->db->from('pss_servicio_social');
        $this->db->where('id_servicio_social',$id_servicio_social);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $nombre_servicio_social=$row['nombre_servicio_social'];
        }
        return $nombre_servicio_social;
    }   

}
?>

