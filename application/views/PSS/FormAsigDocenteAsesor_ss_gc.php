<aside class="right-side strech">
  <?php 
    foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

  <?php echo "<h4><b>Asignación Docente Asesor de Servicio Social</b></h4>"; ?>     
  <div style='height:20px;'></div>  
    <div>
    <?php echo $output; ?>
    </div>
<!-- Con este script se captura el valor del libstbox id_equipo y se muestra de forma dinamica el tema del equipo -->
<script type="text/javascript">
$(document).ready(function() {

    var id_det_expediente = $('select[name="id_det_expediente"]');
    var id_docente = $('select[name="id_docente"]');

    id_det_expediente.change(function() {
    
    var select_value = this.value;
    $.getJSON('<?=BASE_URL.'/PSS/AsigDocenteAsesor_ss_gc/datos_expediente/'?>'+select_value,
        function(data){
            
            $.each(data, function(key, val) {               
                $("#field-id_det_expediente").val(val.id_detalle_expediente);
                $("#field-id_servicio_social").val(val.nombre_servicio_social);
                $("#field-id_due").val(val.id_due);
                $("#field-nombre").val(val.nombre);
                $("#field-apellido").val(val.apellido);
            });
            
        });
        
    });
//Con esta función se captura el valor del libstbox id_docente y se muestra de forma dinamica el nombre y apellido del docente 
    id_docente.change(function() {
    
    var select_value = this.value;
    $.getJSON('<?=BASE_URL.'/PSS/AsigDocenteAsesor_ss_gc/datos_docente/'?>'+select_value,
        function(data){
            
            $.each(data, function(key, val) {
                
                $("#field-nombreApellidoDocente").val(val.nombre+" "+val.apellido);
                $("#field-correo_docente").val(val.email);
            });
            
        });
        
    });

//Con esta función se cambia el valor del mensaje message_insert_error segun la validacion de datos para el boton form-button-save
    $("#form-button-save").click(function() {
    var id_det_expediente_value =$('#field-id_det_expediente').val();
    var id_due_value = $('#field-id_due').val();//nuevo
    var id_docente_value = $('#field-id_docente').val();
    var es_docente_pss_principal_value = $('input[name="es_docente_pss_principal"]:checked').val();
    var id_docente_value = $('#field-id_docente').val();
    $.get('<?=BASE_URL.'/PSS/AsigDocenteAsesor_ss_gc/valida_data/'?>'+id_det_expediente_value+"/"+id_due_value+"/"+id_docente_value+"/"+es_docente_pss_principal_value,
            {
            },
            function(datos) {
                message_insert_error = datos;
            }        
    );  
        
    });
//Con esta función se cambia el valor del mensaje message_insert_error segun la validacion de datos para el boton save-and-go-back-button
    $("#save-and-go-back-button").click(function() {
    var id_det_expediente_value =$('#field-id_det_expediente').val();
    var id_due_value = $('#field-id_due').val();//nuevo
    var id_docente_value = $('#field-id_docente').val();
    var es_docente_pss_principal_value = $('input[name="es_docente_pss_principal"]:checked').val();
    $.get('<?=BASE_URL.'/PSS/AsigDocenteAsesor_ss_gc/valida_data/'?>'+id_det_expediente_value+"/"+id_due_value+"/"+id_docente_value+"/"+es_docente_pss_principal_value,
            {
            },
            function(datos) {
                message_insert_error = datos;
                message_update_error = datos;
            }        
    );  
        
    });      

});

</script>    
</aside>