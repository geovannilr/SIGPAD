<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenRemiEjemplares_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PDG/GenRemiEjemplares_gc_model');

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
        $pdf->SetTitle('Remisión de Ejemplares');
        $pdf->SetSubject('Remisión de Ejemplares');
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
        $resultado=$this->GenRemiEjemplares_gc_model->DatosEquipo($datos_equipo);      
        foreach ($resultado->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
                $anio_tg=$row['anio_tg'];
                $ciclo_tg=$row['ciclo_tg'];
                $tema=$row['tema'];
                $sigla=$row['sigla'];
        }     
                   
        $nombreAdminAcademico=$this->GenRemiEjemplares_gc_model->DatosAdminAcademica();
        $nombreSecreEscuela=$this->GenRemiEjemplares_gc_model->DatosSecreEscuela();
 
        // Establecemos el contenido para imprimir

        //Calculo del año actual
        $anio =date ("Y"); 
        //Calculo de la fecha actual
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
         
        $fecha=date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        //   
        $html =    '<p style="text-align: right;">Ref. EISI-003-'.$anio.'</p>
                    <p>Para: &nbsp; &nbsp; &nbsp; &nbsp;ING. '.strtoupper($nombreAdminAcademico).'</p>
                    <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Administrador Acad&eacute;mico de la Facultad de Ingenier&iacute;a y Arquitectura</p>
                    <p>DE: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;ING. '.strtoupper($nombreSecreEscuela).'</p>
                    <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Secretario de la Escuela de Ingenier&iacute;a de Sistemas Informaticos</p>
                    <p>ASUNTO: REMISION DE 2 EJEMPLARES, 2 GRABACIONES EN CD Y NOTAS DE &nbsp;&nbsp;TRABAJO DE GRADUACI&Oacute;N.</p>
                    <p>FECHA: &nbsp; &nbsp; &nbsp;'.$fecha.'</p>
                    <p style="text-align: justify;">Por este medio y en cumplimiento al art&iacute;culo 212 del Reglamento de la Gesti&oacute;n Acad&eacute;mica-Administrativa, de la Universidad de El Salvador, notifico a Usted: que los Brs.</p>';
        $html .=    '<table style="height: 68px;" width="111">
                    <tbody>';
                   $resultadoAlumnos=$this->GenRemiEjemplares_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$ciclo_tg);      
                    foreach ($resultadoAlumnos->result_array() as $row)
                    {
                            $id_due=$row['id_due'];
                            $apellido_nombre=$row['apellido_nombre'];
                            $html .= '<tr>
                                     <td style="width: 500px;">'.$apellido_nombre.'</td>
                                     </tr>';                            

                    }                      
        $html .=    '</tbody>
                    </table>';
                    
        $html .=    '<p>Estudiantes de INGENIERIA DE SISTEMAS INFORM&Aacute;TICOS, desarrollaron y aprobaron su Trabajo de Graduaci&oacute;n en el ciclo '.$ciclo_tg.'/'.$anio_tg.' , con la calificaci&oacute;n final de:</p>';
        $html .= '<table style="height: 48px; width: 548px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 241px; height: 13px; text-align: center;"><span style="text-decoration: underline;"><strong>NOMBRES</strong></span></td>
                    <td style="width: 121px; text-align: center; height: 13px;"><span style="text-decoration: underline;"><strong>NOTA FINAL</strong></span></td>
                    <td style="width: 172px; height: 13px; text-align: left;"><span style="text-decoration: underline;"><strong>(LETRAS)</strong></span></td>
                    </tr>';

                   $resultadoAlumnos=$this->GenRemiEjemplares_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$ciclo_tg);      
                    foreach ($resultadoAlumnos->result_array() as $row)
                    {
                            $id_due=$row['id_due'];
                            $apellido_nombre=$row['apellido_nombre'];
                            $html .= '<tr style="height: 13px;">';
                            $html .= '<td style="width: 241px; height: 13px;">'.$apellido_nombre.'</td>';
                            $resultadoNota=$this->GenRemiEjemplares_gc_model->NotaSegunCarnet($id_equipo_tg,$anio_tg,$ciclo_tg,$id_due);
                            $html .='<td style="width: 121px; height: 13px; text-align: center;">'.round($resultadoNota,1).'</td>';
                            $resultadoNotaEnLetra=$this->GenRemiEjemplares_gc_model->NotaSegunCarnetEnLetra($resultadoNota);
                            $html .='<td style="width: 172px; height: 13px;">'.$resultadoNotaEnLetra.'</td>';  
                            $html .= '</tr>';                            

                    }   


        $html .=    '</tbody>
                    </table>';


        $html .=    '<p>Asimismo la presentaci&oacute;n de los 2 ejemplares y 2 grabaciones en CD,reglamentarios de su Trabajo de Graduaci&oacute;n, denominado:</p>
                    <p>TEMA:&nbsp;'.$tema.' ('.$sigla.')</p>
                    <p>En espera de que esta informaci&oacute;n sea de utilidad para los efectos acad&eacute;micos correspondientes, me suscribo de usted atentamente.</p>';


        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("RemisionEjemplar_equipo_".$id_equipo_tg.".pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    