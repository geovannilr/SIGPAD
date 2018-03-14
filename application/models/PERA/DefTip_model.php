<?php
class DefTip_model extends CI_Model{

    function __construct(){
    	parent::__construct();
        $this->load->database();
    }
    
    function obtener_estudiante(){
        //$query = $this->db->get('pdg_perfil');
        $query = $this->db->get('gen_estudiante');
        
        return $query->result_array();
    }
    
      
    function get_Due()
    {
        $query = $this->db->query('SELECT id_due FROM per_detalle');
        return $query->result_array();
    } 
    
    function get_Docente()
    {
        /*$sql = "SELECT area_deficitaria as id_docente FROM per_detalle 
                    WHERE id_due = 'aa12345';";
        $query = $this->db->query($sql);*/
        $query = $this->db->query('SELECT id_docente FROM gen_docente');
      
        return $query->result_array();
    } 
    

    function Actualizar($insertar)
    {

        $query = "CALL per_proc_def_tip_update('".$insertar['id_tipo_pera']."',
                                                '".$insertar['tipo']."',
                                                '".$insertar['descripcion']."',
                                                '".$insertar['inicio']."',                                                        
                                                '".$insertar['fin']."');";   

        $query = $this->db->query($query);
            
        //$query = $this->db->query("CALL per_proc_asi_doc_update('FM09001','".$insertar['id_docente']."');");

        /*$query = $this->db->query("CALL per_proc_insertar_def_tip(
                    '".$insertar['id_due']."','".$insertar['id_due']."','".$insertar['Tipo_Pera']."','".$insertar['Unidades_Valorativas']."','".$insertar['Descripcion']."','".$insertar['Inicio']."','".$insertar['Fin']."');");
        */
        //$query = $this->db->query("CALL per_proc_insertar_def_tip('500','1','1','1','1');");
                                
        return $this->db->affected_rows();
           
            
            
            
    }
    
    function Eliminar($id_tipo_pera){

        $query="CALL per_proc_def_tip_delete('".$id_tipo_pera."');";

        $query=$this->db->query($query);

        return $this->db->affected_rows();
    }

    public function ComprobarFK($id){

        $this->db->select('id_evaluacion');
        $this->db->from('per_evaluacion');
        $this->db->where('id_tipo_pera',$id);
        $query = $this->db->get();
        foreach ($query->result_array() as $row)
        {
                $filas = $row['id_evaluacion'];
        }
        return $filas;
    } 
}

?>
