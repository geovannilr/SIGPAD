<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenActaEtapa1_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PDG/GenActaEtapa1_gc_model');

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
        $pdf->SetFont('Helvetica', '',10);
        
        // Añadir la primer página
        $pdf->AddPage('P', 'A4');
        
       
        // Este método tiene varias opciones, consulta la documentación para más información.
        //$pdf->AddPage();
      
        $datos_equipo['id']=$id;
        $resultado=$this->GenActaEtapa1_gc_model->DatosEquipo($datos_equipo);      
        foreach ($resultado->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
                $anio_tg=$row['anio_tg'];
                $ciclo_tg=$row['ciclo_tg'];
                $tema=$row['tema'];
        }     
        $nombreDocenteAsesor=strtoupper ($this->GenActaEtapa1_gc_model->DatosDocenteAsesor($id_equipo_tg,$anio_tg,$ciclo_tg));
 
        // Establecemos el contenido para imprimir

        //Calculo del año actual
        $anio =date ("Y"); 
        //Calculo de la fecha actual
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        $mes=date('m');
        $dia=date('d');
        //$html  =    '<p style="text-align: right;">Ref. EISI-002-'.$anio.'</p>';
        $html =    '<table  style="height: 56px; width: 606px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>UNIVERSIDAD DE EL SALVADOR</strong></td>
                    <td style="width: 100px; height: 39px; text-align: center;" rowspan="3">';

                    // Example of Image from data stream ('PHP rules')
                    //Imagen ubicada al costado superior derecho en formato vertical
                    $img = file_get_contents('minerva_el_salvador.gif');
                    $pdf->Image('@' . $img,'167','27','14','19');
                    // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

                    

        $html .=    '</td>
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
                    <p style="text-align: center;"><em><strong>REGISTRO DE NOTAS POR EVALUACION</strong></em></p>
                    <p style="text-align: center;">EVALUACIÓN N° 2 (35%)</p>
                    <p>FECHA EVALUACIÓN: 12/12/2016</p>
                    <p>GRUPO N&deg;:'.$id_equipo_tg.'</p>
                    <p>'.$tema.'</p>';

        $html .=    '<table border="1" style="height: 13px;" width="587">
                    <tbody>
                    <tr>
                    <td style="width: 350px; text-align: center;"><strong>NOMBRE</strong></td>
                    <td style="width: 100px; text-align: center;"><strong>NOTA</strong></td>
                    <td style="width: 100px; text-align: center;"><strong>A/R</strong></td>
                    </tr>';

                   $resultadoAlumnos=$this->GenActaEtapa1_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$ciclo_tg);      
                    foreach ($resultadoAlumnos->result_array() as $row)
                    {
                            $id_due=$row['id_due'];
                            $apellido_nombre=$row['apellido_nombre'];
                            $html .= '<tr>';
                            $html .= '<td style="width: 350px;">'.$apellido_nombre.'</td>';                           
                            $resultadoNota=$this->GenActaEtapa1_gc_model->NotaSegunCarnet($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);
                            if ($resultadoNota<6){
                                $a_r='R';
                            }
                            else{
                                $a_r='A';   
                            }
                            $html .= '<td style="width: 100px; text-align: center;">'.round($resultadoNota,1).'</td>'; 
                            $html .= '<td style="width: 100px; text-align: center;">'.$a_r.'</td>'; 
                            $html .= '</tr>';                            

                    }   


        $html .=    '</tbody>
                    </table>';                                        
        
        $html .=    '<p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;"><span style="text-decoration: underline;"><strong>EVALUADOR</strong></span></p>
                    <p>&nbsp;</p>
                    <table style="height: 32px;" width="521">
                    <tbody>
                    <tr>
                    <td style="width: 275px; text-align: center;">NOMBRE &nbsp;</td>
                    <td style="width: 275px; text-align: center;">FIRMA</td>
                    </tr>
                    <tr>
                    <td style="width: 275px; text-align: center;">&nbsp;</td>
                    <td style="width: 275px; text-align: center;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="width: 275px; text-align: center;">&nbsp;</td>
                    <td style="width: 275px; text-align: center;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="width: 275px; text-align: center;">&nbsp;</td>
                    <td style="width: 275px; text-align: center;">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="width: 275px; text-align: center;">Ing. '.$nombreDocenteAsesor.'</td>
                    <td style="width: 275px; text-align: center;">_______________________________</td>
                    </tr>
                    </tbody>
                    </table>';



                   
        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        //Definiendo la segunda pagina
        $pdf->AddPage('L', 'A4');
        $pdf->SetLeftMargin(5);
        $pdf->SetRightMargin(5);        
        $pdf->SetFont('Helvetica', '',9);
        $html2 =    '<table  style="height: 56px; width: 606px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>UNIVERSIDAD DE EL SALVADOR</strong></td>
                    <td style="width: 100px; height: 39px; text-align: center;" rowspan="3">LOGO;</td>
                    </tr>
                    <tr style="height: 13px; text-align: left;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>FACULTAD DE INGENIERIA Y ARQUITECTURA</strong></td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>ESCUELA DE INGENIERIA DE SISTEMAS INFORMATICOS</strong></td>
                    </tr>
                    </tbody>
                    </table>';
        $html2 .=   '<p>TRABAJO DE GRADUACION</p>
                    <p style="text-align: center;"><strong>FORMATO DE EVALUACIÓN N° 2</strong></p>
                    <p>GRUPO N&deg;:'.$id_equipo_tg.'</p>
                   ';

        $html2 .=   '<table style="height: 39px; width: 873px;">
                    <tbody>
                    <tr>
                    <td style="width: 293px;">EVALUADOR</td>
                    <td style="width: 218px;">Firma:_________________________</td>
                    <td style="width: 143px;">FECHA</td>
                    <td style="width: 218px;">LUGAR</td>
                    </tr>
                    <tr>
                    <td style="width: 293px;">Ing. '.$nombreDocenteAsesor.'</td>
                    <td style="width: 218px;">&nbsp;</td>
                    <td style="width: 143px;">'.$dia.'/'.$mes.'/'.$anio.'</td>
                    <td style="width: 218px;">FIA-EISI</td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>';
        $html2 .=  '<table  border="1" style="height: 138px;" width="949">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 873px; height: 13px;" colspan="9">TEMA: '.$tema.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 23px; height: 13px; text-align: center;" rowspan="3">N&deg;</td>
                    <td style="width: 215px; height: 13px; text-align: center;">CRITERIOS DE EVALUACION</td>
                    <td style="width: 100px; height: 13px; text-align: center;">PRESENTACION GENERAL</td>
                    <td style="width: 95px; height: 13px; text-align: center;">EXPOSICION INDIVIDUAL</td>
                    <td style="width: 100px; height: 13px; text-align: center;">CONOCIMIENTO DLE TEMA</td>
                    <td style="width: 95px; height: 13px; text-align: center;">CAPACIDAD DE ANALISIS</td>
                    <td style="width: 100px; height: 13px; text-align: center;">CONOCIMIENTO DE INGENIERIA</td>
                    <td style="width: 95px; height: 13px; text-align: center;">CRITERIO PROFESIONAL</td>
                    <td style="width: 50px; height: 13px; text-align: center;" rowspan="3">NOTA</td>';
                    
        $html2  .= '</tr>
                    <tr style="height: 13px;">
                    <td style="width: 215px; height: 13px; text-align: center;" rowspan="2">ALUMNO</td>
                    <td style="width: 100px; height: 13px; text-align: center;">1</td>
                    <td style="width: 95px; height: 13px; text-align: center;">2</td>
                    <td style="width: 100px; height: 13px; text-align: center;">3</td>
                    <td style="width: 95px; height: 13px; text-align: center;">4</td>
                    <td style="width: 100px; height: 13px; text-align: center;">5</td>
                    <td style="width: 95px; height: 13px; text-align: center;">6</td>
                    </tr>';
        $html2  .=  '<tr style="height: 13px;">
                    <td style="width: 100px; height: 13px; text-align: center;">10%</td>
                    <td style="width: 95px; height: 13px; text-align: center;">10%</td>
                    <td style="width: 100px; height: 13px; text-align: center;">20%</td>
                    <td style="width: 95px; height: 13px; text-align: center;">20%</td>
                    <td style="width: 100px; height: 13px; text-align: center;">20%</td>
                    <td style="width: 95px; height: 13px; text-align: center;">20%</td>
                    </tr>';
                   $resultadoAlumnos=$this->GenActaEtapa1_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$ciclo_tg);      
                    foreach ($resultadoAlumnos->result_array() as $row)
                    {
                            $id_due=$row['id_due'];
                            $apellido_nombre=$row['apellido_nombre'];
                            $html2 .= '<tr style="height: 13px;">';
                            $html2 .= '<td style="width: 23px; height: 13px; text-align: center;">1</td>';
                            $html2 .= '<td style="width: 215px; height: 13px;">'.$apellido_nombre.'</td>';                                                 
                            $resultadoNotaCriterio1=$this->GenActaEtapa1_gc_model->NotaCriterio1($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);
                            $html2 .='<td style="width: 100px; height: 13px; text-align: center;">'.$resultadoNotaCriterio1.'</td>';
                            $resultadoNotaCriterio2=$this->GenActaEtapa1_gc_model->NotaCriterio2($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);
                            $html2 .='<td style="width: 95px; height: 13px; text-align: center;">'.$resultadoNotaCriterio2.'</td>';
                            $resultadoNotaCriterio3=$this->GenActaEtapa1_gc_model->NotaCriterio3($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);
                            $html2 .='<td style="width: 100px; height: 13px; text-align: center;">'.$resultadoNotaCriterio3.'</td>';
                            $resultadoNotaCriterio4=$this->GenActaEtapa1_gc_model->NotaCriterio4($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);
                            $html2 .='<td style="width: 95px; height: 13px; text-align: center;">'.$resultadoNotaCriterio4.'</td>';
                            $resultadoNotaCriterio5=$this->GenActaEtapa1_gc_model->NotaCriterio5($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);
                            $html2 .='<td style="width: 100px; height: 13px; text-align: center;">'.$resultadoNotaCriterio5.'</td>';
                            $resultadoNotaCriterio6=$this->GenActaEtapa1_gc_model->NotaCriterio6($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);
                            $html2 .='<td style="width: 95px; height: 13px; text-align: center;">'.$resultadoNotaCriterio6.'</td>';
                            $resultadoNotaExposicion=$this->GenActaEtapa1_gc_model->NotaExposicion($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);
                            $html2 .='<td style="width: 50px; height: 13px; text-align: center;">'.round($resultadoNotaExposicion,1).'</td>';
                            $html2 .= '</tr>';                            
                    }                       
        $html2 .=   '</tbody>
                    </table>
                    <table border="1" style="height: 112px; width: 823px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 338px; height: 13px;">
                        N&deg; 1 Creatividad del grupo, detalles, nitidez,Ayudas audiovisuales, etc.
                    </td>
                    <td style="width: 95px; height: 13px;">&nbsp;</td>
                    <td style="width: 100px; height: 13px;">&nbsp;</td>
                    <td style="width: 95px; height: 13px;">&nbsp;</td>
                    <td style="width: 100px; height: 13px;">&nbsp;</td>
                    <td style="width: 95px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 433px; height: 13px;" colspan="2">
                        N&deg; 2 Soltura, desenvolvimiento, ilaci&oacute;n,Coordinaci&oacute;n, Dominio, iniciativa individual,Innovaci&oacute;n, exactitud.
                    </td>
                    <td style="width: 100px; height: 13px;">&nbsp;</td>
                    <td style="width: 95px; height: 13px;">&nbsp;</td>
                    <td style="width: 100px; height: 13px;">&nbsp;</td>
                    <td style="width: 95px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 533px; height: 13px;" colspan="3">
                        N&deg; 3 Q&uacute;e tanto conose sobre su trabajo realizado? &nbsp;
                    </td>
                    <td style="width: 95px; height: 13px;">&nbsp;</td>
                    <td style="width: 100px; height: 13px;">&nbsp;</td>
                    <td style="width: 95px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 628px; height: 13px;" colspan="4">
                        N&deg;4 Habilidad para combinar elementos valiosos, hilvanar ideas, capacidad de &nbsp;Raciocinio, gr&aacute;ficas, modelos y capacidad de S&iacute;ntesis.
                    </td>
                    <td style="width: 100px; height: 13px;">&nbsp;</td>
                    <td style="width: 95px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 728px; height: 13px;" colspan="5">
                        N&deg; 5 Uso y aplicaci&oacute;n de T&eacute;cnicas de Ingenier&iacute;a de Sistemas Inform&aacute;ticos.
                    </td>
                    <td style="width: 95px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 823px; height: 13px;" colspan="6">
                        N&deg; 6 Manera como enfrenta la situaciones imprevistas, madurez profesional,presentaci&oacute;n de soluciones y respuestas de acuerdo a problematica a resolver.</td>
                    </tr>
                    </tbody>
                    </table>';
        $html2  .= '<p>&nbsp;</p>
                    <table style="height: 15px; width: 495px;">
                    <tbody>
                    <tr>
                    <td style="width: 93px;">&nbsp;</td>
                    <td style="width: 146px;">NOTA DE DOCUMENTO:</td>';
                    $resultadoNotaDocumento=$this->GenActaEtapa1_gc_model->NotaDocumento($id_equipo_tg,$anio_tg,$ciclo_tg);
        $html2  .=  '<td border="1" style="width: 91px;">'.$resultadoNotaDocumento.'</td>
                    <td style="width: 42px;">&nbsp;</td>
                    <td style="width: 93px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';            

        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html2, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        
        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Acta_de_Notas_Etapa1_equipo_".$id_equipo_tg.".pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    