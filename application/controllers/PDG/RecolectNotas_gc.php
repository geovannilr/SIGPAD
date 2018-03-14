<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RecolectNotas_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        if($id_cargo_administrativo=='4'){
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/RecolectNotas_gc_model');
            $this->load->model('Pdfs_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            refirect('Login');
        }
    }
    public function _RecolectNotas_gc_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormRecolectNotas_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {

            /*Resentear el contador de id utilizados para las vistas*/
            $sql = "SELECT func_reset_inc_var_session()";
            $this->db->query($sql); 

            $sql="TRUNCATE TABLE pdg_recolector_notas_temp"; 
            $this->db->query($sql);
            $sql="INSERT INTO pdg_recolector_notas_temp(id,id_equipo_tg,anio_tg,ciclo_tg,tema,sigla)
                  SELECT func_inc_var_session(),id_equipo_tg,anio_tg,ciclo_tg,tema,sigla FROM view_recolector_notas_pdg";
            $this->db->query($sql);

            $crud = new grocery_CRUD();
            //$crud->set_theme('datatables');
            //Seteando la tabla o vista
            $crud->set_table('pdg_equipo_tg');
            $crud->order_by('id_equipo_tg');
            $crud->set_language('spanish');         
            $crud->set_subject('Recolecto de Notas');  
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
            $crud->add_action('Recolector Notas','','','glyphicon glyphicon-download',array($this,'just_a_test'));        
            $output = $crud->render();

            $this->_RecolectNotas_gc_output($output);
    }
 
    function just_a_test($primary_key )
    {
            return site_url('PDG/GenRecolectNotas_gc/generar/'.$primary_key );
    }

}

?>
