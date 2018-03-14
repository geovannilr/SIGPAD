<?php
class CierreNotas_gc_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    
function Update($update)
    {
 
        if ($update['etapa_evaluativa']=='1-Anteproyecto'){
            /*************************************************************
            Realizar traslado de notas de pdg_nota_anteproyecto a pdg_consolidado_notas 
            ***************************************************************/

            //Obtencion de nota de anteproyecto
            $sql ="SELECT nota_anteproyecto FROM pdg_nota_anteproyecto
                    WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                    AND ciclo_tg='".$update['ciclo_tg']."'
                    AND anio_tg='".$update['anio_tg']."'";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $nota_anteproyecto=$row['nota_anteproyecto'];
            };  

            //Mapeo de nota de anteproyecto al consolidado de notas

            $sql = "UPDATE pdg_consolidado_notas SET nota_anteproyecto='".$nota_anteproyecto."'
                    WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                    AND anio_tg='".$update['anio_tg']."'
                    AND ciclo_tg='".$update['ciclo_tg']."'";  
            $this->db->query($sql);
            //$validaProcessAnteproy:=$this->db->affected_rows();
            
            //Realizar Actualización de estado en tabla de anteproyecto
            $sql = "UPDATE pdg_nota_anteproyecto set estado_nota='".$update['estado_nota']."'
                    where id_equipo_tg='".$update['id_equipo_tg']."'
                    and anio_tg='".$update['anio_tg']."'      
                    and ciclo_tg='".$update['ciclo_tg']."'"; 
            $this->db->query($sql);
            return $this->db->affected_rows();

        }

        if ($update['etapa_evaluativa']=='2-Etapa 1'){
            /*************************************************************
            Realizar traslado de notas de pdg_nota_etapa1 a pdg_consolidado_notas 
            ***************************************************************/

            //Obtencion de nota de Etapa 1 e integrantes del equipo a los que se les consolidara la nota
            $sql ="SELECT id_due,nota_etapa1 FROM pdg_nota_etapa1
                    WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                    AND anio_tg='".$update['anio_tg']."'
                    AND ciclo_tg='".$update['ciclo_tg']."'";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_due=$row['id_due'];
                    $nota_etapa1=$row['nota_etapa1'];
                    //Obtención del alumno del equipo segun tabla de pdg_consolidado_notas
                    $sql ="SELECT id_due FROM pdg_consolidado_notas
                            WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                            AND anio_tg='".$update['anio_tg']."'
                            AND ciclo_tg='".$update['ciclo_tg']."'";
                    $query = $this->db->query($sql) ;        
                    $query->result_array();
                    foreach ($query->result_array() as $row2)
                    {
                            $id_due_a_consolidar=$row2['id_due'];
                            if ($id_due==$id_due_a_consolidar){
                                //Mapeo de nota de Etapa 1 al consolidado de notas
                                $sql = "UPDATE pdg_consolidado_notas SET nota_etapa1='".$nota_etapa1."'
                                        WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                                        and id_due='".$id_due_a_consolidar."'
                                        AND anio_tg='".$update['anio_tg']."'
                                        AND ciclo_tg='".$update['ciclo_tg']."'";  
                                $this->db->query($sql);
                            }
                    };                      
            };  

            //Realizar Actualización en tabla de Etapa 1
            $sql = "UPDATE pdg_nota_etapa1 set estado_nota='".$update['estado_nota']."'
                    where id_equipo_tg='".$update['id_equipo_tg']."'
                    and anio_tg='".$update['anio_tg']."'     
                    and ciclo_tg='".$update['ciclo_tg']."'"; 
            $this->db->query($sql);
            return $this->db->affected_rows();            
        }

        if ($update['etapa_evaluativa']=='3-Etapa 2'){
            /*************************************************************
            Realizar traslado de notas de pdg_nota_etapa2 a pdg_consolidado_notas 
            ***************************************************************/

            //Obtencion de nota de Etapa 2 e integrantes del equipo a los que se les consolidara la nota
            $sql ="SELECT id_due,nota_etapa2 FROM pdg_nota_etapa2
                    WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                    AND anio_tg='".$update['anio_tg']."'
                    AND ciclo_tg='".$update['ciclo_tg']."'";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_due=$row['id_due'];
                    $nota_etapa2=$row['nota_etapa2'];
                    //Obtención del alumno del equipo segun tabla de pdg_consolidado_notas
                    $sql ="SELECT id_due FROM pdg_consolidado_notas
                            WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                            AND anio_tg='".$update['anio_tg']."'
                            AND ciclo_tg='".$update['ciclo_tg']."'";
                    $query = $this->db->query($sql) ;        
                    $query->result_array();
                    foreach ($query->result_array() as $row2)
                    {
                            $id_due_a_consolidar=$row2['id_due'];
                            if ($id_due==$id_due_a_consolidar){
                                //Mapeo de nota de Etapa 2 al consolidado de notas
                                $sql = "UPDATE pdg_consolidado_notas SET nota_etapa2='".$nota_etapa2."'
                                        WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                                        and id_due='".$id_due_a_consolidar."'
                                        AND anio_tg='".$update['anio_tg']."' 
                                        AND ciclo_tg='".$update['ciclo_tg']."'"; 
                                $this->db->query($sql);
                            }
                    };                      
            };              
            //Realizar Actualización en tabla de Etapa 2
            $sql = "UPDATE pdg_nota_etapa2 set estado_nota='".$update['estado_nota']."'
                    where id_equipo_tg='".$update['id_equipo_tg']."'
                    and anio_tg='".$update['anio_tg']."'        
                    and ciclo_tg='".$update['ciclo_tg']."'"; 
            $this->db->query($sql);
            return $this->db->affected_rows();            
        }

        if ($update['etapa_evaluativa']=='4-Defensa Pública'){
            /*************************************************************
            Realizar traslado de notas de pdg_nota_defensa_publica a pdg_consolidado_notas 
            ***************************************************************/

            //Obtencion de nota de Defensa Publica de integrantes del equipo a los que se les consolidara la nota
            $sql ="SELECT id_due,AVG(nota_defensa_publica) AS prom_nota_defensa_publica 
                    FROM pdg_nota_defensa_publica
                    WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                    AND anio_tg='".$update['anio_tg']."'
                    AND ciclo_tg='".$update['ciclo_tg']."'
                    GROUP BY id_due";
            $query = $this->db->query($sql) ;        
            $query->result_array();
            foreach ($query->result_array() as $row)
            {
                    $id_due=$row['id_due'];
                    $prom_nota_defensa_publica=$row['prom_nota_defensa_publica'];
                    //Obtención del alumno del equipo segun tabla de pdg_consolidado_notas
                    $sql ="SELECT id_due FROM pdg_consolidado_notas
                            WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                            AND anio_tg='".$update['anio_tg']."'
                            AND ciclo_tg='".$update['ciclo_tg']."'";
                    $query = $this->db->query($sql) ;        
                    $query->result_array();
                    foreach ($query->result_array() as $row2)
                    {
                            $id_due_a_consolidar=$row2['id_due'];
                            if ($id_due==$id_due_a_consolidar){
                                //Mapeo de nota de Etapa 2 al consolidado de notas
                                $sql = "UPDATE pdg_consolidado_notas 
                                        SET nota_defensa_publica='".$prom_nota_defensa_publica."'
                                        WHERE id_equipo_tg='".$update['id_equipo_tg']."'
                                        and id_due='".$id_due_a_consolidar."'
                                        AND anio_tg='".$update['anio_tg']."' 
                                        AND ciclo_tg='".$update['ciclo_tg']."'"; 
                                $this->db->query($sql);
                            }
                    };                      
            };              

            //Realizar Actualización en tabla de Defensa Pública
            $sql = "UPDATE pdg_nota_defensa_publica set estado_nota='".$update['estado_nota']."'
                    where id_equipo_tg='".$update['id_equipo_tg']."'
                    AND anio_tg='".$update['anio_tg']."'
                    AND ciclo_tg='".$update['ciclo_tg']."'";          
            $this->db->query($sql);
            return $this->db->affected_rows();            
        }

                                

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
