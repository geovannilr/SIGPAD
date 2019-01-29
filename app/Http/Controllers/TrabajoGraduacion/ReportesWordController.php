<?php
namespace App\Http\Controllers\TrabajoGraduacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportesWordController extends Controller{
    public function index(){
    	$phpWord = new \PhpOffice\PhpWord\PhpWord();


        $section = $phpWord->addSection();
        $section->addImage(public_path('img\header.jpg'),array(
        'width'            => 430,
        'wrappingStyle'    => 'square',
        'marginTop'        => 180,
        'positioning'      => 'relative',
        'posHorizontalRel' => 'margin',
        'posVerticalRel'   => 'line',
        ));

        $titulo = "ACTA DE APROBACIÓN DE TRABAJO DE GRADUACIÓN";
        $section->addText($titulo,array('name' => 'Times New Roman', 'size' => 14, 'bold' => true),array('align'=>'center')); //  FONT /PARRAFO

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
      
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('helloWorld.docx'));
        } catch (Exception $e) {
        }


        return response()->download(storage_path('helloWorld.docx'));
    }

}
