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

    var id_detalle_pera = $('select[name="id_detalle_pera"]');
	
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

    id_detalle_pera.change(function() {
        var select_value = this.value;
        //$("#field-area_deficitaria").val(select_value);
        //var promedio = parseInt(select_value);
        
        //promedio += 3;
        //promedio %= 10;

        $.getJSON('<?=BASE_URL.'/PERA/RegNot/Nota_Final/'?>'+select_value,
            function(data){
                $.each(data, function(key, val) {
                    //$("#field-comentario").val(val.comentario_pais);
                    //$("#field-area_deficitaria").val(val.area_deficitaria);
                    //$("#field-area_deficitaria").val(val);
                    //$("#field-area_deficitaria").val('val');
                    promedio = parseFloat(val.promedio);

                    $("#field-n1").val(promedio+1);
                    $("#field-n2").val(promedio-1);
                    $("#field-n3").val(promedio+0.5);
                    $("#field-n4").val(promedio-0.5);
                    $("#field-n5").val(promedio);

                    $("#field-promedio").val(promedio);
                   
                });
                
            });

        /*$("#field-n1").val(promedio+1);
        $("#field-n2").val(promedio-1);
        $("#field-n3").val(promedio+0.5);
        $("#field-n4").val(promedio-0.5);
        $("#field-n5").val(promedio);

        $("#field-promedio").val(promedio);*/

        
    });
});

</script>
