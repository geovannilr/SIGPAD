<?php
class Perfil_gc_model extends CI_Model{

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

    function ObtenerCorreoCoordinadorPDG(){
        $sql = "SELECT email FROM gen_docente
                WHERE id_cargo='1'
                AND id_cargo_administrativo='4'";
        $query = $this->db->query($sql);                
        foreach ($query->result_array() as $row)
        {
                $email=$row['email'];
        }
        if (empty($email)){
          $email=null;
        }
        return $email;
    }  

    function Update($update)
    {

        //Obteniendo el id_documento_pdg  a partir del id_perfil
        $sql="select id_detalle_pdg,id_documento_pdg from pdg_perfil       
              where id_perfil='".$update['id_perfil']."'";
        $query = $this->db->query($sql);
        
        foreach ($query->result_array() as $row)
        {
                $id_detalle_pdg=$row['id_detalle_pdg'];
                $id_documento_pdg=$row['id_documento_pdg'];
                
        }

          /*Actualizando la ruta del documento*/  
          /**************$sql="UPDATE pdg_documento SET 
          ruta=".$this->db->escape($update['ruta'])."
          WHERE id_documento_pdg='".$id_documento_pdg."'
          and id_detalle_pdg='".$id_detalle_pdg."'"; ***************/

        $this->db->query($sql);
        
        //Actualiacion a pdg_perfil
          $sql="UPDATE pdg_perfil SET 
          ciclo='".$update['ciclo_perfil']."',
          anio='".$update['anio_perfil']."',
          objetivo_general='".$update['objetivo_general']."',
          objetivo_especifico='".$update['objetivo_especifico']."',
          descripcion='".$update['descripcion']."',
          area_tematica_tg='".$update['area_tematica_tg']."',
          resultados_esperados_tg='".$update['resultados_esperados_tg']."'
          WHERE id_perfil='".$update['id_perfil']."'
          and id_detalle_pdg='".$id_detalle_pdg."'";  

          $this->db->query($sql);

        return $this->db->affected_rows();


    }    


}
?>

