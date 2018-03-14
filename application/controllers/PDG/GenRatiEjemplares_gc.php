<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenRatiEjemplares_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PDG/GenRatiEjemplares_gc_model');

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
        $pdf->SetTitle('Ratificación de Ejemplares');
        $pdf->SetSubject('Ratificación de Ejemplares');
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
      
        $datos_equipo['id']=$id;
        $resultado=$this->GenRatiEjemplares_gc_model->DatosEquipo($datos_equipo);      
        foreach ($resultado->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
                $anio_tg=$row['anio_tg'];
                $ciclo_tg=$row['ciclo_tg'];
                $tema=$row['tema'];
                $sigla=$row['sigla'];
        }     
                   
        $nombreDirGenProcesosGraduacion=$this->GenRatiEjemplares_gc_model->DatosDirGenProcesosGraduacion();
        $nombreDirectorEscuela=$this->GenRatiEjemplares_gc_model->DatosDirEscuela();
 
        // Establecemos el contenido para imprimir

        //Calculo del año actual
        $anio =date ("Y"); 
        //Calculo de la fecha actual
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
         
        $fecha=date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        $html  =    '<p style="text-align: right;">Ref. EISI-002-'.$anio.'</p>';
        $html .=   '<p>Ciudad Universitaria, '.$fecha.'</p>';
        $html .=    '<table style="height: 38px;" width="427">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 417px; height: 13px;">ING. '.$nombreDirGenProcesosGraduacion.'</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 417px; height: 13px;">DIRECTORA GENERAL DE PROCESOS DE GRADUACI&Oacute;N</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 417px; height: 13px;">FACULTAD DE INGENIERIA Y ARQUITECTURA</td>
                    </tr>
                    </tbody>
                    </table>
                    <p>Presente.</p>';

        $html .=    '<p>Estimada Ingra. Torres:</p>
                    <p>Reciban un saludo afectuoso, dese&aacute;ndoles&nbsp; &eacute;xitos en el desempe&ntilde;o de sus labores.</p>
                    <p>De acuerdo al Art. 209 del Reglamento de la Gesti&oacute;n Acad&eacute;mica-Admisnitrativa de la Universidad de El Salvador, solicito atentamente ratificaci&oacute;n de los resultados obtenidos en el siguiente Trabajo de Graduaci&oacute;n Ciclo I-2014.</p>
                    <p>Tema de Trabajo de Graduaci&oacute;n: Tema '.$tema.'</p>
                    <p>Desarrollado por:</p>';
    
        $html .=    '<table style="height: 13px;" width="587">
                    <tbody>
                    <tr>
                    <td style="width: 100px;"><strong>CARNET</strong></td>
                    <td style="width: 275px;"><strong>NOMBRE</strong></td>
                    <td style="width: 100px; text-align: center;"><strong>CARRERA</strong></td>
                    <td style="width: 100px; text-align: center;"><strong>NOTA</strong></td>
                    </tr>';

                   $resultadoAlumnos=$this->GenRatiEjemplares_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$ciclo_tg);      
                    foreach ($resultadoAlumnos->result_array() as $row)
                    {
                            $id_due=$row['id_due'];
                            $apellido_nombre=$row['apellido_nombre'];
                            $html .= '<tr>';
                            $html .= '<td style="width: 100px;">'.$id_due.'</td>';
                            $html .= '<td style="width: 275px;">'.$apellido_nombre.'</td>';
                            $html .= '<td style="width: 100px; text-align: center;">I10515</td>';                           
                            $resultadoNota=$this->GenRatiEjemplares_gc_model->NotaSegunCarnet($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);
                            $html .= '<td style="width: 100px; text-align: center;">'.round($resultadoNota,1).'</td>'; 
                            $html .= '</tr>';                            

                    }   


        $html .=    '</tbody>
                    </table>';


        $html .=    '<p>Agradeciendo su atenci&oacute;n prestada, me suscribo de ustedes.</p>
                    <p style="text-align: center;">&ldquo;HACIA LA LIBERTAD POR LA CULTURA&rdquo;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p style="text-align: center;">Ing. '.$nombreDirectorEscuela.'</p>
                    <p style="text-align: center;">Director de la Escuela de&nbsp; Ingenieria de Sistemas Informaticos</p>
                    <p>&nbsp;</p>
                    <p>Anexo. Copia de colector de Nota.</p>';


        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("RatificacionEjemplar_equipo_".$id_equipo_tg.".pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    