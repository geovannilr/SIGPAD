<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remisiones_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        
        if($id_cargo_administrativo=='3'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PSS/Remisiones_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _Remisiones_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PSS/FormRemisiones_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {


            /*Tabla temporal utilizada para alojar los expedientes que ya estan remitidos*/
            $sql="TRUNCATE TABLE expedientes_remitidos_tmp_pss";   
            $this->db->query($sql);
            $sql="INSERT INTO expedientes_remitidos_tmp_pss SELECT * FROM view_expedientes_remitidos_pss";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('expedientes_remitidos_tmp_pss');
            $crud->order_by('id_due','asc');
            $crud->set_primary_key('id_due');
            $crud->set_language('spanish');
/////            $crud->set_relation('id_servicio_social','pss_servicio_social','nombre_servicio_social');
            $crud->set_subject('Cierre de Expediente de Servicio Social');  
            $crud->columns('id_due','remision','fecha_remision','total_horas');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_due','DUE')
                ->display_as('remision','¿Remitido?')          
                 ->display_as('fecha_remision','Fecha de Remisión') 
                 ->display_as('total_horas','Total de Horas Servidas') ;

    
    
            $crud->set_lang_string('form_active','SI');
            $crud->set_lang_string('form_inactive','NO'); 
            /////////////// 
      

            //Definiendo los campos obligatorios
            $crud->required_fields('fecha_remision');  

            //quitar la opcion de agregacion
            $crud->unset_add();
            //quitar la opcion de borrado
            $crud->unset_delete();
            //quitar la edicion
            $crud->unset_edit();
                              
            
            //$crud->callback_update(array($this,'actualizacion_Remisiones'));
            $crud->add_action('Resumen de expediente','','','glyphicon glyphicon-download',array($this,'ResumenExpediente'));                             
            $output = $crud->render();

            $this->_Remisiones_output($output);
    }

  function ResumenExpediente($primary_key )
{
        return site_url('PSS/GenResumenExpediente_gc/generar/'.$primary_key );
}    

}
?>