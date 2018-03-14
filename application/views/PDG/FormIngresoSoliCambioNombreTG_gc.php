<aside class="right-side strech">
  <?php 
    foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

  <?php echo "<h4><b>Ingreso Solicitud Cambio de Nombre de TG</b></h4>"; ?>     
  <div style='height:20px;'></div>  
    <div>
    <?php echo $output; ?>
    </div>
<!-- Con este script se captura el valor del libstbox id_equipo y se muestra de forma dinamica el tema del equipo y año de TG -->
<script type="text/javascript">
$(document).ready(function() {

    var id_equipo_tg = $('select[name="id_equipo_tg"]');
    var id_docente = $('select[name="id_docente"]');

    id_equipo_tg.change(function() {
    
    var select_value = this.value;
    $.getJSON('<?=BASE_URL.'/PDG/IngresoSoliCambioNombreTG_gc/datos_tema/'?>'+select_value,
        function(data){
            
            $.each(data, function(key, val) {
                
                $("#field-tema_tesis").val(val.tema);
                $("#field-anio_tesis").val(val.anio_tg);
                $("#field-ciclo_tesis").val(val.ciclo_tg);
            });
            
        });
        
    });  

});

</script>        
</aside>