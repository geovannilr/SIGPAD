<div class="container">
  <form class="form-horizontal" action="<?=BASE_URL.'/Administrador/Usuario/Crear_Usuario'?>" method="post">
    <fieldset>
      
      <!-- Form Name -->
      <legend>Crear Usuario</legend>
      <p class="text-danger"><?=$output['mensaje']?></p>
      <!-- Select Basic -->
      <div class="form-group">
        <label class="col-md-4 control-label" for="proceso_admin">Proceso Administrativo</label>
        <div class="col-md-4">
          <select id="proceso_admin" name="proceso_admin" class="form-control">
            <?php
            foreach ($output['proceso_admin'] as $row) { 
            ?>
            <option value= "<?=$row->id_proc_admin?>"> <?=$row->nombre_proc_admin?> </option>
            <?php
            }
            ?>
          </select>
        </div>
      </div>

      <!-- Select Basic -->
      <div class="form-group">
        <label class="col-md-4 control-label" for="tipo_usuario">Tipo de Usuario</label>
        <div class="col-md-4">
          <select id="tipo_usuario" name="tipo_usuario" class="form-control">
            <?php
            foreach ($output['tipo_usuario'] as $row) { 
            ?>
            <option value= "<?=$row->id_tipo_usuario?>"> <?=$row->nombre_tipo_usuario?> </option>
            <?php
            }
            ?>
          </select>
        </div>
      </div>

      <!-- Text input-->
      <div class="form-group">
        <label class="col-md-4 control-label" for="email">Correo Electrónico</label>  
        <div class="col-md-4">
        <input id="email" name="email" type="email" placeholder="" class="form-control input-md" required="">
          
        </div>
      </div>

      <!-- Text input-->
      <div class="form-group">
        <label class="col-md-4 control-label" for="nombre">Nombre</label>  
        <div class="col-md-4">
        <input id="nombre" name="nombre" type="text" placeholder="" class="form-control input-md" required="">
          
        </div>
      </div>

      <!-- Text input-->
      <div class="form-group">
        <label class="col-md-4 control-label" for="apellidos">Apellidos</label>  
        <div class="col-md-4">
        <input id="apellidos" name="apellidos" type="text" placeholder="" class="form-control input-md" required="">
          
        </div>
      </div>



      <!-- Text input-->
      <div class="form-group">
        <label class="col-md-4 control-label" for="dui">DUI</label>  
        <div class="col-md-4">
        <input id="dui" name="dui" type="text" placeholder="" class="form-control input-md" >
          
        </div>
      </div>

      <!-- Text input-->
      <div class="form-group">
        <label class="col-md-4 control-label" for="fecha_nac">Fecha de Nacimiento</label>  
        <div class="col-md-4">
          <input id="fecha_nac" name="fecha_nac" type="date" placeholder="" class="form-control input-md" required="">
          
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