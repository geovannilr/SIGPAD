<?php
class ParamGenerales_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    
function Create($insert)
    {
          //$this->db->insert('mytable', $data);  // Produces: INSERT INTO mytable (title, name, date) VALUES ('{$title}', '{$name}', '{$date}')
          /*$this->db->insert('pdg_perfil',$insert);
          return $this->db->affected_rows();*/

            //$sql = "INSERT INTO pdg_perfil (id_perfil,id_detalle_pdg,ciclo,anio,objetivo_general,objetivo_especifico,descripcion) VALUES (".$this->db->escape($title).", ".$this->db->escape($name).")";
            $sql = "INSERT INTO pdg_perfil (id_perfil,id_detalle_pdg,ciclo,anio,objetivo_general,objetivo_especifico,descripcion) 
            VALUES (44,
                    1,
                    ".$this->db->escape($insert['ciclo']).",
                    ".$this->db->escape($insert['anio']).",
                    ".$this->db->escape($insert['obj_general']).",
                    ".$this->db->escape($insert['obj_especifico']).",
                    ".$this->db->escape($insert['descrip_proyecto'])."
                    )";
            $this->db->query($sql);
            //$filas_afectadas=$this->db->affected_rows();
            return $this->db->affected_rows();
            //return false;

    }
   
}

?>

