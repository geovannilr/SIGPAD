<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GenReporteNotasPera_gc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Pdfs_model');
        $this->load->model('PERA/GenReporteNotasPera_gc_model');

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
        $pdf->SetAuthor('Eduardo Ceron');
        $pdf->SetTitle('Reporte de Notas PERA');
        $pdf->SetSubject('Reporte de Notas PERA');
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
        
        $datos['id_registro_nota']=$id;
        $resultado=$this->GenReporteNotasPera_gc_model->Datos($datos);      
        foreach ($resultado->result_array() as $row)
        {
                $ciclo = $row['ciclo'];
                $anio = $row['anio'];
                $carnet = $row['id_due'];
                $nombre_estudiante = $row['nombre'];
                $docente_mentor = $row['docente_mentor'];
                $fecha_finalizacion_programa = $row['fecha_finalizacion'];
                $descripcion_proyecto = $row['descripcion'];
                $nota1 = $row['n1'];
                $nota2 = $row['n2'];
                $nota3 = $row['n3'];
                $nota4 = $row['n4'];
                $nota5 = $row['n5'];
                $promedio = $row['promedio'];
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

        if($ciclo==1)
            $ciclo='I';
        elseif ($ciclo==2) 
            $ciclo='II';        


        //   
        // Example of Image from data stream ('PHP rules')
        //Imagen ubicada al costado superior derecho en formato vertical
        $img = file_get_contents('minerva_el_salvador.gif');
        $pdf->Image('@' . $img,'167','24','14','19');
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)


        $html =    '<table style="height: 56px; width: 606px;">
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
                    <tr style="height: 13px;">
                    <td style="width: 450px; height: 13px; text-align: left;"><strong>PROGRAMA ESPECIAL DE REFUERZO ACADEMICO</strong></td>
                    </tr>
                    </tbody>
                    </table>
                    <p>&nbsp;</p>';
        $html .=    '<table style="height: 6px;" width="600">
<tbody>
<tr>
<td style="width: 560px; text-align: center;"><strong>REGISTRO DE NOTA DEL PROGRAMA<br /></strong></td>
</tr>
<tr>
<td style="width: 560px; text-align: center;"><strong>FECHA DE EVALUACIONES: CICLO&nbsp;'.$ciclo.' '.$anio.'<br /></strong></td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<table style="height: 20px;" border="1" width="560">
<tbody>
<tr>
<td style="width: 560px;">
<table style="height: 6px;" width="600">
<tbody>
<tr>
<td style="width: 280px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 280px;">
<p>CARNET: '.$carnet.'</p>
</td>
</tr>
<tr>
<td style="width: 560px;">NOMBRE ESTUDIANTE: '.$nombre_estudiante.'</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table style="height: 21px;" border="1" width="560">
<tbody>
<tr>
<td style="width: 560px;">
<table style="height: 6px;" width="600">
<tbody>
<tr>
<td style="width: 550px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 550px;">DOCENTE MENTOR: '.$docente_mentor.'</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table style="height: 21px;" border="1" width="560">
<tbody>
<tr>
<td style="width: 560px;">
<table style="height: 6px;" width="600">
<tbody>
<tr>
<td style="width: 560px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 560px;">FECHA DE FINALIZACION DEL PROGRAMA: '.$fecha_finalizacion_programa.'</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table style="height: 21px;" border="1" width="560">
<tbody>
<tr>
<td style="width: 560px;">
<table width="600">
<tbody>
<tr>
<td style="width: 550px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 550px;">
<p>DESCRIPCION DEL PROYECTO: '.$descripcion_proyecto.'</p>
<p>&nbsp;</p>
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
<p>&nbsp;Notas obtenidas por el bachiller:</p>
<table style="width: 560px; height: 6px;" border="1">
<tbody>
<tr>
<td>Estudiante</td>
<td>No. 1 (15%)</td>
<td>No.2 (15%)</td>
<td>No. 3 (20%)</td>
<td>No. 4 (20%)</td>
<td>No. 5 (30%)</td>
<td>Promedio</td>
</tr>
<tr>
<td>'.$carnet.'</td>
<td>'.$nota1.'</td>
<td>'.$nota2.'</td>
<td>'.$nota3.'</td>
<td>'.$nota4.'</td>
<td>'.$nota5.'</td>
<td>'.$promedio.'</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Firma del Docente Mentor: _____________________________</p>
<p>&nbsp;</p>';



        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Registro_de_nota_del_PERA.pdf");
        $pdf->Output($nombre_archivo, 'I');


    }





}    