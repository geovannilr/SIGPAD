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
    var uv_restantes;
    var uv_edit = 0;    
    var miSelect =  $('#field-uv_asignadas');   

    uv_edit = parseInt($("select[id=field-uv_asignadas]").val());


	$("#form-button-save").hide(); // Deshabilitar el boton de Guardar
    //$("#field-comentario").val(2);

    /*$("#save-and-go-back-button").click(function(){
        $("#field-comentario").val(2);
        if(parseInt($("select[id=field-uv_asignadas]").val())>parseInt($("#field-uv_asignables").val()))
        {
            //$("#field-uv_asignadas").val($("#field-uv_asignables").val());
            $("#field-comentario").val($("#field-uv_asignables").val());
            $("#field-observaciones").val($("select[id=field-uv_asignadas]").val());
            
            
            miSelect.val(miSelect.children('option:first').val());
                    


        }
        
    });  */

    // Validación de UV
    $('select[id=field-uv_asignadas]').change(function(){
        if(parseInt(this.value)>parseInt($("#field-uv_asignables").val()))
        {                        
            miSelect.val(miSelect.children('option:first').val());                    
        }
    });
	

	due_sin_docente.change(function() {
		var select_value = this.value;
        //$("#field-area_deficitaria").val(select_value);
        //$("#field-ciclo").val('valor');
		$.getJSON('<?=BASE_URL.'/PERA/AsiDoc/Consultar/'?>'+select_value,
			function(data){
				$.each(data, function(key, val) {

					//$("#field-id_tipo_pera").val(val.id_tipo_pera);					
				    $("#field-uv_pera").val(val.uv_pera);           
					//$("#field-uv").val(val.uv);
                    $("#field-ciclo").val(val.ciclo);

                    $("#field-observaciones").val(val.observaciones);


                    // Funcion para llenar las U.V. asignables en el Add -- Dato calculado no de Grid --
                    $.getJSON('<?=BASE_URL.'/PERA/AsiDoc/uv_asignables/'?>'+select_value,
                        function(data){
                            if(data.length>0){
                                $.each(data, function(key, val) {
                                    uv_restantes = val.uv_pera - val.uv_total_asignadas;                    
                                    $("#field-uv_asignables").val(uv_restantes); 
                                                                       
                                });
                            } else
                                $("#field-uv_asignables").val(val.uv_pera);  
                                             
                        }
                    );               
				});
				
			});
		
	});


    var estudiante = $('#field-estudiante').val();    
    var due = estudiante.slice(0,7);
    // Funcion para llenar las U.V. asignables en el Edit -- Dato calculado no de Grid --   
    $.getJSON('<?=BASE_URL.'/PERA/AsiDoc/uv_asignables/'?>'+due,
            function(data){
                $.each(data, function(key, val) {

                    uv_restantes = val.uv_pera - val.uv_total_asignadas;

                    uv_restantes += uv_edit;                    
                                
                    $("#field-uv_asignables").val(uv_restantes);           
                    //$("#field-uv_asignables").val(due);                                 
                });            
            }
    );

});

</script>