<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Rubro_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->model('ADMINISTRATIVO/Rubro_gc_model');
        $this->load->library('grocery_CRUD');
        
    }
    public function _Rubro_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='ADMINISTRATIVO/FormRubro_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('pss_rubro');
			$crud->set_primary_key('id_rubro');
            $crud->order_by('CAST(id_rubro AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Rubro Institucion');  
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_rubro','Codigo Rubro')
                 ->display_as('rubro','Descripcion');            
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('rubro');   
            //Definiendo los campos obligatorios
            $crud->required_fields('rubro');   
            //Desabilitando  el wisywig
            $crud->unset_texteditor('rubro','full_text');
			$crud->edit_fields('id_rubro','rubro');
            //$crud->callback_edit_field('id_rubro',array($this,'RubroLectura'));
            $crud->callback_insert(array($this,'insercion_Rubro'));
			$crud->callback_delete(array($this,'eliminar_Rubro'));
			$crud->callback_update(array($this,'actualizacion_Rubro'));
			$crud->unset_export();///deshabilita excel
	  		$crud->unset_print();///deshabilita impresion
	  		$crud->unset_read();///deshabilita lupa

            $output = $crud->render();

            $this->_Rubro_output($output);
    }
	
	function RubroLectura($value){
        return '<input type="text" id="field-rubro" name="field-rubro" value="'.$value.'" readonly>';
    }
     public function insercion_Rubro($post_array)
	 {

            $insertar['rubro']=$post_array['rubro'];
            $comprobar=$this->Rubro_gc_model->Create($insertar);
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

public function eliminar_Rubro($primary_key)
	{

            $llaves_delete['primary_key']=$primary_key;

            $llaves=$this->Rubro_gc_model->EncontrarLLavesDelete($llaves_delete);
            foreach ($llaves as $row)
            {
                    $id_rubro=$row['id_rubro'];
					$delete['id_rubro']=$id_rubro;
					$comprobar=$this->Rubro_gc_model->Delete($delete);
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
	        function actualizacion_Rubro($post_array)
		{
            $update['id_rubro']=$post_array['id_rubro'];
            $update['rubro']=$post_array['rubro'];
			$comprobar=$this->Rubro_gc_model->Update($update);
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