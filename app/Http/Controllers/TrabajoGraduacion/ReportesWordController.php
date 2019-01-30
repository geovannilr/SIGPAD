<?php
namespace App\Http\Controllers\TrabajoGraduacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\pdg_gru_grupoModel;

class ReportesWordController extends Controller{
    public function index(){

    }

    public function actaAprobacion(Request $request){
    	$phpWord = new \PhpOffice\PhpWord\PhpWord();
      $idGrupo = 16;
      $grupo= new pdg_gru_grupoModel();
      $server = $_ENV['SERVER'];
        $estudiantesGrupo = $grupo->getDetalleGrupo($idGrupo);
        $section = $phpWord->addSection();
        if ($server  == 'ln') {
          $imgPath = 'img/header.jpg';
        }else{
          $imgPath = 'img\header.jpg';
        }
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $dia = date('d');
        $mes = date('n');
        $nuevoMes =  $meses[$mes-1];
        $anio = date('Y');
        $section->addImage(public_path($imgPath),array(
        'width'            => 430,
        'wrappingStyle'    => 'square',
        'marginTop'        => 180,
        'positioning'      => 'relative',
        'posHorizontalRel' => 'margin',
        'posVerticalRel'   => 'line',
        ));

        $titulo = "ACTA DE APROBACIÓN DE TRABAJO DE GRADUACIÓN";
        $section->addText($titulo,array('name' => 'Times New Roman', 'size' => 14, 'bold' => true),array('align'=>'center')); //  FONT /PARRAFO
        $section->addText(" ");
        $section->addText(" ");
        $tituloDatosGenerales = "DATOS GENERALES";
        $section->addText($tituloDatosGenerales,array('name' => 'Times New Roman', 'size' => 10, 'bold' => true),array('align'=>'center'));

       $tableStyle = array('borderSize' => 6, 'borderColor' => '999999'); 
       $phpWord->addTableStyle('Datos Generales', $tableStyle);

      
       $table = $section->addTable('Datos Generales');
       $table->addRow();
       $cell = $table->addCell(10000);
       $cell->addText("CARRERA:",array('name' => 'Arial', 'size' => 10, 'bold' => true));
       $cell->addText("\n");
       $cell->addText("Ingeniería de Sistemas Informáticos",array('name' => 'Calibri', 'size' => 14, 'bold' => true),array('align'=>'center'));
       $table->addRow();
       $cell = $table->addCell(10000);
       $cell->addText("CICLO DE INSCRIPCION DEL TRABAJO: Ciclo I - 2019",array('name' => 'Calibri', 'size' => 11, 'bold' => true));
       $section->addText(" ");

       $tituloTrabajo = "NOMBRE DEL TRABAJO DE GRADUACION:";
       $section->addText($tituloTrabajo,array('name' => 'Times New Roman', 'size' => 10, 'bold' => true),array('align'=>'center'));

       $phpWord->addTableStyle('Nombre TDG', $tableStyle);
       $table = $section->addTable('Nombre TDG', $tableStyle);
       $table->addRow();
       $cell = $table->addCell(10000);
       $cell->addText("NOMBRE DE TRABAJO DE GRADUACION",array('name' => 'Arial', 'size' => 10, 'bold' => true));
       $section->addText(" ");

       $phpWord->addTableStyle('Datos Grupo', $tableStyle);
       $table = $section->addTable('Datos Grupo');
       $table->addRow();
       $cell = $table->addCell(10000);
       $cell->addText("DOCENTE ASESOR",array('name' => 'Arial', 'size' => 10, 'bold' => true));
       $table->addRow();
       $cell = $table->addCell(10000);
       $cell->addText("ESTUDIANTES",array('name' => 'Arial', 'size' => 10, 'bold' => true));

       $section->addText(" ");
       $section->addText(" Habiendo sido subsanadas las observaciones se otorgar la nota de aprobación del trabajo de graduación de:",array('name' => 'Times New Roman', 'size' => 12, 'bold' => false),array('align'=>'left'));

      $section->addText(" "); 
      $section->addText(" ");
      $table = $section->addTable('Detalle Grupo');
      $table->addRow();
      $cell = $table->addCell(7000);
      $cell->addText("NOMBRES",array('name' => 'Arial', 'size' => 11, 'underline' => 'single'),array('align'=>'left'));
      $cell = $table->addCell(3000);
      $cell->addText("NOTA FINAL",array('name' => 'Arial', 'size' => 11, 'underline' => 'single'),array('align'=>'right'));

      $table->addRow();
      $cell = $table->addCell(7000);
      $cell->addText("CM11005 - FERNANDO ERNESTO COSME MORALES",array('name' => 'Arial', 'size' => 11),array('align'=>'left'));
      $cell = $table->addCell(3000);
      $cell->addText("0.0",array('name' => 'Arial', 'size' => 11),array('align'=>'right'));

      $table->addRow();
      $cell = $table->addCell(7000);
      $cell->addText("RG12001 - EDGARDO JOSE RAMIREZ GARCIA",array('name' => 'Arial', 'size' => 11),array('align'=>'left'));
      $cell = $table->addCell(3000);
      $cell->addText("0.0",array('name' => 'Arial', 'size' => 11),array('align'=>'right'));

      $table->addRow();
      $cell = $table->addCell(7000);
      $cell->addText("SB12001 - EDUARDO RAFAEL SERRANO BARRERA",array('name' => 'Arial', 'size' => 11),array('align'=>'left'));
      $cell = $table->addCell(3000);
      $cell->addText("0.0",array('name' => 'Arial', 'size' => 11),array('align'=>'right'));

      $table->addRow();
      $cell = $table->addCell(7000);
      $cell->addText("PP10005 - FRANCISCO WILFREDO POLANCO PORTILLO",array('name' => 'Arial', 'size' => 11),array('align'=>'left'));
      $cell = $table->addCell(3000);
      $cell->addText("0.0",array('name' => 'Arial', 'size' => 11),array('align'=>'right'));
      $section->addText(" "); 
      $section->addText(" ");
      $section->addText("Ciudad Universitaria, el día ".$dia." del mes de ".$nuevoMes." de ".$anio.".",array('name' => 'Times New Roman', 'size' => 12));
      $section->addText(" ");
      $section->addText(" ");
      $section->addText('"HACIA LA LIBERTAD POR LA CULTURA"',array('name' => 'Calibri', 'size' => 11, 'bold' => true),array('align'=>'center'));
      $section->addText(" ");
      $section->addText(" ");
      $section->addText(" ");
      $section->addText(" ");
      $section->addText(" ");
      $section->addText('Ing. José María Sánchez Cornejo',array('name' => 'Calibri', 'size' => 12, 'bold' => false),array('align'=>'center'));
      $section->addText('Director Escuela de Ingeniería de Sistemas Informáticos',array('name' => 'Calibri', 'size' => 12, 'bold' => false),array('align'=>'center'));

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('helloWorld.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path('helloWorld.docx'));
    }

}
