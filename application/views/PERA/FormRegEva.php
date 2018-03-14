<aside class="right-side strech">
  
    <?php 
    foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

    
  <div style='height:20px;'></div>  
    <div>
    <?php echo $output; ?>
    </div>


</aside>    




<script type="text/javascript">
$(document).ready(function() {

	var due_sin_docente = $('select[name="due_sin_docente"]');
    
	
	due_sin_docente.change(function() {
		var select_value = this.value;
        //$("#field-area_deficitaria").val(select_value);
        //$("#field-ciclo").val('valor');
		$.getJSON('http://localhost/SIGPA/index.php/PERA/AsiDoc/AreaDeficitarias/'+select_value,
			function(data){
				$.each(data, function(key, val) {
					//$("#field-comentario").val(val.comentario_pais);
					$("#field-area_deficitaria").val(val.area_deficitaria);
                    //$("#field-area_deficitaria").val(val);
                    //$("#field-area_deficitaria").val('val');
					$("#field-uv").val(val.uv);
                    $("#field-ciclo").val(val.ciclo);
                    $("#field-anio").val(val.anio);
				});
				
			});
		
	});
});

</script>
<!--script>
$(document).ready(function(){
      
    //al cambiar el select poblaciones
   $("#field-due_sin_docente").on("change", function(event){
        //event.preventDefault();
        
        var Estudiante = $("#field-due_sin_docente").val();
        var AreaDeficitaria=$("#field-area_deficitaria").val();
        var url;
        url='http://localhost/SIGPA/index.php/PERA/AsiDoc/AreaDeficitaria';
        $.ajax({
            url:url,
            type:'post',
            data:'field-due_sin_docente='+Estudiante,
            //dataType:'json',
            //cache:false,
            success:function(result){
                    /*switch(result){
                        case "0":
                            alert("No se insertó");
                            break;
                        case "1":
                            alert("Realizado con éxito");
                            //$("input[type=text]").val(""); // esto es para limpiar campos
                            location.reload();
                            break;*/
                        
                    //}//switch
                    //alert("Cambio de Estudiante");
                    valor=result.split('~');
                    $("#field-area_deficitaria").val(valor[0]);
                    $("#field-uv").val(valor[1]);
                    $("#field-ciclo").val(valor[2]);
                    $("#field-anio").val(valor[3]);
                    //$("#field-uv").val(result('uv');
                    //$('#field-area_deficitaria').attr('readonly', true);
                        //location.reload();
                },
            error:function(result){
                    alert("Error Asignacion ya establecida!!!");
                    $("#field-area_deficitaria").val(result);
                }
            
        });//$.ajax
        
    });//$("#Form_Usuario_Nuevo").on("submit",function(event){});
       
   
});
</script-->