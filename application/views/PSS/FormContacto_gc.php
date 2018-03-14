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


 <!-- Con este script se captura el valor del libstbox id_equipo y se muestra de forma dinamica el tema del equipo -->
<script type="text/javascript">
$(document).ready(function() {

    var id_equipo_tg = $('select[name="id_equipo_tg"]');
    //alert("prueba");

    id_equipo_tg.change(function() {
        //alert('prueba');
        var select_value = this.value;
        $.getJSON('http://localhost/sigpa/index.php/pss/Perfil_gc/obtener_tema/'+select_value,
            function(data){
                
                $.each(data, function(key, val) {
                    
                    $("#field-tema").val(val.tema);
                });
                
            });
        
    });
});

</script>
</aside>    

