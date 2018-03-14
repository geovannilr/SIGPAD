<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenSoliProrro_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PDG/GenSoliProrro_gc_model');

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
        $pdf->SetTitle('Solicitud de Prorroga de TG');
        $pdf->SetSubject('Solicitud de Prorroga de TG');
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

        // Añadir una página
        // Este método tiene varias opciones, consulta la documentación para más información.
        $pdf->AddPage();
      
        $datos['id_solicitud_academica']=$id;
        $resultado=$this->GenSoliProrro_gc_model->DatosSolicitud($datos);      
        foreach ($resultado->result_array() as $row)
        {
                $anio_tg=$row['anio_tg'];
                $ciclo_tg=$row['ciclo_tg'];
                $tema=$row['tema'];
                $id_equipo_tg=$row['id_equipo_tg'];
                $fecha_solicitud=$row['fecha_solicitud'];
                $caso_especial=$row['caso_especial'];
                $fecha_ini_prorroga=$row['fecha_ini_prorroga'];
                $fecha_fin_prorroga=$row['fecha_fin_prorroga'];
                $duracion=$row['duracion'];
                $cantidad_evaluacion_actual=$row['cantidad_evaluacion_actual'];
                $eva_actual=$row['eva_actual'];
                $eva_antes_prorroga=$row['eva_antes_prorroga'];
                $justificacion=$row['justificacion'];                                
        }   
        $nombreDocenteAsesor=$this->GenSoliProrro_gc_model->DatosDocenteAsesor($id_equipo_tg,$anio_tg,$ciclo_tg);           
        $nombreDirGenProcesosGraduacion=$this->GenSoliProrro_gc_model->DatosDirGenProcesosGraduacion();
        $nombreCoordinadorProcesosGraduacion=$this->GenSoliProrro_gc_model->DatosCoordinadorProcesosGraduacion();
        /*Conversion de numeros a letras en el campo Caso Especial*/
        if ($caso_especial==0){
            $caso_especial='NO';
        }
        if ($caso_especial==1){
            $caso_especial='SI';
        }        
        // Establecemos el contenido para imprimir

        //Calculo del año actual
        $anio =date ("Y"); 
        //Calculo de la fecha actual
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes=date('m');
        $dia=date('d');         
        $fecha=date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        //   
        // Example of Image from data stream ('PHP rules')
        //Imagen ubicada al costado superior derecho en formato vertical
        $img = file_get_contents('minerva_el_salvador.gif');
        $pdf->Image('@' . $img,'167','22','14','19');
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)


        $html =    '<table  style="height: 56px; width: 606px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>UNIVERSIDAD DE EL SALVADOR</strong></td>
                    </tr>
                    <tr style="height: 13px; text-align: left;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>FACULTAD DE INGENIERIA Y ARQUITECTURA</strong></td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>ESCUELA DE INGENIERIA DE SISTEMAS INFORMATICOS</strong></td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table style="width: 319px;">
                    <tbody>
                    <tr>
                    <td style="width: 321px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';                    
        $html .=    '<table style="width: 560px;">
                    <tbody>
                    <tr>
                    <td style="width: 560px; text-align: center;"><strong>FORMULARIO DE EXTENSION DE PRORROGA DE TRABAJOS DE GRADUACION</strong></td>
                    </tr>
                    </tbody>
                    </table>';  
        $html .=    '<table style="width: 319px;">
                    <tbody>
                    <tr>
                    <td style="width: 321px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';                                      
        $html .=    '<table border="1" style="height: 23px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">
                    <table style="height: 37px;" width="574">
                    <tbody>
                    <tr>
                    <td style="width: 560px;" colspan="2">NOMBRE DEL TRABAJO DE GRADUACION: '.$tema.'</td>
                    </tr>
                    <tr>
                    <td style="width: 560px;" colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="width: 280px;">INSCRITO: CICLO: '.$ciclo_tg.'</td>
                    <td style="width: 280px;">A&Ntilde;O: '.$anio_tg.'</td>
                    </tr>
                    </tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table style="width: 319px;">
                    <tbody>
                    <tr>
                    <td style="width: 321px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table border="1" style="height: 7px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">
                    <table style="height: 72px; width: 560px;">
                    <tbody>
                    <tr>
                    <td style="width: 374px;" colspan="2">FECHA DE SOLICITUD: <strong>'.strftime("%d/%m/%Y", strtotime($fecha_solicitud)).'</strong></td>
                    <td style="width: 186px;">CASO ESPECIAL: <strong>'.$caso_especial.'</strong></td>
                    </tr>
                    <tr>
                    <td style="width: 560px;" colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="width: 560px;" colspan="3">PRORROGA DE SOLICITUD:</td>
                    </tr>
                    <tr>
                    <td style="width: 186px;">DESDE: <strong>'.strftime("%d/%m/%Y", strtotime($fecha_ini_prorroga)).'</strong></td>
                    <td style="width: 186px;">HASTA: <strong>'.strftime("%d/%m/%Y", strtotime($fecha_fin_prorroga)).'</strong></td>
                    <td style="width: 186px;">DURACION: <strong>'.round($duracion,0).'</strong></td>
                    </tr>
                    </tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table style="width: 319px;">
                    <tbody>
                    <tr>
                    <td style="width: 321px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table border="1" style="height: 16px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">
                    <table style="height: 130px;" width="556">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="width: 560px;">Evaluaciones realizadas a la fecha: '.strftime("%d/%m/%Y", strtotime($cantidad_evaluacion_actual)).'</td>
                    </tr>
                    <tr>
                    <td style="width: 560px;">'.$eva_actual.'</td>
                    </tr>
                    <tr>
                    <td style="width: 560px;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="width: 560px;">Evaluaciones finalizadas al '.strftime("%d/%m/%Y", strtotime($fecha_ini_prorroga)).':</td>
                    </tr>
                    <tr>
                    <td style="width: 560px;">'.$eva_antes_prorroga.'</td>
                    </tr>
                    <tr>
                    <td style="width: 560px;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="width: 560px;">JUSTIFICACION:</td>
                    </tr>
                    <tr>
                    <td style="width: 560px;">'.$justificacion.'</td>
                    </tr>
                    </tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table style="width: 319px;">
                    <tbody>
                    <tr>
                    <td style="width: 321px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    ' <table border="1" style="height: 7px;" width="568">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">
                    <table style="height: 162px; width: 560px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px;" colspan="2">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px;" colspan="2">NOMBRES Y FIRMAS:</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px;" colspan="2">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px;" colspan="2">Docente Director: Ing. '.$nombreDocenteAsesor.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px;" colspan="2">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px;" colspan="2">Grupo No: '.$id_equipo_tg.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px;" colspan="2">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px;" colspan="2">Estudiantes:</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px;" colspan="2">&nbsp;</td>
                    </tr>';
                   $resultadoAlumnos=$this->GenSoliProrro_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$ciclo_tg);      
                    foreach ($resultadoAlumnos->result_array() as $row)
                    {
                            $id_due=$row['id_due'];
                            $apellido_nombre=$row['apellido_nombre'];
                            $html .= '<tr>';
                            $html .= '<td style="width: 560px; height: 13px;" colspan="2">'.$apellido_nombre.'</td>';                          
                            $html .= '</tr>';                            
                            $html .= '<tr>';
                            $html .= '<td style="width: 560px; height: 13px;" colspan="2"></td>';                          
                            $html .= '</tr>';
                    }                    
        $html.=     '<tr style="height: 13px;">
                    <td style="width: 560px; height: 13px;" colspan="2">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; text-align: center; height: 13px;">____________________________</td>
                    <td style="width: 280px; text-align: center; height: 13px;">____________________________</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; text-align: center; height: 13px;">Ing. '.$nombreCoordinadorProcesosGraduacion.'</td>
                    <td style="width: 280px; text-align: center; height: 13px;">Ing. '.$nombreDirGenProcesosGraduacion.'</td>
                    </tr>
                    <tr style="height: 26px;">
                    <td style="width: 266px; text-align: center; height: 26px;">Coordinador Procesos de Graduaci&oacute;n EISI</td>
                    <td style="width: 294px; text-align: center; height: 26px;">Coordinadora General de Trabajos de Graduaci&oacute;n FIA</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px;" colspan="2">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table style="width: 319px;">
                    <tbody>
                    <tr>
                    <td style="width: 321px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';                                                                           



        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Solicitud_Prorroga_TG.pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    