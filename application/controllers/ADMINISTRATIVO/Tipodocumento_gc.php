<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tipodocumento_gc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->model('ADMINISTRATIVO/Tipodocumento_gc_model');
        $this->load->library('grocery_CRUD');
        
    }
    public function _Tipodocumento_output($output = null)
    {

        $data['output'] = $output;
        $data['contenido']='ADMINISTRATIVO/FormTipodocumento_gc';
        $this->load->view('templates/content',$data);
                      
    }

    public function index()
    {
            $crud = new grocery_CRUD();
            //Seteando la tabla o vista
            $crud->set_table('pss_tipo_documento');
			$crud->set_primary_key('id_tipo_documento_pss');
            $crud->order_by('CAST(id_tipo_documento_pss AS UNSIGNED)','asc');
            $crud->set_language('spanish');
            $crud->set_subject('Tipo Documento Expediente');  
            //Cambiando nombre a los labels de los campos
            $crud->display_as('id_tipo_documento_pss','Codigo Tipo')
                 ->display_as('descripcion','Descripcion');            
            //Definiendo los input que apareceran en el formulario de inserción
            $crud->fields('descripcion');   
            //Definiendo los campos obligatorios
            $crud->required_fields('descripcion');   
            //Desabilitando  el wisywig
            $crud->unset_texteditor('descripcion','full_text');
			$crud->edit_fields('id_tipo_documento_pss','descripcion');
            //$crud->callback_edit_field('id_tipo_documento_pss',array($this,'IdLectura'));
            $crud->callback_insert(array($this,'insercion_Tipodocumento'));
			$crud->callback_delete(array($this,'eliminar_Tipodocumento'));
			$crud->callback_update(array($this,'actualizacion_Tipodocumento'));
			$crud->unset_export();///deshabilita excel
	  		$crud->unset_print();///deshabilita impresion
	  		$crud->unset_read();///deshabilita lupa

            $output = $crud->render();

            $this->_Tipodocumento_output($output);
    }
	
	function IdLectura($value){
        return '<input type="text" id="field-id" name="field-id" value="'.$value.'" readonly>';
    }
     public function insercion_Tipodocumento($post_array)
	 {

            $insertar['descripcion']=$post_array['descripcion'];
            $comprobar=$this->Tipodocumento_gc_model->Create($insertar);
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

public function eliminar_Tipodocumento($primary_key)
	{

            $llaves_delete['primary_key']=$primary_key;

            $llaves=$this->Tipodocumento_gc_model->EncontrarLLavesDelete($llaves_delete);
            foreach ($llaves as $row)
            {
                    $id_tipo_documento_pss=$row['id_tipo_documento_pss'];
					$delete['id_tipo_documento_pss']=$id_tipo_documento_pss;
					$comprobar=$this->Tipodocumento_gc_model->Delete($delete);
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
	        function actualizacion_Tipodocumento($post_array)
		{
            $update['id_tipo_documento_pss']=$post_array['id_tipo_documento_pss'];
            $update['descripcion']=$post_array['descripcion'];
			$comprobar=$this->Tipodocumento_gc_model->Update($update);
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