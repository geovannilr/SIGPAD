<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenActaAnteproy_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PDG/GenActaAnteproy_gc_model');

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
        $pdf->SetTitle('Acta Nota Anteproyecto');
        $pdf->SetSubject('Acta Nota Anteproyecto');
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
        $resultado=$this->GenActaAnteproy_gc_model->DatosEquipo($datos_equipo);      
        foreach ($resultado->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
                $anio_tg=$row['anio_tg'];
                $ciclo_tg=$row['ciclo_tg'];
                $tema=$row['tema'];
        }     
        $resultado=$this->GenActaAnteproy_gc_model->NotaAnteproy($id_equipo_tg,$anio_tg,$ciclo_tg);      
        foreach ($resultado->result_array() as $row)
        {
                $nota_criterio1=$row['nota_criterio1'];
                $nota_criterio2=$row['nota_criterio2'];
                $nota_criterio3=$row['nota_criterio3'];
                $nota_criterio4=$row['nota_criterio4'];
                $nota_criterio5=$row['nota_criterio5'];
                $nota_criterio6=$row['nota_criterio6'];
                $nota_criterio7=$row['nota_criterio7'];
                $nota_criterio8=$row['nota_criterio8'];
                $nota_criterio9=$row['nota_criterio9'];
                $nota_criterio10=$row['nota_criterio10'];
                $nota_criterio11=$row['nota_criterio11'];
                $nota_criterio12=$row['nota_criterio12'];
                $nota_documento=$row['nota_documento'];
                $nota_exposicion=$row['nota_exposicion'];
                $nota_anteproyecto=$row['nota_anteproyecto'];
        }                      
        $nombreDocenteAsesor=strtoupper ($this->GenActaAnteproy_gc_model->DatosDocenteAsesor($id_equipo_tg,$anio_tg,$ciclo_tg));
 
        // Establecemos el contenido para imprimir

        //Calculo del año actual
        $anio =date ("Y"); 
        //Calculo de la fecha actual
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        //   
        // Example of Image from data stream ('PHP rules')
        //Imagen ubicada al costado superior derecho en formato vertical
        $img = file_get_contents('minerva_el_salvador.gif');
        $pdf->Image('@' . $img,'167','22','14','19');
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
                    <p style="text-align: center;"><span style="text-decoration: underline;"><strong>EVALUACION DEL ANTEPROYECTO</strong></span></p>
                    <p>TEMA: '.$tema.'</p>
                    <p>GRUPO N&deg;:'.$id_equipo_tg.'</p>
                    <table style="height: 18px; width: 576px;">
                    <tbody>
                    <tr>
                    <td style="width: 301px;">PORCENTAJE ASIGNADO A LA ETAPA: 20%</td>
                    <td style="width: 263px;">LUGAR DE REFERENCIA: &nbsp;EISI</td>
                    </tr>
                    <tr>
                    <td style="width: 301px;">FECHA: '.$fecha.'</td>
                    <td style="width: 263px;">HORA: 15:53FALTA ALMANECARLO</td>
                    </tr>
                    </tbody>
                    </table>
                    <p>DOCUMENTO (60%)</p>
                    <table style="height: 178px; width: 573px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 360px; height: 13px; text-align: center;">CRITERIOS DE EVALUACION</td>
                    <td border="1"  style="width: 203px; height: 13px; text-align: center;">NOTA (0-10)</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 360px; height: 13px;">1. Presentaci&oacute;n del documento</td>
                    <td border="1" style="width: 203px; text-align: center; height: 13px;">'.$nota_criterio1.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 360px; height: 13px;">2. Formulaci&oacute;n del problema</td>
                    <td border="1" style="width: 203px; text-align: center; height: 13px;">'.$nota_criterio2.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 360px; height: 13px;">3. Objetivos del &nbsp;Proyecto (Grales.y especificos)</td>
                    <td border="1" style="width: 203px; text-align: center; height: 13px;">'.$nota_criterio3.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 360px; height: 13px;">4. Importancia,Alcances y Limitaciones</td>
                    <td border="1" style="width: 203px; text-align: center; height: 13px;">'.$nota_criterio4.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 360px; height: 13px;">5. Justificaci&oacute;n del estudio</td>
                    <td border="1" style="width: 203px; text-align: center; height: 13px;">'.$nota_criterio5.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 360px; height: 13px;">6. Resultados Esperados</td>
                    <td border="1" style="width: 203px; text-align: center; height: 13px;">'.$nota_criterio6.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 360px; height: 13px;">7. Descripci&oacute;n del Sistema</td>
                    <td border="1" style="width: 203px; text-align: center; height: 13px;">'.$nota_criterio7.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 360px; height: 13px;">8. Metodolog&iacute;a para resolver el Problema</td>
                    <td border="1" style="width: 203px; text-align: center; height: 13px;">'.$nota_criterio8.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 360px; height: 13px;">9. Planificaci&oacute;n del Proyecto</td>
                    <td border="1" style="width: 203px; text-align: center; height: 13px;">'.$nota_criterio9.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 360px; height: 13px;">10. Conclusiones y Recomendaciones</td>
                    <td border="1" style="width: 203px; text-align: center; height: 13px;">'.$nota_criterio10.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 360px; height: 13px; text-align: right;">NOTA DOCUMENTO</td>
                    <td border="1" style="width: 203px; text-align: center; height: 13px;">'.$nota_documento.'</td>
                    </tr>
                    </tbody>
                    </table>
                    <p>EXPOSICI&Oacute;N (40%)</p>
                    <table style="height: 18px; width: 567px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 361px; height: 17px;">1. Exposici&oacute;n</td>
                    <td border="1" style="width: 202px; height: 17px; text-align: center;">'.$nota_criterio11.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td border="1" style="width: 361px; height: 14px;">2.Conocimiento del Tema</td>
                    <td border="1" style="width: 202px; height: 14px; text-align: center;">'.$nota_criterio12.'</td>
                    </tr>
                    <tr>
                    <td style="width: 361px; text-align: right;">NOTA EXPOSICION</td>
                    <td border="1" style="width: 202px; text-align: center;">'.$nota_exposicion.'</td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>
                    <table style="height: 18px; width: 567px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 120px; height: 17px;">&nbsp;</td>
                    <td border="1" style="width: 250px; height: 17px;">NOTAL FINAL DEL ANTEPROYECTO</td>
                    <td border="1" style="width: 80pxpx; height: 17px; text-align: center;">'.$nota_anteproyecto.'</td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>
                    <p style="text-align: center;"><span style="text-decoration: underline;"><strong>EVALUADOR</strong></span></p>
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
                    </table>
                    <p>&nbsp; </p>
                    <p>El Anteproyecto se ha evaluado:</p>
                    <table style="height: 11px; width: 527px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 370px; height: 13px; text-align: right;">Con observaciones</td>
                    <td border="1" style="width: 25px; height: 13px;">&nbsp;</td>
                    <td style="width: 130px; text-align: right; height: 13px;">Se debe Replantear</td>
                    <td border="1" style="width: 25px; height: 13px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';

        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Acta_de_Notas_Anteproyecto_equipo_".$id_equipo_tg.".pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    