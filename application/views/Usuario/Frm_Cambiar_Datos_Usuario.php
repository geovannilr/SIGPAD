
<div class="container">
  <form class="form-horizontal" action="<?=BASE_URL.'/Administrador/Usuario/Cambiar_Datos_Usuario'?>" method="post">
    <fieldset>
      
      <!-- Form Name -->
      <legend>Cambiar Datos</legend>
      <p class="text-danger"><?=$output['mensaje']?></p>

      <!-- Text input-->
      <div class="form-group">
        <label class="col-md-4 control-label" for="nombre">Nombre</label>  
        <div class="col-md-4">
        <input id="nombre" name="nombre" type="text" placeholder="" class="form-control input-md" required="" value = "<?=$output['datos_usuario']['nombre']?>">
          
        </div>
      </div>

      <!-- Text input-->
      <div class="form-group">
        <label class="col-md-4 control-label" for="apellidos">Apellidos</label>  
        <div class="col-md-4">
        <input id="apellidos" name="apellidos" type="text" placeholder="" class="form-control input-md" required="" value = "<?=$output['datos_usuario']['apellidos']?>">
          
        </div>
      </div>



      <!-- Text input-->
      <div class="form-group">
        <label class="col-md-4 control-label" for="dui">DUI</label>  
        <div class="col-md-4">
        <input id="dui" name="dui" type="text" placeholder="" class="form-control input-md" value = "<?=$output['datos_usuario']['DUI']?>">
          
        </div>
      </div>

      <!-- Text input-->
      <div class="form-group">
        <label class="col-md-4 control-label" for="fecha_nac">Fecha de Nacimiento</label>  
        <div class="col-md-4">
          <input id="fecha_nac" name="fecha_nac" type="date" placeholder="" class="form-control input-md" required="" value = "<?=$output['datos_usuario']['fecha_nac']?>">
          
        </div>
      </div>

      <!-- Button (Double) -->
      <div class="form-group">
        <label class="col-md-4 control-label" for="aceptar"></label>
        <div class="col-md-8">
          <input type="submit" id="aceptar" name="aceptar" class="btn btn-primary" value = "Aceptar">
          <input type = "reset" id="Reestablecer" name="Reestablecer" class="btn btn-danger">
        </div>
      </div>

    </fieldset>
  </form>

</div><!-- /container -->
<script>
    $(function(){           

            $('input[type=date]').datepicker({
                changeYear: true,
                dateFormat: "dd-mm-yy"
            });
            
    });
</script>