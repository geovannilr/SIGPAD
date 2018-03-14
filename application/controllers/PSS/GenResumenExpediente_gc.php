<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenResumenExpediente_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PSS/GenResumenExpediente_gc_model');

    }
    
    public function index()
    {
        //cargamos la vista y pasamos el array $data['provincias'] para su uso
        $this->load->view('pdfs_view', $data);
    }

    public function generar($id) {
        $this->load->library('Pdf');
        $pdf = new Pdf(PDF_PAGE_ORIENTATION,'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Ruben Moran');
        $pdf->SetTitle('Acta Nota Etapa 1');
        $pdf->SetSubject('Acta Nota Etapa 1');
        $pdf->SetKeywords('TCPDF, PDF');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));

        // datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetLeftMargin(25);
        $pdf->SetRightMargin(25);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        //relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        // ---------------------------------------------------------
        // establecer el modo de fuente por defecto
        $pdf->setFontSubsetting(true);

        // Establecer el tipo de letra

        //Si tienes que imprimir carácteres ASCII estándar, puede utilizar las fuentes básicas como
        // Helvetica para reducir el tamaño del archivo.
        $pdf->SetFont('Helvetica', '',9);
        
        // Añadir la primer página
        $pdf->AddPage('L', 'A4');
        
       
        // Este método tiene varias opciones, consulta la documentación para más información.
        //$pdf->AddPage();
      
        $datos['id_due']=$id;
        /********$resultado=$this->GenActaEtapa1_gc_model->DatosEquipo($datos_equipo);      
        foreach ($resultado->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
                $anio_tg=$row['anio_tg'];
                $ciclo_tg=$row['ciclo_tg'];
                $tema=$row['tema'];
        }     
        $nombreDocenteAsesor=strtoupper ($this->GenActaEtapa1_gc_model->DatosDocenteAsesor($id_equipo_tg,$anio_tg,$ciclo_tg));*********/
 
        // Establecemos el contenido para imprimir

        //Calculo del año actual
        //$anio =date ("Y"); 
        //Calculo de la fecha actual
        /*$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        $mes=date('m');
        $dia=date('d');*/
        //$html  =    '<p style="text-align: right;">Ref. EISI-002-'.$anio.'</p>';
        
	 
        //obtener datos del alumno de servicio social
		$array_datos_alumno=$this->GenResumenExpediente_gc_model->ObtenerDatosAlumno($datos); 
        foreach ($array_datos_alumno->result_array() as $row)
        {
                $nombre_apellido_alumno=$row['nombre_apellido_alumno'];
                $apertura_expediente_pss=$row['apertura_expediente_pss'];
                $fecha_remision=$row['fecha_remision'];
                $direccion=$row['direccion'];
                $email=$row['email'];
                $lugar_trabajo=$row['lugar_trabajo'];
                $telefono_trabajo=$row['telefono_trabajo'];
                $observaciones_exp_pss=$row['observaciones_exp_pss'];
        }  

        //variable donde se acumulara el total horas sociales servidas
        $total_horas_servidas=0;
        //variable donde se acumulara el total del monto segun horas sociales servidas
        $total_monto=0;
        //variable donde se acumulara el total de beneficiarios directos
        $total_bene_d=0;
        //variable donde se acumulara el total de beneficiarios indirectos
        $total_bene_i=0; 

        $html =    '<table  style="height: 26px; width: 155px;" >
					<tbody>
					<tr>
					<td style="width: 186px;">&nbsp;</td>
					<td style="width: 486px;">&nbsp;</td>
					<td style="width: 186px;" rowspan="4" border="1">
						<table >
						<tbody>
						<tr>
						<td style="text-align: center;">CERTIFICADO EMITIDO</td>
						</tr>
						<tr>
						<td style="text-align: center;">&nbsp;</td>
						</tr>
						</tbody>
						</table>
					</td>
					</tr>
					<tr>
					<td style="width: 186px;">&nbsp;</td>
					<td style="width: 486px; text-align: center;"><strong>HOJA EXPEDIENTE</strong></td>
					</tr>
					<tr>
					<td style="width: 186px;">&nbsp;</td>
					<td style="width: 486px; text-align: center;">UNIVERSIDAD DE EL SALVADOR</td>
					</tr>
					<tr>
					<td style="width: 186px;">&nbsp;</td>
					<td style="width: 486px; text-align: center;">FACULTAD DE INGENIERIA Y ARQUITECTURA</td>
					</tr>
					<tr>
					<td style="width: 186px;">&nbsp;</td>
					<td style="width: 486px; text-align: center;">ESCUELA DE INGENIERIA SISTEMAS INFORMATICOS</td>
					<td style="width: 186px;">&nbsp;</td>
					</tr>
					<tr>
					<td style="width: 186px;">&nbsp;</td>
					<td style="width: 486px; text-align: center;">SUBUNIDAD DE PROYECCION SOCIAL</td>
					<td style="width: 186px;">&nbsp;</td>
					</tr>
					<tr>
					<td style="width: 186px;">&nbsp;</td>
					<td style="width: 486px;">&nbsp;</td>
					<td style="width: 186px;">&nbsp;</td>
					</tr>
					</tbody>
					</table>';
		$html.=	   '<table style="height: 11px;" width="364">
					<tbody>
					<tr>
					<td style="width: 354px;">&nbsp;</td>
					</tr>
					</tbody>
					</table>';
 		$html.=	   '<table style="height: 11px;" width="860">
					<tbody>
					<tr>
					<td style="width: 860px; text-align: center;"><strong>RESUMEN DE SERVICIO SOCIAL DESARROLLADO</strong></td>
					</tr>
					</tbody>
					</table>';
 		$html.=	   '<table style="height: 11px;" width="364">
					<tbody>
					<tr>
					<td style="width: 354px;">&nbsp;</td>
					</tr>
					</tbody>
					</table>';
		$html.=	   '<table border="1" style="height: 9px;" width="860">
					<tbody>
					<tr style="height: 137px;">
					<td style="width: 860px; height: 137px;">
					<table style="height: 125px;" width="541">
					<tbody>
					<tr>
					<td style="width: 860px;"><span style="text-decoration: underline;"><strong>DATOS DE INSCRIPCION:</strong></span></td>
					</tr>
					<tr>
					<td style="width: 860px;">&nbsp;</td>
					</tr>					
					<tr>
					<td style="width: 860px;">ALUMNO: '.$nombre_apellido_alumno.' CARNE: '.$datos['id_due'].'</td>
					</tr>
					<tr>
					<td style="width: 860px;">FECHA APERTURA DE EXPEDIENTE:'.$apertura_expediente_pss.' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;TELEFONOS:22336699</td>
					</tr>
					<tr>
					<td style="width: 860px;">DIRECCION: '.$direccion.'</td>
					</tr>
					<tr>
					<td style="width: 860px;">CORREO ELECTRONICO: '.$email.'</td>
					</tr>
					<tr>
					<td style="width: 860px;">LUGAR DE TRABAJO: '.$lugar_trabajo.' &nbsp; &nbsp;TELEFONO DE TRABAJO: '.$telefono_trabajo.'</td>
					</tr>
					<tr>
					<td style="width: 860px;">&nbsp;</td>
					</tr>
					</tbody>
					</table>
					</td>
					</tr>
					</tbody>
					</table>';
		$html.=	   '<table style="height: 11px;" width="364">
					<tbody>
					<tr>
					<td style="width: 354px;">&nbsp;</td>
					</tr>
					</tbody>
					</table>';
		$html.=	   '<table border="1" style="height: 94px;" width="593">
					<tbody>
					<tr style="height: 13px;">
					<td style="width: 30px; height: 25px; text-align: center;" rowspan="2"><strong>No</strong></td>
					<td style="width: 185px; height: 35px; text-align: center;" rowspan="2"><strong>Modalidad de Servicio Social/Lugar</strong></td>
					<td style="width: 180px; height: 35px; text-align: center;" rowspan="2"><strong>Tutor</strong></td>
					<td style="width: 190px; height: 13px; text-align: center;" colspan="2"><strong>Fecha</strong></td>
					<td style="width: 75px; height: 39px; text-align: center;" rowspan="2"><strong>Horas sociales asignadas</strong></td>
					<td style="width: 60px; height: 39px; text-align: center;" rowspan="2"><strong>Monto</strong></td>
					<td style="width: 90px; height: 40px; text-align: center;" colspan="2"><strong>Beneficiarios</strong></td>
					<td style="width: 50px; height: 39px; text-align: center;" rowspan="2"><strong>Estado</strong></td>
					</tr>
					<tr style="height: 26px;">
					<td style="width: 95px; height: 26px; text-align: center;">
						<strong>Inicio 
							(dd/mm/yyyy)
						</strong></td>
					<td style="width: 95px; height: 26px; text-align: center;">
						<strong>Finalizaci&oacute;n 
							(dd/mm/yyyy)
						</strong>
					</td>
					<td style="width: 45px; height: 20px; text-align: center;"><strong>D</strong></td>
					<td style="width: 45px; height: 20px; text-align: center;"><strong>I</strong></td>
					</tr>';
					//reinicio de contador de registros segun sesion activa
					$sql = "SELECT func_reset_inc_var_session()";
					$this->db->query($sql); 
					//validar existencia de 1° registro
					$id_due=$datos['id_due'];
					$posicion=1;
					$hay_datos_resumen_1=$this->GenResumenExpediente_gc_model->ValidarDatosResumen($id_due,$posicion); 					
			        if ($hay_datos_resumen_1==1){
						//reinicio de contador de registros segun sesion activa
						$sql = "SELECT func_reset_inc_var_session()";
						$this->db->query($sql); 			        	
				        //obtener datos del resumen de servicio social del alumno para el registro 1 de la tabla
				        $id_due=$datos['id_due'];
				        $posicion=1;
						$array_datos_resumen_1=$this->GenResumenExpediente_gc_model->ObtenerDatosResumen($id_due,$posicion); 
				        foreach ($array_datos_resumen_1->result_array() as $row)
				        {
				                $id_detalle_expediente1=$row['id_detalle_expediente'];
				                $servicio_social1=$row['servicio_social'];
				                $fecha_inicio1=$row['fecha_inicio'];
				                $fecha_fin1=$row['fecha_fin'];
				                $horas_sociales1=$row['horas_prestadas'];
				                $monto1=$row['monto'];
				                $beneficiario_directo1=$row['beneficiario_directo'];
				                $beneficiario_indirecto1=$row['beneficiario_indirecto'];
				                $estado1=$row['estado_case'];
				                $nombre_docente_asesor_ss1=$this->GenResumenExpediente_gc_model->ObtenerNombreApellidoDocenteAsesor($id_detalle_expediente1); 

				                //Acumulación de totales
						        $total_horas_servidas=$total_horas_servidas+$horas_sociales1;
						        $total_monto=$total_monto+$monto1;				                
						        $total_bene_d=$total_bene_d+$beneficiario_directo1;	
						        $total_bene_i=$total_bene_i+$beneficiario_indirecto1;							        
				        }  	
			        }else{
				                $servicio_social1=' ';
				                $fecha_inicio1=' ';
				                $fecha_fin1=' ';
				                $horas_sociales1=' ';
				                $monto1=' ';
				                $beneficiario_directo1=' ';
				                $beneficiario_indirecto1=' ';
				                $estado1=' ';		
				                $nombre_docente_asesor_ss1=' ';    
								
								//Acumulación de totales
						        $total_horas_servidas=$total_horas_servidas+0;
						        $total_monto=$total_monto+0;
						        $total_bene_d=$total_bene_d+0;	
						        $total_bene_i=$total_bene_i+0;							        				                				                    	        	
			        }			
		$html.=	    '<tr style="height: 13px;">
					<td style="width: 30px; height: 13px; text-align: center;"><strong>1</strong></td>
					<td style="width: 185px; height: 13px; text-align: left;">'.$servicio_social1.'</td>
					<td style="width: 180px; height: 13px; text-align: left;">'.$nombre_docente_asesor_ss1.'</td>
					<td style="width: 95px; height: 13px; text-align: center;">'.$fecha_inicio1.'</td>
					<td style="width: 95px; height: 13px; text-align: center;">'.$fecha_fin1.'</td>
					<td style="width: 75px; height: 13px; text-align: center;">'.$horas_sociales1.'</td>
					<td style="width: 60px; height: 13px; text-align: center;">'.$monto1.'</td>
					<td style="width: 45px; height: 13px; text-align: center;">'.$beneficiario_directo1.'</td>
					<td style="width: 45px; height: 13px; text-align: center;">'.$beneficiario_indirecto1.'</td>
					<td style="width: 50px; height: 13px; text-align: center;">'.$estado1.'</td>
					</tr>';
					//reinicio de contador de registros segun sesion activa
					$sql = "SELECT func_reset_inc_var_session()";
					$this->db->query($sql); 
					//validar existencia de 2° registro
					$id_due=$datos['id_due'];
					$posicion=2;
					$hay_datos_resumen_2=$this->GenResumenExpediente_gc_model->ValidarDatosResumen($id_due,$posicion); 					
			        if ($hay_datos_resumen_2==1){
						//reinicio de contador de registros segun sesion activa
						$sql = "SELECT func_reset_inc_var_session()";
						$this->db->query($sql); 			        	
				        //obtener datos del resumen de servicio social del alumno para el registro 2 de la tabla
				        $id_due=$datos['id_due'];
				        $posicion=2;
						$array_datos_resumen_2=$this->GenResumenExpediente_gc_model->ObtenerDatosResumen($id_due,$posicion); 
				        foreach ($array_datos_resumen_2->result_array() as $row)
				        {
				                $id_detalle_expediente2=$row['id_detalle_expediente'];
				                $servicio_social2=$row['servicio_social'];
				                $fecha_inicio2=$row['fecha_inicio'];
				                $fecha_fin2=$row['fecha_fin'];
				                $horas_sociales2=$row['horas_prestadas'];
				                $monto2=$row['monto'];
				                $beneficiario_directo2=$row['beneficiario_directo'];
				                $beneficiario_indirecto2=$row['beneficiario_indirecto'];
				                $estado2=$row['estado_case'];
				                $nombre_docente_asesor_ss2=$this->GenResumenExpediente_gc_model->ObtenerNombreApellidoDocenteAsesor($id_detalle_expediente2); 

								//Acumulación de totales
						        $total_horas_servidas=$total_horas_servidas+$horas_sociales2;
						        $total_monto=$total_monto+$monto2;	
						        $total_bene_d=$total_bene_d+$beneficiario_directo2;	
						        $total_bene_i=$total_bene_i+$beneficiario_indirecto2;							        				                
				        }  	
			        }else{
				                $servicio_social2=' ';
				                $fecha_inicio2=' ';
				                $fecha_fin2=' ';
				                $horas_sociales2=' ';
				                $monto2=' ';
				                $beneficiario_directo2=' ';
				                $beneficiario_indirecto2=' ';
				                $estado2=' ';		
				                $nombre_docente_asesor_ss2=' ';     

								//Acumulación de totales
						        $total_horas_servidas=$total_horas_servidas+0;
						        $total_monto=$total_monto+0;	
						        $total_bene_d=$total_bene_d+0;	
						        $total_bene_i=$total_bene_i+0;								        				                   	
			        }					
		$html.=	    '<tr style="height: 13px;">
					<td style="width: 30px; height: 13px; text-align: center;"><strong>2</strong></td>
					<td style="width: 185px; height: 13px; text-align: left;">'.$servicio_social2.'</td>
					<td style="width: 180px; height: 13px; text-align: left;">'.$nombre_docente_asesor_ss2.'</td>
					<td style="width: 95px; height: 13px; text-align: center;">'.$fecha_inicio2.'</td>
					<td style="width: 95px; height: 13px; text-align: center;">'.$fecha_fin2.'</td>
					<td style="width: 75px; height: 13px; text-align: center;">'.$horas_sociales2.'</td>
					<td style="width: 60px; height: 13px; text-align: center;">'.$monto2.'</td>
					<td style="width: 45px; height: 13px; text-align: center;">'.$beneficiario_directo2.'</td>
					<td style="width: 45px; height: 13px; text-align: center;">'.$beneficiario_indirecto2.'</td>
					<td style="width: 50px; height: 13px; text-align: center;">'.$estado2.'</td>
					</tr>';
					//reinicio de contador de registros segun sesion activa
					$sql = "SELECT func_reset_inc_var_session()";
					$this->db->query($sql); 
					//validar existencia de 3° registro
					$id_due=$datos['id_due'];
					$posicion=3;
					$hay_datos_resumen_3=$this->GenResumenExpediente_gc_model->ValidarDatosResumen($id_due,$posicion); 					
			        if ($hay_datos_resumen_3==1){
						//reinicio de contador de registros segun sesion activa
						$sql = "SELECT func_reset_inc_var_session()";
						$this->db->query($sql); 			        	
				        //obtener datos del resumen de servicio social del alumno para el registro 3 de la tabla
				        $id_due=$datos['id_due'];
				        $posicion=3;
						$array_datos_resumen_3=$this->GenResumenExpediente_gc_model->ObtenerDatosResumen($id_due,$posicion); 
				        foreach ($array_datos_resumen_3->result_array() as $row)
				        {
				                $id_detalle_expediente3=$row['id_detalle_expediente'];
				                $servicio_social3=$row['servicio_social'];
				                $fecha_inicio3=$row['fecha_inicio'];
				                $fecha_fin3=$row['fecha_fin'];
				                $horas_sociales3=$row['horas_prestadas'];
				                $monto3=$row['monto'];
				                $beneficiario_directo3=$row['beneficiario_directo'];
				                $beneficiario_indirecto3=$row['beneficiario_indirecto'];
				                $estado3=$row['estado_case'];
				                $nombre_docente_asesor_ss3=$this->GenResumenExpediente_gc_model->ObtenerNombreApellidoDocenteAsesor($id_detalle_expediente3); 

								//Acumulación de totales
						        $total_horas_servidas=$total_horas_servidas+$horas_sociales3;
						        $total_monto=$total_monto+$monto3;	
						        $total_bene_d=$total_bene_d+$beneficiario_directo3;	
						        $total_bene_i=$total_bene_i+$beneficiario_indirecto3;						        				                
				        }  	
			        }else{
				                $servicio_social3=' ';
				                $fecha_inicio3=' ';
				                $fecha_fin3=' ';
				                $horas_sociales3=' ';
				                $monto3=' ';
				                $beneficiario_directo3=' ';
				                $beneficiario_indirecto3=' ';
				                $estado3=' ';	
				                $nombre_docente_asesor_ss3=' ';	     

								//Acumulación de totales
						        $total_horas_servidas=$total_horas_servidas+0;
						        $total_monto=$total_monto+0;	
						        $total_bene_d=$total_bene_d+0;	
						        $total_bene_i=$total_bene_i+0;								        					                   	
			        }						
		$html.=	    '<tr style="height: 13px;">
					<td style="width: 30px; height: 13px; text-align: center;"><strong>3</strong></td>
					<td style="width: 185px; height: 13px; text-align: left;">'.$servicio_social3.'</td>
					<td style="width: 180px; height: 13px; text-align: left;">'.$nombre_docente_asesor_ss3.'</td>
					<td style="width: 95px; height: 13px; text-align: center;">'.$fecha_inicio3.'</td>
					<td style="width: 95px; height: 13px; text-align: center;">'.$fecha_fin3.'</td>
					<td style="width: 75px; height: 13px; text-align: center;">'.$horas_sociales3.'</td>
					<td style="width: 60px; height: 13px; text-align: center;">'.$monto3.'</td>
					<td style="width: 45px; height: 13px; text-align: center;">'.$beneficiario_directo3.'</td>
					<td style="width: 45px; height: 13px; text-align: center;">'.$beneficiario_indirecto3.'</td>
					<td style="width: 50px; height: 13px; text-align: center;">'.$estado3.'</td>
					</tr>';
					//reinicio de contador de registros segun sesion activa
					$sql = "SELECT func_reset_inc_var_session()";
					$this->db->query($sql); 
					//validar existencia de 4° registro
					$id_due=$datos['id_due'];
					$posicion=4;
					$hay_datos_resumen_4=$this->GenResumenExpediente_gc_model->ValidarDatosResumen($id_due,$posicion); 					
			        if ($hay_datos_resumen_4==1){
						//reinicio de contador de registros segun sesion activa
						$sql = "SELECT func_reset_inc_var_session()";
						$this->db->query($sql); 			        	
				        //obtener datos del resumen de servicio social del alumno para el registro 4 de la tabla
				        $id_due=$datos['id_due'];
				        $posicion=4;
						$array_datos_resumen_4=$this->GenResumenExpediente_gc_model->ObtenerDatosResumen($id_due,$posicion); 
				        foreach ($array_datos_resumen_4->result_array() as $row)
				        {
				                $id_detalle_expediente4=$row['id_detalle_expediente'];
				                $servicio_social4=$row['servicio_social'];
				                $fecha_inicio4=$row['fecha_inicio'];
				                $fecha_fin4=$row['fecha_fin'];
				                $horas_sociales4=$row['horas_prestadas'];
				                $monto4=$row['monto'];
				                $beneficiario_directo4=$row['beneficiario_directo'];
				                $beneficiario_indirecto4=$row['beneficiario_indirecto'];
				                $estado4=$row['estado_case'];
				                $nombre_docente_asesor_ss4=$this->GenResumenExpediente_gc_model->ObtenerNombreApellidoDocenteAsesor($id_detalle_expediente4); 

								//Acumulación de totales
						        $total_horas_servidas=$total_horas_servidas+$horas_sociales4;
						        $total_monto=$total_monto+$monto4;	
						        $total_bene_d=$total_bene_d+$beneficiario_directo4;	
						        $total_bene_i=$total_bene_i+$beneficiario_indirecto4;						        			                
				        }  	
			        }else{
				                $servicio_social4=' ';
				                $fecha_inicio4=' ';
				                $fecha_fin4=' ';
				                $horas_sociales4=' ';
				                $monto4=' ';
				                $beneficiario_directo4=' ';
				                $beneficiario_indirecto4=' ';
				                $estado4=' ';
				                $nombre_docente_asesor_ss4=' ';
								
								//Acumulación de totales
						        $total_horas_servidas=$total_horas_servidas+0;
						        $total_monto=$total_monto+0;
						        $total_bene_d=$total_bene_d+0;	
						        $total_bene_i=$total_bene_i+0;								        
			        }
				
		$html.=	    '<tr style="height: 13px;">
					<td style="width: 30px; height: 13px; text-align: center;"><strong>4</strong></td>
					<td style="width: 185px; height: 13px; text-align: left;">'.$servicio_social4.'</td>
					<td style="width: 180px; height: 13px; text-align: left;">'.$nombre_docente_asesor_ss4.'</td>
					<td style="width: 95px; height: 13px; text-align: center;">'.$fecha_inicio4.'</td>
					<td style="width: 95px; height: 13px; text-align: center;">'.$fecha_fin4.'</td>
					<td style="width: 75px; height: 13px; text-align: center;">'.$horas_sociales4.'</td>
					<td style="width: 60px; height: 13px; text-align: center;">'.$monto4.'</td>
					<td style="width: 45px; height: 13px; text-align: center;">'.$beneficiario_directo4.'</td>
					<td style="width: 45px; height: 13px; text-align: center;">'.$beneficiario_indirecto4.'</td>
					<td style="width: 50px; height: 13px; text-align: center;">'.$estado4.'</td>
					</tr>
					<tr style="height: 13px;">
					<td style="width: 585px; height: 13px; text-align: right;" colspan="5"><strong>TOTALES</strong></td>
					<td style="width: 75px; height: 13px; text-align: center;">'.$total_horas_servidas.'</td>
					<td style="width: 60px; height: 13px; text-align: center;">'.$total_monto.'</td>
					<td style="width: 45px; height: 13px; text-align: center;">'.$total_bene_d.'</td>
					<td style="width: 45px; height: 13px; text-align: center;">'.$total_bene_i.'</td>
					<td style="width: 50px; height: 13px; text-align: center;">&nbsp;</td>
					</tr>
					</tbody>
					</table>';
						
		$html.=	   '<table style="height: 11px;" width="364">
					<tbody>
					<tr>
					<td style="width: 354px;">&nbsp;</td>
					</tr>
					</tbody>
					</table>';	
        $html.=     '<table border="1" style="height: 102px;" width="860">
					<tbody>
					<tr style="height: 13px;">
					<td style="width: 860px; height: 13px;" colspan="3"><strong>OBSERVACIONES:</strong>'.$observaciones_exp_pss.'</td>
					</tr>';

					/*'<tr style="height: 13px;">
					<td style="width: 860px; height: 13px;" colspan="3">MSDMASMDKASMDKASMDKASMDKASMDKASD</td>
					</tr>
					<tr style="height: 13px;">
					<td style="width: 860px; height: 14px;" colspan="3">MSDKASMLKDMASLKDMASLKDMASLKDMLAKSDMLKASMDLKSA</td>
					</tr>';*/

		$html.=  	'<tr style="height: 13px;">
					<td style="width: 860px; height: 14px;" colspan="3">&nbsp;</td>
					</tr>
					<tr style="height: 13px;">
					<td style="width: 286px; height: 14px;"><strong>Vi, Bo./Nombre</strong></td>
					<td style="width: 286px; height: 14px;"><strong>Firma:</strong></td>
					<td style="width: 288px; height: 14px;"><strong>Fecha de Remisi&oacute;n:</strong> '.$fecha_remision.'</td>
					</tr>
					</tbody>
					</table>';																								
      


                   
        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
   
        
        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Resumen_Expediente_Servicio_Social.pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    