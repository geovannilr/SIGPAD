<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenControlAsesorias_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PDG/GenControlAsesorias_gc_model');

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
        $pdf->SetTitle('Control de Asesorias de TG');
        $pdf->SetSubject('Control de Asesorias de TG');
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
      
        $datos['id_bitacora']=$id;
        $resultado=$this->GenControlAsesorias_gc_model->DatosAsesoria($datos);      
        foreach ($resultado->result_array() as $row)
        {
                $id_equipo_tg=$row['id_equipo_tg'];
                $anio_tg=$row['anio_tesis'];
                $ciclo_tg=$row['ciclo_tesis'];
                $ciclo_asesoria=$row['ciclo_asesoria'];
                $anio_asesoria=$row['anio_asesoria'];
                $tema_asesoria=$row['tema_asesoria'];
                $tematica_tratar=$row['tematica_tratar'];
                $hora_inicio=$row['hora_inicio'];
                $hora_fin=$row['hora_fin'];
                $lugar=$row['lugar'];
                $observaciones=$row['observaciones'];
                $id_due1=$row['id_due1'];
                $hora_inicio_alumno_1=$row['hora_inicio_alumno_1']; 
                $hora_fin_alumno_1=$row['hora_fin_alumno_1'];
                $id_due2=$row['id_due2'];
                $hora_inicio_alumno_2=$row['hora_inicio_alumno_2']; 
                $hora_fin_alumno_2=$row['hora_fin_alumno_2'];
                $id_due3=$row['id_due3'];
                $hora_inicio_alumno_3=$row['hora_inicio_alumno_3']; 
                $hora_fin_alumno_3=$row['hora_fin_alumno_3'];
                $id_due4=$row['id_due4'];
                $hora_inicio_alumno_4=$row['hora_inicio_alumno_4'];  
                $hora_fin_alumno_4=$row['hora_fin_alumno_4'];
                $id_due5=$row['id_due5'];
                $hora_inicio_alumno_5=$row['hora_inicio_alumno_5'];  
                $hora_fin_alumno_5=$row['hora_fin_alumno_5'];                
                $id_docente=$row['id_docente'];
                $hora_inicio_docente=$row['hora_inicio_docente'];  
                $hora_fin_docente=$row['hora_fin_docente'];                                                                 
        }   
        $nombreAlumno1=$this->GenControlAsesorias_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$id_due1);           
        $nombreAlumno2=$this->GenControlAsesorias_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$id_due2);           
        $nombreAlumno3=$this->GenControlAsesorias_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$id_due3);           
        $nombreAlumno4=$this->GenControlAsesorias_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$id_due4);           
        $nombreAlumno5=$this->GenControlAsesorias_gc_model->ApellidosNombresEquipoTg($id_equipo_tg,$anio_tg,$id_due5);           
        $nombreDocenteAsesor=$this->GenControlAsesorias_gc_model->DatosDocenteAsesor($id_equipo_tg,$anio_tg);           
        
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
        $pdf->Image('@' . $img,'167','25','14','19');
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

                    
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
        $html .=    '<table style="width: 319px;">
                    <tbody>
                    <tr>
                    <td style="width: 321px;">&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>';                     
        $html .=    '<table style="height: 13px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px; text-align: center;"><span style="text-decoration: underline;"><strong>HOJA DE CONTROL DE ASESORIAS DE TRABAJOS DE GRADUACION</strong></span></td>
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
        $html .=    '<table style="height: 13px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 280px; text-align: left;">CICLO: '.$ciclo_asesoria.'/'.$anio_asesoria.'</td>
                    <td style="width: 280px; text-align: left;">FECHA: '.$fecha.'</td>
                    </tr>
                    </tbody>
                    </table>';
                  
        $html .=    '<table border="1" style="height: 13px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px; text-align: left;">TEMA: '.$tema_asesoria.'</td>
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
        $html .=    '<table border="1" style="height: 13px;" width="560">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 560px; text-align: left; height: 13px;">TEMATICA A TRATAR: '.$tematica_tratar.'</td>
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
        $html .=    '<table border="1" style="height: 13px;" width="560">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 280px; text-align: left; height: 13px;">HORA INICIO: '.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_inicio)).'</td>
                    <td style="width: 280px; text-align: left; height: 13px;">HORA FINALIZACION: '.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_fin)).'</td>
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
         $html .=   '<table style="height: 13px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;" colspan="4">Grupo N&deg;: '.$id_equipo_tg.'</td>
                    </tr>
                    <tr >
                    <td border="1" style="width: 210px; text-align: center;"><strong>ALUMNOS</strong></td>
                    <td border="1" style="width: 100px; text-align: center;"><strong>FIRMA</strong></td>
                    <td border="1" style="width: 125px; text-align: center;"><strong>ENTRADA</strong></td>
                    <td border="1" style="width: 125px; text-align: center;"><strong>SALIDA</strong></td>
                    </tr>
                    <tr>
                    <td border="1" style="width: 210px; text-align: left;">'.$nombreAlumno1.'</td>
                    <td border="1" style="width: 100px; text-align: center;">&nbsp;</td>
                    <td border="1" style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_inicio_alumno_1)).'</td>
                    <td border="1" style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_fin_alumno_1)).'</td>
                    </tr>
                    <tr>
                    <td border="1" style="width: 210px; text-align: left;">'.$nombreAlumno2.'</td>
                    <td border="1" style="width: 100px; text-align: center;">&nbsp;</td>
                    <td border="1" style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_inicio_alumno_2)).'</td>
                    <td border="1" style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_fin_alumno_2)).'</td>
                    </tr>
                    <tr>
                    <td border="1" style="width: 210px; text-align: left;">'.$nombreAlumno3.'</td>
                    <td border="1" style="width: 100px; text-align: center;">&nbsp;</td>
                    <td border="1" style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_inicio_alumno_3)).'</td>
                    <td border="1" style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_fin_alumno_3)).'</td>
                    </tr>
                    <tr>
                    <td border="1" style="width: 210px; text-align: left;">'.$nombreAlumno4.'</td>
                    <td border="1" style="width: 100px; text-align: center;">&nbsp;</td>
                    <td border="1" style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_inicio_alumno_4)).'</td>
                    <td border="1" style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_fin_alumno_4)).'</td>
                    </tr>      
                    <tr>
                    <td border="1" style="width: 210px; text-align: left;">'.$nombreAlumno5.'</td>
                    <td border="1" style="width: 100px; text-align: center;">&nbsp;</td>
                    <td border="1" style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_inicio_alumno_5)).'</td>
                    <td border="1" style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_fin_alumno_5)).'</td>
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
        $html .=   '<table border="1" style="height: 13px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 210px; text-align: center;"><strong>DOCENTE DIRECTOR</strong></td>
                    <td style="width: 100px; text-align: center;"><strong>FIRMA</strong></td>
                    <td style="width: 125px; text-align: center;"><strong>ENTRADA</strong></td>
                    <td style="width: 125px; text-align: center;"><strong>SALIDA</strong></td>
                    </tr>
                    <tr>
                    <td style="width: 210px; text-align: left;">'.$nombreDocenteAsesor.'</td>
                    <td style="width: 100px; text-align: center;">&nbsp;</td>
                    <td style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_inicio_docente)).'</td>
                    <td style="width: 125px; text-align: center;">'.strftime("%d/%m/%Y %H:%M:%S", strtotime($hora_fin_docente)).'</td>
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
        $html .=    '<table border="1" style="height: 13px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">OBSERVACIONES: '.$observaciones.'</td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>
                    <table style="height: 13px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">LUGAR: '.$lugar.'</td>
                    </tr>
                    </tbody>
                    </table>';                     



        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Control_Asesorias.pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    