<aside class="right-side strech">
  <?php 
    foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

  <?php echo "<h4><b>Asignación Docente Asesor</b></h4>"; ?>     
  <div style='height:20px;'></div>  
    <div>
    <?php echo $output; ?>
    </div>
<!-- Con este script se captura el valor del libstbox id_equipo y se muestra de forma dinamica el tema del equipo -->
<script type="text/javascript">
$(document).ready(function() {

    var id_equipo_tg = $('select[name="id_equipo_tg"]');
    var id_docente = $('select[name="id_docente"]');

    id_equipo_tg.change(function() {
    
    var select_value = this.value;
    $.getJSON('<?=BASE_URL.'/PDG/AsigDocenteAsesor_gc/datos_tema/'?>'+select_value,
        function(data){
            
            $.each(data, function(key, val) {
                
                $("#field-tema_tesis").val(val.tema);
                $("#field-anio_tesis").val(val.anio_tg);
                $("#field-ciclo_tesis").val(val.ciclo_tg);
            });
            
        });
        
    });
//Con esta función se captura el valor del libstbox id_docente y se muestra de forma dinamica el nombre y apellido del docente 
    id_docente.change(function() {
    
    var select_value = this.value;
    $.getJSON('<?=BASE_URL.'/PDG/AsigDocenteAsesor_gc/datos_docente/'?>'+select_value,
        function(data){
            
            $.each(data, function(key, val) {
                
                $("#field-nombreApellidoDocente").val(val.nombre+" "+val.apellido);
                $("#field-correo_docente").val(val.email);
            });
            
        });
        
    });

//Con esta función se cambia el valor del mensaje message_insert_error segun la validacion de datos para el boton form-button-save
    $("#form-button-save").click(function() {
    var id_equipo_value =$('#field-id_equipo_tg').val();
    var id_docente_value = $('#field-id_docente').val();
    $.get('<?=BASE_URL.'/PDG/AsigDocenteAsesor_gc/valida_data/'?>'+id_equipo_value+"/"+id_docente_value,
            {
            },
            function(datos) {
                message_insert_error = datos;
            }        
    );  
        
    });
//Con esta función se cambia el valor del mensaje message_insert_error segun la validacion de datos para el boton save-and-go-back-button
    $("#save-and-go-back-button").click(function() {
    var id_equipo_value =$('#field-id_equipo_tg').val();
    var id_docente_value = $('#field-id_docente').val();
    $.get('<?=BASE_URL.'/PDG/AsigDocenteAsesor_gc/valida_data/'?>'+id_equipo_value+"/"+id_docente_value,
            {
            },
            function(datos) {
                message_insert_error = datos;
            }        
    );  
        
    });    

});

</script>    
</aside>