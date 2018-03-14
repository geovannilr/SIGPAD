<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pdfs extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Registro_Roles_model');
        $this->load->model('Pdfs_model');

    }
    
    public function index()
    {
        //$data['provincias'] llena el select con las provincias españolas
        $data['provincias'] = $this->Pdfs_model->getProvincias();
        //cargamos la vista y pasamos el array $data['provincias'] para su uso
        $this->load->view('pdfs_view', $data);
    }
    public function generar_prueba(){
        $registro_Roles = $this->Registro_Roles_model->getRegistroRoles();
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->AddPage();
        //$this->load->view('Registro_Roles_view', $data);
        $html = '';
        $html .= '<h2 style="text-align: center">Tabla</h2>';
        $html .= '<table>';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td>id_registro_roles</td>';
        $html .= '<td>usuario</td>';
        $html .= '<td>rol</td>';
        $html .= '<td>valido_hasta</td>';
        $html .= '</tr>';

                    foreach($registro_Roles as $fila)
                    {
$html .= '                  <tr>';
        $html .= '<td>'.$fila->id_registro_roles.'</td>';
        $html .= '<td>'.$fila->usuario.'</td>';
        $html .= '<td>'.$fila->rol.'</td>';
        $html .= '<td>'.$fila->valido_hasta.'</td>';
        $html .= '</tr>';
        }
        

        $html .= '</tbody>';
        $html .= '</table>';

        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        //echo(json_encode($registro_Roles));
        $pdf->Output();
        //$this->load->library('Pdf');
        //$dimensiones=array (140,200); 
        //$MiPDF=new PDF('P','mm',$dimensiones,false,'ISO-8859-1'); 

        //$MiPDF->Write(0,(file_get_contents('regenta.txt')),'',0,'J',1); 
        //$MiPDF->Output(); 
    }

    public function generar() {
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Israel Parra');
        $pdf->SetTitle('Ejemplo de provincías con TCPDF');
        $pdf->SetSubject('Tutorial TCPDF');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

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
        $pdf->SetFont('freemono', '', 14, '', true);

// Añadir una página
// Este método tiene varias opciones, consulta la documentación para más información.
        $pdf->AddPage();

//fijar efecto de sombra en el texto
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

// Establecemos el contenido para imprimir
        $provincia = '01';//quemando elv alor solo apra prueba    //$this->input->post('provincia');
        $provincias = $this->Pdfs_model->getProvinciasSeleccionadas($provincia);
        foreach($provincias as $fila)
        {
            $prov = $fila['p.provincia'];
        }
        //preparamos y maquetamos el contenido a crear
        $html = '';
        $html .= "<style type=text/css>";
        $html .= "th{color: #fff; font-weight: bold; background-color: #222}";
        $html .= "td{background-color: #AAC7E3; color: #fff}";
        $html .= "</style>";
        $html .= "<h2>Localidades de ".$prov."</h2><h4>Actualmente: ".count($provincias)." localidades</h4>";
        $html .= "<table width='100%'>";
        $html .= "<tr><th>Id localidad</th><th>Localidades</th></tr>";
        
        //provincias es la respuesta de la función getProvinciasSeleccionadas($provincia) del modelo
        foreach ($provincias as $fila) 
        {
            $id = $fila['l.id'];
            $localidad = $fila['l.localidad'];

            $html .= "<tr><td class='id'>" . $id . "</td><td class='localidad'>" . $localidad . "</td></tr>";
        }
        $html .= "</table>";

// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Localidades de ".$prov.".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }
}