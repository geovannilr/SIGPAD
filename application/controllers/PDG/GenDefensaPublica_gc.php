<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenDefensaPublica_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PDG/GenDefensaPublica_gc_model');

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
        $pdf->SetTitle('Acta Nota Defensa Pública');
        $pdf->SetSubject('Acta Nota Defensa Pública');
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
        $pdf->SetFont('Helvetica', '',10);

        // Añadir una página
        // Este método tiene varias opciones, consulta la documentación para más información.
        $pdf->AddPage();
      
        $datos_equipo['id']=$id;
        $resultado=$this->GenDefensaPublica_gc_model->DatosEquipo($datos_equipo);      
        foreach ($resultado->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
                $anio_tg=$row['anio_tg'];
                $ciclo_tg=$row['ciclo_tg'];
                $tema=$row['tema'];
        }     
                    
        $nombreDocenteAsesor=$this->GenDefensaPublica_gc_model->DatosDocenteAsesor($id_equipo_tg,$anio_tg);
        $nombreDocenteTribunalEva1=$this->GenDefensaPublica_gc_model->DatosDocenteTribunalEva1($id_equipo_tg,$anio_tg);
        $nombreDocenteTribunalEva2=$this->GenDefensaPublica_gc_model->DatosDocenteTribunalEva2($id_equipo_tg,$anio_tg);
        // Establecemos el contenido para imprimir

        //Calculo del año actual
        $anio =date ("Y"); 
        //Calculo de la fecha actual
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        $mes=date('m');
        $dia=date('d');
       // Example of Image from data stream ('PHP rules')
        //Imagen ubicada al costado superior derecho en formato vertical
        $img = file_get_contents('minerva_el_salvador.gif');
        $pdf->Image('@' . $img,'167','25','14','19');
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

        //$html  =    '<p style="text-align: right;">Ref. EISI-002-'.$anio.'</p>';
        $html =    '<table  style="height: 56px; width: 606px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>UNIVERSIDAD DE EL SALVADOR</strong></td>
                    <td style="width: 100px; height: 39px; text-align: center;" rowspan="3"></td>
                    </tr>
                    <tr style="height: 13px; text-align: left;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>FACULTAD DE INGENIERIA Y ARQUITECTURA</strong></td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>ESCUELA DE INGENIERIA DE SISTEMAS INFORMATICOS</strong></td>
                    </tr>
                    </tbody>
                    </table>';
                                        
        $html .=    '<p>TRABAJO DE GRADUACION</p>
                    <p style="text-align: center;"><strong>ACTA DE EVALUACION DE LA ETAPA IV DEL TRABAJO DE GRADUACION</strong></p>';
        $html  .=  '<table style="height: 5px;" width="520">
                    <tbody>
                    <tr>
                    <td style="width: 510px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table style="height: 22px;" width="554">
                    <tbody>
                    <tr>
                    <td style="width: 560px; text-align: center;"><strong>DATOS GENERALES</strong></td>
                    </tr>
                    <tr>
                    <td border="1" style="width: 560px;"><strong>CARRERA: &nbsp;Ing. de Sistemas Informaticos</strong></td>
                    </tr>
                    <tr>
                    <td border="1" style="width: 560px;"><strong>CICLO DE INSCRIPCION DEL TRABAJO: Ciclo '.$ciclo_tg.'-'.$anio_tg.'</strong></td>
                    </tr>
                    </tbody>
                    </table>';
        $html  .=  '<table style="height: 5px;" width="520">
                    <tbody>
                    <tr>
                    <td style="width: 510px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';
        $html  .=  '<table border="1" style="height: 29px;" width="557">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 560px; text-align: center; height: 13px;"><strong>NOMBRE DEL TRABAJO DE GRADUACI&Oacute;N</strong></td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px; text-align: center;">'.$tema.'</td>
                    </tr>
                    </tbody>
                    </table>';
        $html  .=  '<table style="height: 5px;" width="520">
                    <tbody>
                    <tr>
                    <td style="width: 510px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';                  
        $html .=    '<table border="1" style="height: 15px;" width="581">
                    <tbody>
                    <tr>
                    <td style="width: 560px;"><strong>&nbsp;&nbsp;&nbsp;DOCENTE ASESOR:</strong> Ing. '.$nombreDocenteAsesor.'</td>
                    </tr>
                    </tbody>
                    </table>                    
                    <table border="1" style="height: 5px;" width="581">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">
                    <table style="height: 27px;" width="573">
                    <tbody>
                    <tr>
                    <td style="width: 560px;"><strong>ESTUDIANTES:</strong></td>
                    </tr>';
                   $resultadoAlumnos=$this->GenDefensaPublica_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg);      
                    foreach ($resultadoAlumnos->result_array() as $row)
                    {
                            $id_due=$row['id_due'];
                            $apellido_nombre=$row['apellido_nombre'];
                            $html .= '<tr>';
                            $html .= '<td style="width: 560px;">'.$apellido_nombre.'</td>';                          
                            $html .= '</tr>';                            

                    }
            $html .='</tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>';
            $html .='<table style="height: 89px;" width="570">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 10px; text-align: justify;">Reunido el Tribunal Calificador con fecha '.$dia.'/'.$mes.'/'.$anio.', y habiendo sido subsanadas las observaciones al documento ACUERDA otorgar a:</td>
                    </tr>';
                $resultadoAlumnos=$this->GenDefensaPublica_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg);      
                    foreach ($resultadoAlumnos->result_array() as $row)
                    {
                            $id_due=$row['id_due'];
                            $apellido_nombre=$row['apellido_nombre'];
                            $resultadoNota=$this->GenDefensaPublica_gc_model->NotaSegunCarnet($id_equipo_tg,$anio_tg,$id_due);
                            $resultadoNotaEnLetras=$this->GenDefensaPublica_gc_model->NotaSegunCarnetEnLetra($resultadoNota);
                            $html .= '<tr style="height: 13px;">';
                            $html .= '<td style="width: 560px; height: 10px; text-align: justify;"><strong>'.$apellido_nombre.'</strong> la calificaci&oacute;n de '.$resultadoNotaEnLetras.'  ('.round($resultadoNota,1).');</td>';
                            $html .= '</tr>';                            

                    }            

            $html .='<tr style="height: 13px;">
                    <td style="width: 560px; height: 3px; text-align: justify;">en la exposici&oacute;n y defensa del informe final del trabajo de graduaci&oacute;n.</td>
                    </tr>
                    </tbody>
                    </table>';
                    
            $html.='<p style="text-align: justify;">Y con el objeto que este documento sirva para continuar con la 
                                                    etapa de finalizaci&oacute;n del proceso de graduaci&oacute;n de los
                                                    bachilleres antes mencionados, los abajo firmantes extienden la presente
                                                    en la Ciudad Universitaria el d&iacute;a '.$dia.' del mes de '.$meses[date('n')-1].' de
                                                    '.$anio.'.</p>
                    <p style="text-align: justify;">&nbsp;</p>
                    <p style="text-align: justify;">&nbsp;</p>
                    <table style="height: 33px;" width="540">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 172px; height: 13px; text-align: center;">_____________________</td>
                    <td style="width: 173px; height: 13px; text-align: center;">_____________________</td>
                    <td style="width: 173px; height: 13px; text-align: center;">_____________________</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 172px; height: 13px; text-align: center;">Ing. '.$nombreDocenteAsesor.'</td>
                    <td style="width: 173px; height: 13px; text-align: center;">Ing. '.$nombreDocenteTribunalEva1.'</td>
                    <td style="width: 173px; height: 13px; text-align: center;">Ing. '.$nombreDocenteTribunalEva2.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 172px; height: 13px; text-align: center;">Docente Asesor</td>
                    <td style="width: 173px; height: 13px; text-align: center;">Jurado Evaluador</td>
                    <td style="width: 173px; height: 13px; text-align: center;">Jurado Evaluador</td>
                    </tr>
                    </tbody>
                    </table>
                    <p style="text-align: justify;"><br /><br /><br /><br /></p>';

        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Acta_de_Notas_Defensa_Publica_equipo_".$id_equipo_tg.".pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    