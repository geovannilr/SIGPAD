<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenRecolectNotas_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PDG/GenRecolectNotas_gc_model');

    }
    
    public function index()
    {
        //cargamos la vista y pasamos el array $data['provincias'] para su uso
        $this->load->view('pdfs_view', $data);
    }

    public function generar($id) {
        $this->load->library('Pdf');
        $pdf = new Pdf(PDF_PAGE_ORIENTATION,'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPageOrientation('l'); // PDF_PAGE_ORIENTATION---> 'l' or 'p'
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Ruben Moran');
        $pdf->SetTitle('Recolector de Notas');
        $pdf->SetSubject('Recolecto de Notas');
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
        $resultado=$this->GenRecolectNotas_gc_model->DatosEquipo($datos_equipo);      
        foreach ($resultado->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
                $anio_tg=$row['anio_tg'];
                $ciclo_tg=$row['ciclo_tg'];
                $tema=$row['tema'];
                $sigla=$row['sigla'];
        }     
                   
        $nombreDirGenProcesosGraduacion=$this->GenRecolectNotas_gc_model->DatosDirGenProcesosGraduacion();
        $nombreDirectorEscuela=$this->GenRecolectNotas_gc_model->DatosDirEscuela();
 
        // Establecemos el contenido para imprimir

        //Calculo del año actual
        $anio =date ("Y"); 
        //Calculo de la fecha actual
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
         
        $fecha=date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        //$html  =    '<p style="text-align: right;">Ref. EISI-002-'.$anio.'</p>';
        $html =    '<table style="height: 56px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 860px; text-align: center; height: 13px;"><strong>UNIVERSIDAD DE EL SALVADOR</strong></td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 860px; text-align: center; height: 13px;"><strong>FACULTAD DE INGENIERIA Y ARQUITECTURA</strong></td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 860px; text-align: center; height: 13px;"><strong>RECOLECTOR OFICIAL CICLO I 2015</strong></td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<p>&nbsp;</p>
                    <table border="1" style="height: 62px; width: 627px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 88px; height: 13px;">Escuela</td>
                    <td style="width: 450px; height: 13px;">INGENIERIA DE SISTEMAS INFORMATICOS</td>
                    <td style="width: 200px; height: 13px;">FIRMA RESPONSABLE</td>
                    <td style="width: 122px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 88px; height: 13px;">Asignatura</td>
                    <td style="width: 450px; height: 13px;">TRABAJO DE GRADUACI&Oacute;N</td>
                    <td style="width: 200px; height: 13px;">TOTAL DE ALUMNOS</td>
                    <td style="width: 122px; height: 13px;">4</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 88px; height: 13px;">Catedr&aacute;tico</td>
                    <td style="height: 13px;" colspan="3">ING. JULIO ARMANDO &nbsp;REYES</td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<p>&nbsp;</p>';
        $html .=    '<table border="1" style="height: 50px; width: 723px;">
                    <tbody>
                    <tr>
                    <td style="width: 54px; text-align: center;"><strong>Corr.</strong></td>
                    <td style="width: 65px; text-align: center;"><strong>Carrera</strong></td>
                    <td style="width: 65px; text-align: center;"><strong>Carnet</strong></td>
                    <td style="width: 250px; text-align: center;"><strong>Apellidos y Nombres</strong></td>
                    <td style="width: 35px; text-align: center;"><strong>1</strong></td>
                    <td style="width: 35px; text-align: center;"><strong>2</strong></td>
                    <td style="width: 35px; text-align: center;"><strong>3</strong></td>
                    <td style="width: 35px; text-align: center;"><strong>4</strong></td>
                    <td style="width: 35px; text-align: center;"><strong>5</strong></td>
                    <td style="width: 35px; text-align: center;"><strong>6</strong></td>
                    <td style="width: 35px; text-align: center;"><strong>7</strong></td>
                    <td style="width: 35px; text-align: center;"><strong>8</strong></td>
                    <td style="width: 35px; text-align: center;"><strong>9</strong></td>
                    <td style="width: 35px; text-align: center;"><strong>10</strong></td>
                    <td style="width: 75px; text-align: center;"><strong>Nota Final de Ciclo</strong></td>
                    </tr>
                    <tr>
                    <td style="text-align: right;" colspan="4">%</td>
                    <td style="width: 35px;">20</td>
                    <td style="width: 35px;">35</td>
                    <td style="width: 35px;">25</td>
                    <td style="width: 35px;">20</td>
                    <td style="width: 35px;">...</td>
                    <td style="width: 35px;">...</td>
                    <td style="width: 35px;">...</td>
                    <td style="width: 35px;">...</td>
                    <td style="width: 35px;">...</td>
                    <td style="width: 35px;">...</td>
                    <td style="width: 75px;">&nbsp;</td>
                    </tr>';
                  $resultadoAlumnos=$this->GenRecolectNotas_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$ciclo_tg);      
                    foreach ($resultadoAlumnos->result_array() as $row)
                    {
                            $id_due=$row['id_due'];
                            $apellido_nombre=$row['apellido_nombre'];
                            $html .= '<tr>';
                            $html .= '<td style="width: 54px;">1</td>';
                            $html .= '<td style="width: 65px;">I10515</td>';
                            $html .= '<td style="width: 65px;">'.$id_due.'</td>';
                            $html .= '<td style="width: 250px;">'.$apellido_nombre.'</td>';                           
                            $resultadoNotaAnteproy=$this->GenRecolectNotas_gc_model->NotaAnteproy($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);
                            $html .= '<td style="width: 35px;">'.round($resultadoNotaAnteproy,1).'</td>';
                            $resultadoNotaEtapa1=$this->GenRecolectNotas_gc_model->NotaEtapa1($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);                             
                            $html .= '<td style="width: 35px;">'.round($resultadoNotaEtapa1,1).'</td>';
                            $resultadoNotaEtapa2=$this->GenRecolectNotas_gc_model->NotaEtapa2($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);                             
                            $html .= '<td style="width: 35px;">'.round($resultadoNotaEtapa2,1).'</td>';
                            $resultadoNotaDefensaPublica=$this->GenRecolectNotas_gc_model->NotaDefensaPublica($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);                             
                            $html .= '<td style="width: 35px;">'.round($resultadoNotaDefensaPublica,1).'</td>';                                                                                  
        $html   .=          '<td style="width: 35px;">...</td>
                            <td style="width: 35px;">...</td>
                            <td style="width: 35px;">...</td>
                            <td style="width: 35px;">...</td>
                            <td style="width: 35px;">...</td>
                            <td style="width: 35px;">...</td>';
                            $resultadoNotaFinal=$this->GenRecolectNotas_gc_model->NotaFinal($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);                             
                            $html .= '<td style="width: 75px;">'.round($resultadoNotaFinal,1).'</td>';                                                                                                                                        
        $html .=           '</tr>'; 


                    }                       
        $html   .=  '</tbody>
                    </table>';
                        
       
        $html .=    '<p>&nbsp;</p>';          
        $html .=    '<table style= width="860px">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 860px; text-align: center; height: 13px;"><span style="text-decoration: underline;">TEMA: '.$tema.'</span></td>
                    </tr>
                    </tbody>
                    </table>';


        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Recolector_de_Notas_equipo_".$id_equipo_tg.".pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    