<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RemiEjemplares_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='4'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/RemiEjemplares_gc_model');
            $this->load->model('Pdfs_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _RemiEjemplares_gc_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormRemiEjemplares_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            /*Resentear el contador de id utilizados para las vistas*/
            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            $sql="TRUNCATE TABLE pdg_remision_notas_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_remision_notas_temp(id,id_equipo_tg,anio_tg,ciclo_tg,tema,sigla)
                  SELECT func_inc_var_session(),id_equipo_tg,anio_tg,ciclo_tg,tema,sigla FROM view_remision_notas_pdg";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //$crud->set_theme('datatables');
            //Seteando la tabla o vista
            $crud->set_table('pdg_equipo_tg');
            $crud->order_by('CAST(id_equipo_tg AS UNSIGNED)','asc');
            $crud->set_language('spanish');         
            $crud->set_subject('Remisión de Ejemplares');  
            $crud->columns('id_equipo_tg','anio_tg','ciclo_tg','tema','sigla');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_equipo_tg','Id. Equipo TG')            
                 ->display_as('anio_tg','Año TG')  
                 ->display_as('ciclo_tg','Ciclo TG') 
                 ->display_as('tema','Tema TG')
                 ->display_as('sigla','Siglas');   
            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de editar
            $crud->unset_edit();            
            //Desabilitando opcion de eliminar
            $crud->unset_delete();
            $crud->add_action('Remisión','','','glyphicon glyphicon-download',array($this,'just_a_test'));        
            $output = $crud->render();

            $this->_RemiEjemplares_gc_output($output);
    }
 
    function just_a_test($primary_key )
    {
            return site_url('PDG/GenRemiEjemplares_gc/generar/'.$primary_key );
    }

}

?>
