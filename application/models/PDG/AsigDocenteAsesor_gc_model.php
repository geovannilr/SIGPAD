<?php
class AsigDocenteAsesor_gc_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }
    


    function Create($insert)
    {
                
        //Inserción a asignado
        $sql = "INSERT INTO asignado(id_due,id_docente,id_proceso,id_cargo,correlativo_tutor_ss,es_docente_director_pdg)
        VALUES (".$this->db->escape($insert['id_due']).",
                ".$this->db->escape($insert['id_docente']).",
                ".$this->db->escape($insert['id_proceso']).",
                ".$this->db->escape($insert['id_cargo']).",
                ".$this->db->escape($insert['correlativo_tutor_ss']).",
                ".$this->db->escape($insert['es_docente_director_pdg'])."
                )";
        $this->db->query($sql);
        //validar que sea docente principal
        if($insert['es_docente_director_pdg']==1){


            //Actualizar en tabla pdg_nota_defensa_publica el alumno en cuestion asociado al id_equipo, anio_tg y ciclo_tgy cargo=2
            $sql = "UPDATE pdg_nota_defensa_publica SET id_docente='".$insert['id_docente']."'
            WHERE id_equipo_tg='".$insert['id_equipo_tg']."'
            AND anio_tg='".$insert['anio_tesis']."'
            AND ciclo_tg='".$insert['ciclo_tesis']."'
            AND id_due='".$insert['id_due']."'
            AND id_cargo='2'";  
            $this->db->query($sql); 
        }
        return $this->db->affected_rows();

    }


    function Update($update)
    {

                
        //Actualizacion a asignado
        $sql = "UPDATE asignado set id_docente='".$update['id_docente']."',
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
        return $this->db->affected_rows();

    }    

    function EncontrarLLavesDelete($llaves_delete)
    {
        $this->db->select('id_equipo_tg,anio_tesis,ciclo_tesis,id_docente');
        //$this->db->from('view_asig_docente_aux_pdg');
        $this->db->from('asig_docente_aux_pdg');
        $this->db->where('id',$llaves_delete['primary_key']);
        $query = $this->db->get();
        return $query->result_array();

    } 

    function Delete($delete)
    {
        $sql = "DELETE FROM asignado
                 WHERE id_due='".$delete['id_due']."'
                 AND id_docente='".$delete['id_docente']."'
                 AND id_proceso='".$delete['id_proceso']."'
                 AND id_cargo='".$delete['id_cargo']."'";


        $this->db->query($sql);
        return $this->db->affected_rows();
    }    



    function Get_Datos_Tema($get)
    {
        $this->db->select('tema,anio_tg,ciclo_tg');
        $this->db->from('pdg_equipo_tg');
        $this->db->where('id_equipo_tg',$get);
        $query = $this->db->get();
        return $query->result();

    } 
    function Get_Datos_Docentes($get)
    {
        $this->db->select('nombre,apellido,email');
        $this->db->from('gen_docente');     
        $this->db->where('id_docente',$get);
        $query = $this->db->get();
        return $query->result();

    } 
    function Get_Valida_Data($get1,$get2)
    {
        
        /*Valida si el docente asesor de trabajo de graduacion ya ha sido ingresado para el mismo equipo */
        /*Se obtiene un id_due comodin del equipo seleccionado*/
        $this->db->select('id_due');
        $this->db->from('conforma');     
        $this->db->where('id_equipo_tg',$get1);
        $this->db->limit(1);
        $query = $this->db->get();
        
        foreach ($query->result_array() as $row)
        {
                $resultado=$row['id_due'];

        }

        /*Con el id_due comodin se consulta si previamente se le ha asignado el docente seleccionado en el formulario*/
        $sql ="SELECT * FROM asignado
                 WHERE id_due='".$resultado."'
                 AND id_docente='".$get2."'";
        $query = $this->db->query($sql) ;
        $cantidad=$query->num_rows();
        if (!$cantidad){
            //Si no hay datos
            return 0;
        }
        else{
            //Si hay datos
            return 1;
        }


    }     

    function BuscarDUEsEquipo($aux1,$aux2,$aux3){
            


        /*$this->db->select('id_due');
        $this->db->from('conforma');
        $this->db->where('id_equipo_tg',$aux1);
        $query = $this->db->get();
        return $query->result_array();*/


        $sql ="SELECT id_due FROM conforma
                 WHERE id_equipo_tg='".$aux1."'
                 AND anio_tg='".$aux2."'
                 AND ciclo_tg='".$aux3."'";
        $query = $this->db->query($sql) ; 
        return $query;       

    }   
     
    function ConsultaDocenteAsesorOld($aux1){

        $this->db->select('id_docente');
        $this->db->from('asig_docente_aux_pdg');
        $this->db->where('id',$aux1);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $resultado=$row['id_docente'];
        }
        return $resultado;
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

