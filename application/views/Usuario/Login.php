<!-- *******Contenido de la página **********************-->

<div class="col-md-12">
    <div class="panel-body">
        <div class="col-sm-10 col-md-12">
            <div class="text-center">
                <h2>Inicio de Sesion</h2>
                <?=form_open('Login/Entrar','');?>
                    <fieldset>
                    <!-- Form Name -->
                    
                    <legend><?=$mensaje?></legend>
                    
                    
                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="usuario">Usuario</label>  
                      <div class="col-md-6">
                      <input id="usuario" name="usuario" type="text" placeholder="" class="form-control input-md" required="">
                        
                      </div>
                    </div>
                    
                    <!-- Password input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="password">Password</label>
                      <div class="col-md-6">
                        <input id="password" name="password" type="password" placeholder="" class="form-control input-md" required="">
                        
                      </div>
                    </div>
                    
                    <!-- SUBMIT -->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="iniciarSesion"></label>
                      <div class="col-md-4">
                        <input type="submit" id="iniciarSesion" name="iniciarSesion" class="btn btn-primary submit" value="Iniciar Sesion"/>
                      </div>
                    </div>
                    
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>