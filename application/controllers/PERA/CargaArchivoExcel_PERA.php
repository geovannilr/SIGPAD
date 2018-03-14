<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CargaArchivoExcel_PERA extends CI_Controller{

function __construct(){
    parent::__construct();
    $id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
    if($id_cargo_administrativo=='2'){
    	$this->load->helper(array('form', 'url'));
        //load the excel library
        $this->load->library('excel');
        $this->load->library('email');
        //$this->removeCache();
        $this->load->model('PERA/CargaArchivoExcel_PERA_model');
    }
    else{
        redirect('Login');
    }
}

public function index($mensaje = null){
    $data['mensaje'] = $mensaje;
    $data['output'] = null;
    $data['contenido']= 'PERA/FormCargaArchivoExcel_PERA';
    $this->load->view('templates/content',$data);

 //   $this->load->view('FormCargaArchivoExcel', array('error' => ' ' ));
}

public function CargaArchivo(){
    //SE CREARÁ UN ARCHIVO CON NOMBRE ALEATORIO 
    //DEBIDO AL CASO QUE SE PUEDEN CARGAS 2 ARCHIVOS SIMULTANEAMENTE POR 2 USUARIOS DIFERENTES
    $nombrearchivo = uniqid();
    $config['upload_path']          = './files/';
    $config['file_name']			= $nombrearchivo;//'test';
    $config['overwrite']			= true;
    $config['allowed_types']        = 'xls|xlsx';
    // $config['max_size']             = 100;
    // $config['max_width']            = 1024;
    // $config['max_height']           = 768;

    $this->load->library('upload', $config);

    if ( ! $this->upload->do_upload('userfile')){
            $error = $this->upload->display_errors();
            $this->index($error);
    }
    else{
        
        $file = './files/'.$nombrearchivo.'.xlsx';
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        //get only the Cell Collection
        //echo($objPHPExcel->canRead());

        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            //columna
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            //fila
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            //valor
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

            $arr_data[$row][$column] = $data_value;

            //header will/should be in row 1 only. of course this can be modified to suit your need.
            //if ($row == 1) {
                //$header[$row][$column] = $data_value;
            //} else {
            //}
        }
        //send the data in an array format
        //$data['header'] = $header;
        $data = $arr_data;
        //echo(json_encode($data));
        $url= BASE_URL;
        
        //CONTADORES
        $cuenta_PERA= 0;
        
        $mensaje_email = '';
        foreach($data as $row) {
            //SI LLEVA TODOS LOS DATOS SE INCLUYE, SINO SE PASA DE LARGO
            if(!empty($row['A']) and !empty($row['B']) and !empty($row['C']) and !empty($row['D']) and !empty($row['E']) and !empty($row['F']) and !empty($row['G']) and !empty($row['H']) ){
                    $enviar['id_due'] = substr($row['A'],0,7);
                    $enviar['apellido'] = substr($row['B'],0,25);
                    $enviar['nombre'] = substr($row['C'],0,30);
                    $enviar['cum'] = $row['D'];
                    $enviar['materia'] = substr($row['F'],0,7);
                    $enviar['nota'] = $row['G'];
                    $enviar['email'] = substr($row['H'],0,50);
                    if($row['E']>=100){
                        
                        $email = $enviar['email']; //$enviar['id_due'].$correo_institucional;
                        //echo("SERVICIO SOCIAL");
                        $verificar_PERA = $this->CargaArchivoExcel_PERA_model->InsertarLineaPERA($enviar);
                        
                        switch($verificar_PERA['RETORNA']){
                            case 8:
                                $cuenta_PERA++;
                                $mensaje_email = 'Se asignó la siguiente materia a su Detalle de PERA: '.$enviar['materia']
                                .$url;
                                break;

                            //CASO 14 ES SOLAMENTE QUE SE ASIGNÓ UN NUEVO ROL DEL USUARIO AUNQUE NO SE CREO EL USUARIO DESDE CERO
                            case 14:
                                $cuenta_PERA++;
                                $mensaje_email = 'Se ha asignado su Usuario para el Proceso de PERA. Se asignó la siguiente materia a su Detalle de PERA: '.$enviar['materia'].$url;
                                break;
                            //CASO 15 ES QUE SE CREO USUARIO Y ESTUDIANTE Y SE ASIGNO OTRO PROCESO
                            case 15:
                                $cuenta_PERA++;
                                $mensaje_email = 'Se ha creado su usuario para Proceso de PERA, sus credenciales son Usuario: '.$enviar['id_due'].', Password: '.$enviar['id_due'].'. favor de seguir el siguiente link, si no puedes hacer click, favor de copiar y pegarlo en la barra de direcciones de tu navegador. '
                                    .$url;
                                $this->email->from('sigpa.fia.ues@gmail.com','Sistema Informático para el Control de Procesos Académicos-Administrativos UES');
                                $this->email->to($email);
                                $this->email->subject('Notificación: Creación de Usuario de PERA');
                                $this->email->message($mensaje_email);
                                $this->email->send();
                                break;
                        }//switch
                        
                        
                        
                    }

            }


        }
        $mensaje = "RESULTADO: Registros Insertadas a PERA ".$cuenta_PERA;
        $this->index($mensaje);

                
    }

}










	public function LeeExcel(){

		
	$file = './files/test.xlsx';

		//load the excel library
$this->load->library('excel');



//read file from path
$objPHPExcel = PHPExcel_IOFactory::load($file);
//get only the Cell Collection
//echo($objPHPExcel->canRead());

$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
//extract to a PHP readable array format
foreach ($cell_collection as $cell) {
    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
    //header will/should be in row 1 only. of course this can be modified to suit your need.
    if ($row == 1) {
        $header[$row][$column] = $data_value;
    } else {
        $arr_data[$row][$column] = $data_value;
    }
}
//send the data in an array format
//$data['header'] = $header;
$data['values'] = $arr_data;
	echo(json_encode($data));
	}

}