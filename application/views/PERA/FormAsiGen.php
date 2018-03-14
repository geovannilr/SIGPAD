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
    </div>



    <div>
    <?php echo $output; ?>
    </div>

    <!--div><textarea id="field-areas_deficitarias" class="form-control" name="areas_deficitarias" wrap="hard" ></textarea>'</div-->
 
  


</aside>    



<script type="text/javascript">
$(document).ready(function() {

	
	var due_sin_docente = $('select[name="due_sin_docente"]');    
    var d1="";
    var d2="";
    var d3="";
    var d4="";

    var nd1;
    var nd2;
    var nd3;
    var nd4;


	$("#form-button-save").hide();	
    	
	due_sin_docente.change(function() {
		var select_value = this.value;

		$.getJSON('<?=BASE_URL.'/PERA/AsiGen/AreaDeficitarias/'?>'+select_value,
			function(data){
				$.each(data, function(key, val) {
					//$("#field-comentario").val(val.comentario_pais);
					$("#field-id_detalle_pera").val(val.id_detalle_pera);
					$("#field-cum").val(val.cum);
                    //$("#field-area_deficitaria").val(val);               
					$("#field-uv").val(val.uv);
                    $("#field-ciclo").val(val.ciclo);
                    $("#field-anio").val(val.anio);
                    //$("#field-observaciones").val(val.observaciones);                    
                    id_detalle_pera=val.id_detalle_pera;


                    $.getJSON('<?=BASE_URL.'/PERA/AsiGen/Areas_Deficitarias/'?>'+id_detalle_pera,
                    function(miJSON){
                        $.each(miJSON, function(i,item){      

                            if (miJSON[i].id_departamento=='1') {
                                d1 = d1 
                                    +miJSON[i].id_materia+"\t-\t"
                                    +miJSON[i].nota+"\t-\t"
                                    +miJSON[i].nombre+"\n";
                                nd1 = '\nPromedio: \t'+miJSON[i].nota_departamento;  
                            } else if(miJSON[i].id_departamento=='2'){
                                d2 = d2 
                                +miJSON[i].id_materia+"\t-\t"
                                +miJSON[i].nota+"\t-\t"
                                +miJSON[i].nombre+"\n";                         
                                nd2 = '\nPromedio: \t'+miJSON[i].nota_departamento;
                            } else if(miJSON[i].id_departamento=='3'){
                                d3 = d3 
                                +miJSON[i].id_materia+"\t-\t"
                                +miJSON[i].nota+"\t-\t"
                                +miJSON[i].nombre+"\n";                         
                                nd3 = '\nPromedio: \t'+miJSON[i].nota_departamento;
                            } else if(miJSON[i].id_departamento=='4'){
                                d4 = d4 
                                +miJSON[i].id_materia+"\t-\t"
                                +miJSON[i].nota+"\t-\t"
                                +miJSON[i].nombre+"\n";                         
                                nd4 = '\nPromedio: \t'+miJSON[i].nota_departamento;
                            }
                        });                        
                        $("#field-d1").val(d1+nd1);
                        $("#field-d2").val(d2+nd2);
                        $("#field-d3").val(d3+nd3);
                        $("#field-d4").val(d4+nd4);
                                
                    });
				});	                
			}
        );
	});


    var id_detalle_pera = $('#field-id_detalle_pera').val();

    $.getJSON('<?=BASE_URL.'/PERA/AsiGen/Areas_Deficitarias/'?>'+id_detalle_pera,
    function(miJSON){
        $.each(miJSON, function(i,item){            

            if (miJSON[i].id_departamento=='1') {
                d1 = d1 
                    +miJSON[i].id_materia+"\t-\t"
                    +miJSON[i].nota+"\t-\t"
                    +miJSON[i].nombre+"\n";

                nd1 = '\nPromedio: \t'+miJSON[i].nota_departamento;  
            } else if(miJSON[i].id_departamento=='2'){
                d2 = d2 
                +miJSON[i].id_materia+"\t-\t"
                +miJSON[i].nota+"\t-\t"
                +miJSON[i].nombre+"\n";                         
                nd2 = '\nPromedio: \t'+miJSON[i].nota_departamento;
            } else if(miJSON[i].id_departamento=='3'){
                d3 = d3 
                +miJSON[i].id_materia+"\t-\t"
                +miJSON[i].nota+"\t-\t"
                +miJSON[i].nombre+"\n";                         
                nd3 = '\nPromedio: \t'+miJSON[i].nota_departamento;
            } else if(miJSON[i].id_departamento=='4'){
                d4 = d4 
                +miJSON[i].id_materia+"\t-\t"
                +miJSON[i].nota+"\t-\t"
                +miJSON[i].nombre+"\n";                         
                nd4 = '\nPromedio: \t'+miJSON[i].nota_departamento;
            }
        });                        
        $("#field-d1").val(d1+nd1);
        $("#field-d2").val(d2+nd2);
        $("#field-d3").val(d3+nd3);
        $("#field-d4").val(d4+nd4);  
                
    });

});

</script>