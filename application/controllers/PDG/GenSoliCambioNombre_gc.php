<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenSoliCambioNombre_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PDG/GenSoliCambioNombre_gc_model');

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
        $pdf->SetTitle('Solicitud de Cambio de Nombre de TG');
        $pdf->SetSubject('Solicitud de Cambio de Nombre de TG');
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
      
        $datos['id_solicitud_academica']=$id;
        $resultado=$this->GenSoliCambioNombre_gc_model->DatosSolicitud($datos);      
        foreach ($resultado->result_array() as $row)
        {
                $ciclo_tg=$row['ciclo_tg'];
                $anio_tg=$row['anio_tg'];
                $nombre_actual=$row['nombre_actual'];
                $nombre_propuesto=$row['nombre_propuesto'];
                $justificacion=$row['justificacion'];
        }     
                   

 
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
        $pdf->Image('@' . $img,'167','24','14','19');
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)


        $html =    '<table  style="height: 56px; width: 606px;">
                    <tbody>
                    <tr style="height: 13px;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>UNIVERSIDAD DE EL SALVADOR</strong></td>
                    </tr>
                    <tr style="height: 13px; text-align: left;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>FACULTAD DE INGENIERIA Y ARQUITECTURA</strong></td>
                    </tr>
                    <tr style="height: 13px;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>ESCUELA DE INGENIERIA DE SISTEMAS INFORMATICOS</strong></td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>';
        $html .=    '<table style="height: 39px;" width="544">
                    <tbody>
                    <tr>
                    <td style="width: 560px; text-align: center;"><strong>SOLICITUD MODIFICACION DE NOMBRE TRABAJO DE GRADUACION</strong></td>
                    </tr>
                    <tr>
                    <td style="width: 560px; text-align: right;"><strong>FECHA: '.$dia.'/'.$mes.'/'.$anio.'</strong></td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>
                    <table border="1" style="height: 20px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">
                    <table style="height: 21px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 280px;">INSCRITO CICLO: '.$ciclo_tg.'</td>
                    <td style="width: 280px;">A&Ntilde;O DE INSCRIPCION: '.$anio_tg.'</td>
                    </tr>
                    <tr>
                    <td style="width: 560px;" colspan="2">&nbsp;</td>
                    </tr>                    
                    <tr>
                    <td style="width: 560px;" colspan="2">ACUERDO J.D DE APROBADO:</td>
                    </tr>
                    </tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>
                    <table border="1" style="height: 21px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">
                    <table style="height: 6px;" width="565">
                    <tbody>
                    <tr>
                    <td style="width: 550px;">NOMBRE ACTUAL SEGUN ACUERDO J.D: '.$nombre_actual.'
                    <p>&nbsp;</p>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>
                    <table border="1"  style="height: 21px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">
                    <table style="height: 6px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">NOMBRE PROPUESTO: '.$nombre_propuesto.'
                    <p>&nbsp;</p>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>
                    <table border="1"  style="height: 21px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">
                    <table style="height: 6px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 550px;">JUSTIFICACION DE MODIFICACION: '.$justificacion.'
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>
                    <table border="1"  style="height: 21px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">
                    <table style="height: 6px;" width="560">
                    <tbody>
                    <tr>
                    <td style="width: 560px;">FIRMAS COORDINADOR DE TRABAJOS DE GRADUACION EISI, DOCENTE ASESOR Y ESTUDIANTES
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p></td>
                    </tr>
                    </tbody>
                    </table>
                    </td>
                    </tr>
                    </tbody>
                    </table>';



        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Solicitud_Cambio_Nombre_TG.pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    