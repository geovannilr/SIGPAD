<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ConsolNotas_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $tipo = $this->session->userdata('tipo');
        $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
        $id_cargo = $this->session->userdata('id_cargo');
        $tipo_estudiante = $this->session->userdata('tipo_estudiante');
        $id_doc_est = $this->session->userdata('id_doc_est');
        $nombre = $this->session->userdata('nombre');
        $apellido = $this->session->userdata('apellidos');
        if  (
            ($tipo == 'Estudiante' and 
            ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) or
            ($tipo == 'Docente' and
                (
                    $id_cargo_administrativo == '4' or $id_cargo_administrativo == '1' or
                    ($id_cargo_administrativo == '6' and ($id_cargo == '2' or $id_cargo == '5'
                        or $id_cargo == '6'))
                )   
            )
        ) {
            $this->load->database();
            $this->load->helper('url');
            $this->load->model('PDG/ConsolNotas_gc_model');
            $this->load->library('grocery_CRUD');
        }
        else{
            redirect('Login');
        }
    }
    public function _ConsolNotas_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='PDG/FormConsolNotas_gc';
        $this->load->view('templates/content',$data);
                      
    }


    public function index()
    {

            $tipo = $this->session->userdata('tipo');
            $id_doc_est = $this->session->userdata('id_doc_est');
            
            $crud = new grocery_CRUD();
            //Seteando la tabla o pdg_subir_doc_temp
            $crud->set_table('view_consolidado_notas_pdg');
            if ($tipo == 'Estudiante'){
                //Encontrar id_equipo_tg en base al id_due logueado
                $id_equipo_tg_filtrar=$this->ConsolNotas_gc_model->EncontrarIdEquipoFiltrar($id_doc_est);                
                $crud->where('id_equipo_tg',$id_equipo_tg_filtrar);
            }             
            $crud->set_primary_key('id_consolidado_notas');
            $crud->set_subject('Consolidado de Notas');
            $crud->set_language('spanish');
            //Definiendo las columnas que apareceran
            $crud->columns('id_consolidado_notas','id_equipo_tg','tema','anio_tg','ciclo_tg','id_due','nota_anteproyecto','nota_etapa1','nota_etapa2','nota_defensa_publica','nota_final');
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_consolidado_notas','Correlativo')
                 ->display_as('id_equipo_tg','Id. Equipo TG')
                 ->display_as('tema','Tema TG')
                 ->display_as('anio_tg','Año TG')
                 ->display_as('ciclo_tg','Ciclo TG')
                 ->display_as('id_due','DUE')
                 ->display_as('nota_anteproyecto','Nota Anteproyecto')
                 ->display_as('nota_etapa1','Nota Etapa 1')
                 ->display_as('nota_etapa2','Nota Etapa 2')
                 ->display_as('nota_defensa_publica','Nota Defensa Pública')
                 ->display_as('nota_final','Nota Final');
           
            //Valifación de columnas
            $crud->callback_column('nota_anteproyecto',array($this,'notaAnteproyectoRound'));
            $crud->callback_column('nota_etapa1',array($this,'notaEtapa1Round'));
            $crud->callback_column('nota_etapa2',array($this,'notaEtapa2Round'));
            $crud->callback_column('nota_defensa_publica',array($this,'notaDefensaPublicaRound'));
            $crud->callback_column('nota_final',array($this,'notaFinalRound'));


            //Desabilitando opcion de agregar
            $crud->unset_add();
            //Desabilitando opcion de editar
            $crud->unset_edit();            
            //Desabilitando opcion de eliminar
            $crud->unset_read();
            $crud->unset_delete();
            $crud->add_action('Anteproyecto','','','glyphicon glyphicon-download',array($this,'ActaAnteproy'));        
            $crud->add_action('Etapa1', '','','glyphicon glyphicon-download',array($this,'ActaEtapa1'));        
            $crud->add_action('Etapa2', '','','glyphicon glyphicon-download',array($this,'ActaEtapa2'));        
            $crud->add_action('Defensa_Publica', '','','glyphicon glyphicon-download',array($this,'ActaDefensaPublica'));        
            $output = $crud->render();
            $this->_ConsolNotas_output($output);
    }

  
    function ActaAnteproy($primary_key )
    {
            return site_url('PDG/GenActaAnteproy_gc/generar/'.$primary_key );
    }
    function ActaEtapa1($primary_key )
    {
            return site_url('PDG/GenActaEtapa1_gc/generar/'.$primary_key );
    }
    function ActaEtapa2($primary_key )
    {
            return site_url('PDG/GenActaEtapa2_gc/generar/'.$primary_key );
    }
    function ActaDefensaPublica($primary_key )
    {
            return site_url('PDG/GenDefensaPublica_gc/generar/'.$primary_key );
    }        

    function notaAnteproyectoRound($value){
        return round($value,1);
    } 
  
    function notaEtapa1Round($value){
        return round($value,1);
    } 

    function notaEtapa2Round($value){
        return round($value,1);
    } 

    function notaDefensaPublicaRound($value){
        return round($value,1);
    }     
    function notaFinalRound($value){
        return round($value,1);
    }          
}