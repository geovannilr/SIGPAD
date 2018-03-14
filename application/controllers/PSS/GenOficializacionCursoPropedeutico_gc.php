<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenOficializacionCursoPropedeutico_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PSS/GenOficializacionCursoPropedeutico_gc_model');

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
        $pdf->SetTitle('Oficilización Curso Propedeutico');
        $pdf->SetSubject('Oficilización Curso Propedeutico');
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
   
         $datos['id_detalle_expediente']=$id;

        //Calculo del año actual
        /*$anio =date ("Y"); 
        //Calculo de la fecha actual
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes=date('m');
        $dia=date('d');         
        $fecha=date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;*/
        //   
        ///obtener fecha de oficiliazacion desglosada
        $array_fecha_desglosada=$this->GenOficializacionCursoPropedeutico_gc_model->ObtenerFechaDesglosada($datos); 
        foreach ($array_fecha_desglosada->result_array() as $row)
        {
                $dia=$row['dia'];
                $mes=$row['mes_case'];
                $anio=$row['anio'];
        }  
        ///obtener datos de estudiante a oficializar
        $array_datos_alumno=$this->GenOficializacionCursoPropedeutico_gc_model->ObtenerDatosAlumno($datos); 
        foreach ($array_datos_alumno->result_array() as $row)
        {
                $id_due=$row['id_due'];
                $nombre_apellido_alumno=$row['nombre_apellido_alumno'];
        }        

        $html =    '<table style="height: 189px;" width="603">
                    <tbody>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; text-align: center; height: 13px;"><strong>OFICIALIZACION DEL CURSO PROPEDEUTICO</strong></td>
                    </tr>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; text-align: center; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; text-align: center; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; text-align: center; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; text-align: center; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; text-align: center; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; text-align: center; height: 13px;">&nbsp;ESCUELA DE: <strong>Ingenir&iacute;a de Sistemas Inform&aacute;ticos</strong></td>
                    </tr>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; text-align: center; height: 13px;">SUBUNIDAD DE PROYECCION SOCIAL</td>
                    </tr>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px; text-align: justify;">
                    <td style="width: 593px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 593px; height: 13px;">
                    <p style="text-align: justify;">POR ESTE MEDIO SE OFICIALIZA LA PARTICIPACION DEL BR.<strong>'.$nombre_apellido_alumno.'</strong> CON CARN&Eacute;: <strong>'.$id_due.'</strong> EN EL CURSO PROPEDEUTICO DE NUEVO INGRESO PARA EL A&Ntilde;O: <strong>'.$anio.'</strong> EN FACULTAD DE INGENIERIA Y ARQUITECTURA.</p>
                    </td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 593px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 593px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 593px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 593px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 593px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 593px; height: 13px;">&nbsp;</td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 593px; height: 13px; text-align: center;"><strong>________________________________</strong></td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 593px; height: 13px; text-align: center;">NOMBRE Y FIRMA DEL COORDINADOR DE LA SUPS</td>
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
        $nombre_archivo = utf8_decode("Oficializacion_Curso_Propedeutico.pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    