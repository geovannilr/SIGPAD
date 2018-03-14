<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenResumenPerfilV2_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PDG/GenResumenPerfilV2_gc_model');

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
      
        //a partir del id se obtiene el id_perfil
        $datos['id']=$id;
        $idPerfil=$this->GenResumenPerfilV2_gc_model->ObtenerIdPerfil($datos); 
        //a partir del id se obtiene la cantidad de alumnos
        $cantidad_alumnos=$this->GenResumenPerfilV2_gc_model->ObtenerCantidadAlumnosXEquipo($datos);
        //a partir del id se obtiene la cantidad de asesores
        $cantidad_asesores=$this->GenResumenPerfilV2_gc_model->ObtenerCantidadAsesores($datos);
        //a partir del id se obtiene el estado del perfil
        $estado_perfil=$this->GenResumenPerfilV2_gc_model->ObtenerEstadoPerfil($datos);
        if ($estado_perfil=='A'){
            $es_aprobado='X';
            $es_reprobado=' ';
        }else{
            if ($estado_perfil=='D'){
                $es_aprobado=' ';
                $es_reprobado='X';
            }
        }
        //a partir del id se obtiene el tema de tesis
        $tema_tesis=$this->GenResumenPerfilV2_gc_model->ObtenerTema($datos);

        //a partir del id se obtiene el numero de acta
        $acta_n=$this->GenResumenPerfilV2_gc_model->ObtenerActaN($datos);
        //a partir del id se obtiene el numero de punto
        $punto=$this->GenResumenPerfilV2_gc_model->ObtenerPunto($datos);
        //a partir del id se obtiene el acuerdo
        $acuerdo=$this->GenResumenPerfilV2_gc_model->ObtenerAcuerdo($datos);
        //a partir del id se obtiene la fecha de aprobacion
        $fecha_aprobacion=$this->GenResumenPerfilV2_gc_model->ObtenerFechaAprobacion($datos);
        

        //obtener datos del perfil
        $datos['id_perfil']=$idPerfil;
        $resultado=$this->GenResumenPerfilV2_gc_model->DatosPerfil($datos);      
        foreach ($resultado->result_array() as $row)
        {
                $id_perfil=$row['id_perfil'];
                $id_detalle_pdg=$row['id_detalle_pdg'];
                $ciclo=$row['ciclo'];
                $anio=$row['anio'];
                $objetivo_general=$row['objetivo_general'];
                $descripcion=$row['descripcion'];                           
                $area_tematica_tg=$row['area_tematica_tg'];    
        }   
        //obteniendo los objetivos especificos ya formateado
        $objetivos_especificos=$this->GenResumenPerfilV2_gc_model->ObtenerObjetivosEspecificos($datos); 
        //obteniendo los resultados esperados
        $resultados_esperados_tg=$this->GenResumenPerfilV2_gc_model->ObtenerResultadosEsperados($datos); 

   
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

        //Imagen ubicada al costado superior derecho en formato vertical
        $img = file_get_contents('minerva_el_salvador.gif');
        $pdf->Image('@' . $img,'27','25','14','19');

        //   
        $html =    '<table style="height: 22px; width: 573px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 563px; height: 13px; text-align: center;">&nbsp; <strong>UNIVERSIDAD DE EL SALVADOR</strong></td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 563px; height: 13px; text-align: center;"><strong>FACULTAD DE INGENIERIA Y ARQUTECTURA</strong></td>
                    </tr>
                    <tr style="height: 48px;">
                    <td style="width: 563px; height: 13px; text-align: center;"><strong>RESUMEN DE PERFIL DE TRABAJO DE GRADUACI&Oacute;N</strong></td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 563px; height: 13px; text-align: center;"><strong>CARRERA: Ingenier&iacute;a de Sistemas Inform&aacute;ticos</strong></td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>
                    <table style="height: 47px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 186px;">
                    <strong>A&ntilde;o acad&eacute;mico:</strong> '.$anio.'
                    </td>
                    <td style="width: 186px; text-align: center;">
                    <strong>Ciclo: </strong>'.$ciclo.'
                    </td>
                    <td style="width: 186px; text-align: rigth;">
                    <strong>Fecha:</strong> '.$dia.'/'.$mes.'/'.$anio.'
                    </td>
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
        $html .=    '<table border="1"  style="height: 147px; width: 565px;">
                    <tbody>
                    <tr>
                    <td style="width: 159px;"><strong>Carrera:</strong></td>
                    <td style="width: 398px;">Ingeniería de Sistemas Informaticos</td>
                    </tr>
                    <tr>
                    <td style="width: 159px;"><strong>Nombre del trabajo de &nbsp;graduaci&oacute;n:</strong></td>
                    <td style="width: 398px;">'.$tema_tesis.'</td>
                    </tr>
                    <tr>
                    <td style="width: 159px;"><strong>&Aacute;rea tematica del trabajo de graduaci&oacute;n:</strong></td>
                    <td style="width: 398px; text-align: justify;">'.$area_tematica_tg.'</td>
                    </tr>
                    <tr>
                    <td style="width: 159px;"><strong>N&uacute;mero de Estudiantes:</strong></td>
                    <td style="width: 398px;">'.$cantidad_alumnos.'</td>
                    </tr>
                    <tr>
                    <td style="width: 159px;"><strong>N&uacute;mero de Docentes Asesores:</strong></td>
                    <td style="width: 398px;">'.$cantidad_asesores.'</td>
                    </tr>
                    <tr>
                    <td style="width: 159px;"><strong>Descripci&oacute;n del trabajo de graduaci&oacute;n:</strong></td>
                    <td style="width: 398px; text-align: justify;">'.$descripcion.'</td>
                    </tr>
                    <tr>
                    <td style="width: 159px;"><strong>Objetivo General del Trabajo de Graduaci&oacute;n:</strong></td>
                    <td style="width: 398px; text-align: justify;">'.$objetivo_general.'</td>
                    </tr>
                    <tr>
                    <td style="width: 159px;"><strong>Objetivos Especificos del Trabajo de Graduaci&oacute;n:</strong></td>
                    <td style="width: 398px;">';
        
                    $html .=    '<table style="height: 13px; text-align: justify;" width="395">
                                <tbody>
                                '.$objetivos_especificos.'
                                </tbody>
                                </table>';
        $html .=    '</td>
                    </tr>';
        $html .=   '<tr>
                    <td style="width: 159px;"><strong>Resultados esperados del trabajo de graduaci&oacute;n:</strong></td>
                    <td style="width: 398px;">';
                    
                    $html .=    '<table style="height: 13px; text-align: justify;" width="395">
                                <tbody>
                                '.$resultados_esperados_tg.'
                                </tbody>
                                </table>';
        $html .=    '</td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;&nbsp;</p>
                    <table style="width: 560px;">
                    <tbody>
                    <tr style="height: 14px;">
                    <td style="height: 14px; width: 186px; text-align: center;">&nbsp;___________________</td>
                    <td style="height: 14px; width: 186px; text-align: center;">&nbsp;&nbsp;___________________</td>
                    <td style="height: 14px; width: 186px; text-align: center;">&nbsp;&nbsp;___________________</td>
                    </tr>
                    <tr style="height: 85px;">
                    <td style="height: 85px; width: 186px; text-align: center;">
                    <strong>Coordinador General de Procesos de Graduaci&oacute;n </strong>
                    <strong>Escuela de Ingenier&iacute;a de Sistemas Inform&aacute;ticos</strong>
                    </td>
                    <td style="height: 85px; width: 186px; text-align: center;">
                    <strong>Director</strong>
                    <strong>Escuela de Ingenier&iacute;a de Sistemas Inform&aacute;ticos</strong>
                    </td>
                    <td style="height: 85px; width: 186px; text-align: center;">
                    <strong>Director General de Procesos de Graduaci&oacute;n </strong>
                    <strong>Facultad de Ingenier&iacute;a y Arquitectura</strong>
                    </td>
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
        $html .=    '<table style="height: 23px;" width="550">
                    <tbody>
                    <tr>
                    <td style="width: 267px; text-align: center;">RESOLUCI&Oacute;N DE JUNTA DIRECTIVA:</td>
                    <td style="width: 267px; text-align: center;">Aprobado ('.$es_aprobado.')&nbsp;&nbsp;&nbsp; Denegado ('.$es_reprobado.')</td>
                    </tr>
                    </tbody>
                    </table>
                    <table style="height: 15px;" width="547">
                    <tbody>
                    <tr>
                    <td style="width: 537px;">
                    <p style="text-align: center;">Acta N&deg;: <strong>'.$acta_n.'</strong>   Punto:   <strong>'.$punto.'</strong>   Acuerdo: <strong>'.$acuerdo.'</strong> Fecha: <strong>'.$fecha_aprobacion.'</strong></p>
                    </td>
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