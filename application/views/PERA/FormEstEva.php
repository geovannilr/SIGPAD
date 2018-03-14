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

    
    var id_tipo_pera = $('select[name="id_tipo_pera"]');
    var porcentaje_disponible;
    var porcentaje_total;
    var porcentaje = parseFloat($('#field-porcentaje').val());    


    $("#form-button-save").hide();
    //$("#field-id_detalle_pera").hide();
    
    
    id_tipo_pera.change(function() {
        var select_value = this.value;
        //$("#field-area_deficitaria").val(select_value);
        //$("#field-ciclo").val('valor');
        $.getJSON('<?=BASE_URL.'/PERA/EstEva/Consultar/'?>'+select_value,
            function(data){
                $.each(data, function(key, val) {
                    //$("#field-comentario").val(val.comentario_pais);
                    $("#field-estudiante").val(val.estudiante);
                    $("#field-ciclo").val(val.ciclo);
                    $("#field-descripcion_pera").val(val.descripcion_pera);


                    // Funcion para llenar el porcentaje en el ADD
                    $.getJSON('<?=BASE_URL.'/PERA/EstEva/porcentaje_total/'?>'+select_value,
                        function(data){
                            if(data.length>0){
                                $.each(data, function(key, val) {
                                    if(val.porcentaje_total!=null){
                                        porcentaje_total = parseFloat(val.porcentaje_total);
                                        porcentaje_disponible = 1 - porcentaje_total;                                                 
                                        $("#field-porcentaje_disponible").val((porcentaje_disponible).toFixed(2));                                         
                                    }
                                    else
                                        $("#field-porcentaje_disponible").val('1');
                                                                       
                                });
                            } else
                                $("#field-porcentaje_disponible").val('1');  
                                             
                        }
                    ); 



                });
                
            });        
    });

    var tipo_pera = $("#field-id_tipo_pera").val();

    //alert($('[name=id_tipo_pera]').val());

    // Funcion para llenar el porcentaje en el EDIT
    $.getJSON('<?=BASE_URL.'/PERA/EstEva/porcentaje_total/'?>'+tipo_pera,
        function(data){
            if(data.length>0){
                $.each(data, function(key, val) {
                    if(val.porcentaje_total!=null){
                        porcentaje_total = parseFloat(val.porcentaje_total);
                        porcentaje_disponible = 1 - porcentaje_total;
                        porcentaje_disponible += porcentaje;
                        $("#field-porcentaje_disponible").val((porcentaje_disponible).toFixed(2)); 
                    }
                    else
                        $("#field-porcentaje_disponible").val('1');
                                                                                
                                                       
                });
            } else
                $("#field-porcentaje_disponible").val('1');  
                             
        }
    ); 

});

</script>