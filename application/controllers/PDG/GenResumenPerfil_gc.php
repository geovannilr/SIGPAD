<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenResumenPerfil_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PDG/GenResumenPerfil_gc_model');

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
        $pdf->SetTitle('Resumen Perfil de TG');
        $pdf->SetSubject('Resumen Perfil de TG');
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
      
        $datos['id_perfil']=$id;
        $resultado=$this->GenResumenPerfil_gc_model->DatosPerfil($datos);      
        foreach ($resultado->result_array() as $row)
        {
                $id_perfil=$row['id_perfil'];
                $id_detalle_pdg=$row['id_detalle_pdg'];
                $ciclo=$row['ciclo'];
                $anio=$row['anio'];
                $objetivo_general=$row['objetivo_general'];
                $objetivo_especifico=$row['objetivo_especifico'];
                $descripcion=$row['descripcion'];                           
        }   

        //Esta parte quedo comentariado por si en algfuna de las defensas quisieran que aparezcan los nombres de los fulanos
        /*$nombreDocenteAsesor=$this->GenResumenPerfil_gc->DatosDocenteAsesor($id_equipo_tg,$anio_tg);           
        $nombreDirGenProcesosGraduacion=$this->GenResumenPerfil_gc->DatosDirGenProcesosGraduacion();
        $nombreCoordinadorProcesosGraduacion=$this->GenResumenPerfil_gc->DatosCoordinadorProcesosGraduacion();*/
        
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
        $html =    '<table  style="height: 56px; width: 560px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px; text-align: left;"><strong>UNIVERSIDAD DE EL SALVADOR</strong></td>
                    </tr>
                    <tr style="height: 13px; text-align: left;">
                    <td style="width: 560px; height: 13px; text-align: left;"><strong>FACULTAD DE INGENIERIA Y ARQUITECTURA</strong></td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 560px; height: 13px; text-align: left;"><strong>ESCUELA DE INGENIERIA DE SISTEMAS INFORMATICOS</strong></td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table style="width: 560px;">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table style="height: 76px; width: 560px;">
                    <tbody>
                    <tr>
                    <td style="width: 360px;"><strong>RESUMEN DE PERFIL DE TRABAJO DE GRADUACION</strong></td>
                    <td style="width: 80px; text-align: right;"><strong>Correlativo:</strong></td>
                    <td border="1" style="width: 120px; text-align: center;">'.$id_perfil.'</td>
                    </tr>
                    <tr>
                    <td style="width: 321px;" colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                    <td style="width: 360px;">&nbsp;</td>
                    <td style="width: 80px; text-align: right;">CICLO: </td>
                    <td border="1" style="width: 120px; text-align: center;">'.$ciclo.'</td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table style="width: 560px;">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';                    
        $html .=    '<table style="height: 20px; width: 576px;">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">Descripci&oacute;n</td>
                    </tr>
                    <tr>
                    <td border="1" style="width: 560px;">'.$descripcion.'</td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table style="width: 560px;">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';                     
        $html .=    '<table style="height: 28px;" width="576">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">Obtevio General</td>
                    </tr>
                    <tr>
                    <td border="1" style="width: 560px;">'.$objetivo_general.'</td>
                    </tr>
                    </tbody>
                    </table>';
        $html .=    '<table style="width: 560px;">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>'; 
        $html .=    '<table  style="height: 11px;" width="584">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">Objetivos Especificos</td>
                    </tr>
                    <tr>
                    <td border="1" style="width: 560px;">'.$objetivo_especifico.'</td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>
                    <table style="height: 173px;" width="584">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">_____________________________________</td>
                    <td border="1" style="width: 280px; height: 13px;" rowspan="13">&nbsp;</td>
                    </tr>
                    <tr style="height: 26px;">
                    <td style="width: 280px; height: 26px;">Coordinador de Procesos de Graduaci&oacute;n de Escuela de Ing. De Sistemas Informaticos</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">_____________________________________</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 26px;">Director de Escuela&nbsp;de Ing. De Sistemas Informaticos</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">_____________________________________</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">Director General de Procesos de Graduaci&oacute;n</td>
                    <td style="width: 280px; height: 13px; text-align: center;">(Espacio Reservado Junta Directiva)</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    <td style="width: 280px; height: 13px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';                                        



        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Resumen_Perfil.pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    