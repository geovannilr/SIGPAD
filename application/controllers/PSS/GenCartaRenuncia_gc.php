<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenCartaRenuncia_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PSS/GenCartaRenuncia_gc_model');

    }
    
    public function index()
    {
        //cargamos la vista y pasamos el array $data['provincias'] para su uso
        $this->load->view('pdfs_view', $data);
    }

    public function generar($id) {
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Ruben Moran');
        $pdf->SetTitle('Carta Oficialización');
        $pdf->SetSubject('Carta Oficialización');
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
        $pdf->SetFont('Helvetica', '',12);

        // Añadir una página
        // Este método tiene varias opciones, consulta la documentación para más información.
        $pdf->AddPage();
      
        //////////////////////////////$datos_equipo['id']=$id;
        /*$resultado=$this->GenRemiEjemplares_gc_model->DatosEquipo($datos_equipo);      
        foreach ($resultado->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
                $anio_tg=$row['anio_tg'];
                $ciclo_tg=$row['ciclo_tg'];
                $tema=$row['tema'];
                $sigla=$row['sigla'];
        }     
                   
        $nombreAdminAcademico=$this->GenRemiEjemplares_gc_model->DatosAdminAcademica();
        $nombreSecreEscuela=$this->GenRemiEjemplares_gc_model->DatosSecreEscuela();*/
 
        // Establecemos el contenido para imprimir

        //Calculo del año actual
        /*$anio =date ("Y"); 
        //Calculo de la fecha actual
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
         
        $fecha=date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;*/
        //   
		$datos['id_detalle_expediente']=$id;
        //obtener el encabezado del reporte    
		$encabezado=$this->GenCartaRenuncia_gc_model->ObtenerEncabezado($datos);         
        //obtener el motivos de renuncia    
		$motivos_renuncia=$this->GenCartaRenuncia_gc_model->ObtenerMotivosRenuncia($datos);  
		///obtener fecha de renuncia de la fecha actual desglosada
		$array_fecha_desglosada=$this->GenCartaRenuncia_gc_model->ObtenerFechaDesglosada($datos); 
        foreach ($array_fecha_desglosada->result_array() as $row)
        {
                $dia=$row['dia'];
                $mes=$row['mes_case'];
                $anio=$row['anio'];
        }  
        //obtencion de nombre de servicio social
        $nombre_servicio_social=$this->GenCartaRenuncia_gc_model->ObtenerNombreServicioSocial($datos); 
		//Obtener el nombre_apellido de la Coordinadora  de la subunida de  proyeecion social
 		$nombre_apellido_coor_sub_uni_pss=$this->GenCartaRenuncia_gc_model->ObtenerNombreApellidoCoorSubUniPss($datos);         
		$html =	'<table  style="height: 19px;" width="560">
					<tbody>
					<tr>
					<td style="width: 560px; text-align: right;">San Salvador, '.$dia.' de '.$mes.' de '.$anio.'</td>
					</tr>
					</tbody>
					</table>';

        $html .=    '<table style="height: 13px;" width="560">
					<tbody>
					'.$encabezado.'
					</tbody>
					</table>';

		$html .=	'<table  style="height: 20px;" width="560">
					<tbody>
					<tr>
					<td style="width: 560px; text-align: justify;">&nbsp;</td>
					</tr>
					</tbody>
					</table>';							
		$html .=    '<table  style="height: 180px;" width="560">
					<tbody>
					<tr>
					<td style="width: 560px; text-align: justify;">Reciba un atento saludo dese&aacute;ndole &eacute;xito en todas sus actividades tanto a nivel laboral como personal.</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">&nbsp;</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">El motivo de la presente es para expresarle mi dificultad de continuar ofreciendo mi Servicio Social, que consiste en '.$nombre_servicio_social.', el cual estoy llevando a cabo como estudiante de la carrera de Ing. de Sistemas Inform&aacute;ticos de la Universidad de El Salvador.</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">&nbsp;</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">La raz&oacute;n de mi retiro voluntario es:</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">&nbsp;</td>
					</tr>
					</tbody>
					</table>';

        $html .=    '<table style="height: 13px;" width="560">
					<tbody>
					'.$motivos_renuncia.'
					</tbody>
					</table>';

		$html .=   '<table style="height: 180px;" width="580">
					<tbody>
					<tr>
					<td style="width: 560px; text-align: justify;">&nbsp;</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">Es por tal motivo que de la manera m&aacute;s cordial solicito su permiso para renunciar a dicho proyecto,manifest&aacute;ndole adem&aacute;s mi gratitud por la atenci&oacute;n y ayuda brindada hasta el momento para el desarrollo del mismo.</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">&nbsp;</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">No omito manisfestarle que estoy conciente que dada la presente situaci&oacute;n no me ser&aacute; asignada NINGUNA hora social por el trabajo que hasta el momento he desarrollado.</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">&nbsp;</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">Sin m&aacute;s que agregar por el momento me despido, no sin antes ponerme a su disposici&oacute;n para futuros proyectos o actividades dentro de su Unidad.</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">&nbsp;</td>
					</tr>
					<tr>
					<td style="width: 560px; text-align: justify;">Atentamente,</td>
					</tr>
					</tbody>
					</table>
					<p>&nbsp;</p>
					<p>&nbsp;</p>';
					
		$html .=    '<table style="height: 77px;" width="560">
					<tbody>
					<tr style="height: 13px;">
					<td style="width: 280px; height: 13px;  text-align: center;">_______________________________</td>
					<td style="width: 280px; height: 13px;  text-align: center;">_______________________________</td>
					</tr>
					<tr style="height: 13px;">
					<td style="width: 280px; height: 13px;  text-align: center;">
					<p>c.c Licda. '.$nombre_apellido_coor_sub_uni_pss.'</p>
					</td>
					<td style="width: 280px; height: 13px;  text-align: center;">
					<p>Nombre,Fima y DUI del estudiante</p>
					</td>
					</tr>
					<tr style="height: 13px;">
					<td style="width: 280px; height: 13px;  text-align: center;">				
					<p>Coordinadora Sub-Unidad de Proyecci&oacute;n Social</p>
					<p>Escuela de Ingenier&iacute;a de Sistemas Inform&aacute;ticos</p></td>
					<td style="width: 280px; height: 13px;  text-align: center;">
					<p>Estudiante de &nbsp;Ingenier&iacute;a de Sistemas Inform&aacute;ticos</p>
					</td>
					</tr>
					</tbody>
					</table>';
	

																		

        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Carta Oficialización.pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    