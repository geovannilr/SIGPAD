<aside class="right-side strech">
  <?php $formClass=array('class'=>'form-horizontal','role'=>'form','autocomplete'=>'on','id' =>'FrmParamGenerales');?>
  <?php $hidden = array('base_url' => BASE_URL);?>
  <?=form_open('',$formClass,$hidden )?>
  <fieldset>
  <!-- Form Name -->
  <legend>Parametros Generales</legend>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="nombres_director_escuela">Nombres</label>  
    <div class="col-md-5">
    <input id="nombres_director_escuela" name="nombres_director_escuela" type="text" placeholder="Juan Manuel " class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="apellidos_director_escuela">Apellidos</label>  
    <div class="col-md-5">
    <input id="apellidos_director_escuela" name="apellidos_director_escuela" type="text" placeholder="Cabrer Turciosr" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="nombres_coordinador_horas_sociales">Nombres</label>  
    <div class="col-md-4">
    <input id="nombres_coordinador_horas_sociales" name="nombres_coordinador_horas_sociales" type="text" placeholder="Juan Manuel" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="apellidos_coordinador_horas_sociales">Apellidos</label>  
    <div class="col-md-4">
    <input id="apellidos_coordinador_horas_sociales" name="apellidos_coordinador_horas_sociales" type="text" placeholder="Moran Ramirez" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="nombres_coordinador_pera">nombres</label>  
    <div class="col-md-5">
    <input id="nombres_coordinador_pera" name="nombres_coordinador_pera" type="text" placeholder="Salvador Efrain " class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="apellidos_coordinador_pera">Apellidos</label>  
    <div class="col-md-5">
    <input id="apellidos_coordinador_pera" name="apellidos_coordinador_pera" type="text" placeholder="Erazo Hernandez" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="nombres_director_general_pdg">Nombres</label>  
    <div class="col-md-5">
    <input id="nombres_director_general_pdg" name="nombres_director_general_pdg" type="text" placeholder="Juan Maria" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="apellidos_director_general_pdg">Apellidos</label>  
    <div class="col-md-5">
    <input id="apellidos_director_general_pdg" name="apellidos_director_general_pdg" type="text" placeholder="Marroquin de Villegas" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="nombres_administrador_academica_fia">Nombres</label>  
    <div class="col-md-5">
    <input id="nombres_administrador_academica_fia" name="nombres_administrador_academica_fia" type="text" placeholder="Jose Damian" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="apellidos_administrador_academica_fia">Apellidos</label>  
    <div class="col-md-5">
    <input id="apellidos_administrador_academica_fia" name="apellidos_administrador_academica_fia" type="text" placeholder="Hernandez Montesino" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="nombres_secretario_eisi">Nombres</label>  
    <div class="col-md-5">
    <input id="nombres_secretario_eisi" name="nombres_secretario_eisi" type="text" placeholder="Juan Jose" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="apellidos_administrador_academica_fia">Apellidos</label>  
    <div class="col-md-5">
    <input id="apellidos_administrador_academica_fia" name="apellidos_administrador_academica_fia" type="text" placeholder="Montano Villas" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="precio_hora_sercvicio_social">Precio Hora Servicio Social $</label>  
    <div class="col-md-4">
    <input id="precio_hora_sercvicio_social" name="precio_hora_sercvicio_social" type="text" placeholder="4.30" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Button -->
  <div class="form-group">
    <label class="col-md-4 control-label" for="ingresar_parametros_generales"></label>
    <div class="col-md-4">
      <button id="ingresar_parametros_generales" name="ingresar_parametros_generales" class="btn btn-primary">Ingresar</button>
    </div>
  </div>

  <!-- Button -->
  <div class="form-group">
    <label class="col-md-4 control-label" for="limpiar_parametros_generales"></label>
    <div class="col-md-4">
      <button id="limpiar_parametros_generales" name="limpiar_parametros_generales" class="btn btn-primary">Limpiar</button>
    </div>
  </div>

  <!-- Button -->
  <div class="form-group">
    <label class="col-md-4 control-label" for="cancelar_parametros_generales"></label>
    <div class="col-md-4">
      <button id="cancelar_parametros_generales" name="cancelar_parametros_generales" class="btn btn-primary">Cancelar</button>
    </div>
  </div>

  </fieldset>
  <?=form_close()?>
</aside>   