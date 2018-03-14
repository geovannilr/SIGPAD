<aside class="right-side strech">
  <?php 
    foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

  <?php echo "<h4><b>Control de Asesorias de TG</b></h4>"; ?>     
  <div style='height:20px;'></div>  
    <div>
    <?php echo $output; ?>
    </div>
<!-- Con este script se captura el valor del libstbox id_equipo y se muestra de forma dinamica el tema del equipo y año de TG -->
<script type="text/javascript">
$(document).ready(function() {

    var id_equipo_tg = $('select[name="id_equipo_tg"]');
    id_equipo_tg.change(function() {
    
    var select_value = this.value;
    $.getJSON('<?=BASE_URL.'/PDG/ControlAsesorias_gc/datos_tema/'?>'+select_value,
        function(data){
            
            $.each(data, function(key, val) {
                
                $("#field-tema").val(val.tema);
                $("#field-anio_tesis").val(val.anio_tg);           
                $("#field-ciclo_tesis").val(val.ciclo_tg);

            
            });
            
        });

    });
    /*Aca se obtiene el carnet del primer alumno del equipo seleccionado*/
    id_equipo_tg.change(function() {
    var select_value = this.value;
    //var id_due1 = $('select[name="id_due1"]');
    $.getJSON('<?=BASE_URL.'/PDG/ControlAsesorias_gc/datos_due1/'?>'+select_value,
        function(data){
            
            $.each(data, function(key, val) {
                $("#id_due1").val(val.id_due);
            });
            
        });
        
    });
    /*Aca se obtiene el carnet del ´segundo alumno del equipo seleccionado*/
    id_equipo_tg.change(function() {
    var select_value = this.value;
    $.getJSON('<?=BASE_URL.'/PDG/ControlAsesorias_gc/datos_due2/'?>'+select_value,
        function(data){
            
            $.each(data, function(key, val) {
                $("#id_due2").val(val.id_due);
            });
            
        });
        
    });
    /*Aca se obtiene el carnet del tercer alumno del equipo seleccionado*/
    id_equipo_tg.change(function() {
    var select_value = this.value;
    $.getJSON('<?=BASE_URL.'/PDG/ControlAsesorias_gc/datos_due3/'?>'+select_value,
        function(data){
            
            $.each(data, function(key, val) {
                $("#id_due3").val(val.id_due);
            });
            
        });
        
    });    
    /*Aca se obtiene el carnet del cuarto alumno del equipo seleccionado*/
    id_equipo_tg.change(function() {
    var select_value = this.value;
    $.getJSON('<?=BASE_URL.'/PDG/ControlAsesorias_gc/datos_due4/'?>'+select_value,
        function(data){
            
            $.each(data, function(key, val) {
                $("#id_due4").val(val.id_due);
            });
            
        });
        
    });
    /*Aca se obtiene el carnet del cuarto alumno del equipo seleccionado*/
    id_equipo_tg.change(function() {
    var select_value = this.value;
    $.getJSON('<?=BASE_URL.'/PDG/ControlAsesorias_gc/datos_due5/'?>'+select_value,
        function(data){
            
            $.each(data, function(key, val) {
                $("#id_due5").val(val.id_due);
            });
            
        });
        
    });    
    /*Aca se obtiene el identificador del docente asesor estipulado como docente director segun equipo seleccionado*/
    $("#field-ciclo_asesoria").change(function() {
    var anio_tesis_value =$('#field-anio_tesis').val();
    var ciclo_tesis_value =$('#field-ciclo_tesis').val();   
    var id_equipo_tg_value =$('#field-id_equipo_tg').val();   
    
    $.getJSON('<?=BASE_URL.'/PDG/ControlAsesorias_gc/datos_docente/'?>'+id_equipo_tg_value+"/"+anio_tesis_value+"/"+ciclo_tesis_value,
        function(data){
            $.each(data, function(key, val) {
                $("#id_docente").val(val.carnet);
            });
            
        });
        
    });   
    $("#field-hora_inicio").change(function(){
        var hora_inicio = $("#field-hora_inicio").val();
        $("#field-hora_inicio_alumno_1").val(hora_inicio);
        $("#field-hora_inicio_alumno_2").val(hora_inicio);
        $("#field-hora_inicio_alumno_3").val(hora_inicio);
        $("#field-hora_inicio_alumno_4").val(hora_inicio);
        $("#field-hora_inicio_alumno_5").val(hora_inicio);
        $("#field-hora_inicio_docente").val(hora_inicio);
    });

    $("#field-hora_fin").change(function(){
        var hora_fin = $("#field-hora_fin").val();
        $("#field-hora_fin_alumno_1").val(hora_fin);
        $("#field-hora_fin_alumno_2").val(hora_fin);
        $("#field-hora_fin_alumno_3").val(hora_fin);
        $("#field-hora_fin_alumno_4").val(hora_fin);
        $("#field-hora_fin_alumno_5").val(hora_fin);
        $("#field-hora_fin_docente").val(hora_fin);
    });

});

</script>  
</aside>