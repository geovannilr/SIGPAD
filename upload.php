<?php 

$time = time();
//$datetime = date("d-m-Y-His", $time);
$datetime = date("Ymd_His", $time);


$carpetaAdjunta="carga_desa/";
// Contar envían por el plugin
$obj_a_subir =count(isset($_FILES['obj_a_subir']['name'])?$_FILES['obj_a_subir']['name']:0);
$info_obj_subidas = array();
for($i = 0; $i < $obj_a_subir; $i++) {

	// El nombre y nombre temporal del archivo que vamos para adjuntar
	$nombreArchivo=isset($_FILES['obj_a_subir']['name'][$i])?$_FILES['obj_a_subir']['name'][$i]:null;
	$nombreTemporal=isset($_FILES['obj_a_subir']['tmp_name'][$i])?$_FILES['obj_a_subir']['tmp_name'][$i]:null;
	
	//si se quiere guardar con el nombre real usar esta linea
	//$rutaArchivo=$carpetaAdjunta.$nombreArchivo;
	//el archivo se guarda con la fecha_hora_correlativo Ej. 20161016_203226_01
	$rutaArchivo=$carpetaAdjunta.$datetime."_".($i+1)."_".$nombreArchivo;
	
	move_uploaded_file($nombreTemporal,$rutaArchivo);
	
	$info_obj_subidas[$i]=array("caption"=>"$nombreArchivo","height"=>"120px","url"=>"borrar.php","key"=>$nombreArchivo);
	$obj_a_subirSubidas[$i]="<height='120px' src='$rutaArchivo' class='file-preview-image'>";

	}

//$arr = array("file_id"=>0,"overwriteInitial"=>true,"initialPreviewConfig"=>$info_obj_subidas,"initialPreview"=>$obj_a_subirSubidas);  
$arr = array("file_id"=>0,"overwriteInitial"=>false);  
echo json_encode($arr);
?>

