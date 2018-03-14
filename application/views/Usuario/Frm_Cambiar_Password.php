<div class="container">
    <form class="form-horizontal" action="<?=BASE_URL.'/Administrador/Usuario/Confirm_Cambiar_Password'?>" method="post">
        <fieldset>

            <!-- Form Name -->
            <legend>Cambiar Password</legend>
            <p><?=$output?></p>
            <!-- Password input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="password_actual">Password Actual</label>
              <div class="col-md-4">
                <input id="password_actual" name="password_actual" type="password" placeholder="" class="form-control input-md" required="">
                
              </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="password_nueva">Password Nueva</label>
              <div class="col-md-4">
                <input id="password_nueva" name="password_nueva" type="password" placeholder="" class="form-control input-md" required="">
                
              </div>
            </div>
            
            <!-- Password input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="password_confirmar">Confirme Password</label>
              <div class="col-md-4">
                <input id="password_confirmar" name="password_confirmar" type="password" placeholder="" class="form-control input-md" required="">
                
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