<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Modalidad_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->model('ADMINISTRATIVO/Modalidad_gc_model');
        $this->load->library('grocery_CRUD');
        
    }
    public function _Modalidad_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='ADMINISTRATIVO/FormModalidad_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('pss_modalidad');
			$crud->set_primary_key('id_modalidad');
            $crud->order_by('CAST(id_modalidad AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Modalidad Institucion');  
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_modalidad','Codigo Modalidad')
                 ->display_as('modalidad','Descripcion');            
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('modalidad');   
            //Definiendo los campos obligatorios
            $crud->required_fields('modalidad');   
            //Desabilitando  el wisywig
            $crud->unset_texteditor('modalidad','full_text');
			$crud->edit_fields('id_modalidad','modalidad');
            //$crud->callback_edit_field('id_rubro',array($this,'RubroLectura'));
            $crud->callback_insert(array($this,'insercion_Modalidad'));
			$crud->callback_delete(array($this,'eliminar_Modalidad'));
			$crud->callback_update(array($this,'actualizacion_Modalidad'));
			$crud->unset_export();///deshabilita excel
	  		$crud->unset_print();///deshabilita impresion
	  		$crud->unset_read();///deshabilita lupa

            $output = $crud->render();

            $this->_Modalidad_output($output);
    }
	
	function ModalidadLectura($value){
        return '<input type="text" id="field-modalidad" name="field-modalidad" value="'.$value.'" readonly>';
    }
     public function insercion_Modalidad($post_array)
	 {

            $insertar['modalidad']=$post_array['modalidad'];
            $comprobar=$this->Modalidad_gc_model->Create($insertar);
            if ($comprobar >=1)
			{
                print ('SE INSERTO');
				return 1;
            }
            else
			{
                //significa que no se realizo la operacion DML
                print ('NO SE INSERTO');
				return 0;
            }                           
        } 

///////ELIMINACION DE CATALOGO

public function eliminar_Modalidad($primary_key)
	{

            $llaves_delete['primary_key']=$primary_key;

            $llaves=$this->Modalidad_gc_model->EncontrarLLavesDelete($llaves_delete);
            foreach ($llaves as $row)
            {
                    $id_modalidad=$row['id_modalidad'];
					$delete['id_modalidad']=$id_modalidad;
					$comprobar=$this->Modalidad_gc_model->Delete($delete);
                    if ($comprobar >=1){
                        $huboError=0;
                        //return true;
                    }
                    else{
                        //significa que no se realizo la operacion DML
                        $huboError=1;
                        //return false;
                    }          
                    
            } 


           
    }
	//////////////////ACTUALIZACION RUBRO
	        function actualizacion_Modalidad($post_array)
		{
            $update['id_modalidad']=$post_array['id_modalidad'];
            $update['modalidad']=$post_array['modalidad'];
			$comprobar=$this->Modalidad_gc_model->Update($update);
            if ($comprobar >=1)
			{
               print("SE INSERTO VALORES");
			    return true;
            }
            else
			{
                print("NO SE INSERTO VALORES");
				return false;
            }            
        }

//////////////////////////////////////
}