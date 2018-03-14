
<aside class="right-side strech">
  <?php $formClass=array('class'=>'form-horizontal','role'=>'form','autocomplete'=>'on','id' =>'FrmCalificaciones');?>
  <?=form_open('PDG/perfil/create',$formClass); ?>
  <fieldset>

  <!-- Form Name -->
  <legend>Calificaciones</legend>

  <!-- Multiple Radios (inline) -->
  <div class="form-group">
    <label class="col-md-4 control-label" for="ciclo">Ciclo</label>
    <div class="col-md-4"> 
      <label class="radio-inline" for="ciclo-0">
        <input type="radio" name="ciclo" id="ciclo-0" value="1" checked="checked">
        1
      </label> 
      <label class="radio-inline" for="ciclo-1">
        <input type="radio" name="ciclo" id="ciclo-1" value="2">
        2
      </label>
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="anio">Año</label>  
    <div class="col-md-2">
    <input id="anio" name="anio" type="text" placeholder="" class="form-control input-md" required="">
      
    </div>
  </div>

  <!-- Select Basic -->
  <div class="form-group">
    <label class="col-md-1 control-label" for="selectbasic">Equipo</label>
    <div class="col-md-1">
      <select id="selectbasic_equipo" name="selectbasic_equipo" class="form-control">
        <?php foreach($equipo as $row):?>
        <option value="1"><?php echo $row['id_equipo_tg'];?></option>
        <?php endforeach;?>
      </select>
    </div>
  </div>

  <!-- Textarea -->
  <div class="form-group">
    <label class="col-md-4 control-label" for="tema">Tema</label>
    <div class="col-md-4">                     
      <textarea class="form-control" id="tema" name="tema"></textarea>
    </div>
  </div>

  <!-- Select Basic -->
  <div class="form-group">
    <label class="col-md-4 control-label" for="fase_evaluativa">Fase Evaluativa</label>
    <div class="col-md-4">
      <select id="fase_evaluativa" name="fase_evaluativa" class="form-control">
        <option value="1">Option one</option>
        <option value="2">Option two</option>
      </select>
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="textinput">ACA IRIA UNA TABLA DE CONSULTA/INSERCION DE NOTAS</label>  
    <div class="col-md-4">
    <input id="textinput" name="textinput" type="text" placeholder="placeholder" class="form-control input-md">
    <span class="help-block">help</span>  
    </div>
  </div>

  <!-- Button -->
  <div class="form-group">
    <label class="col-md-4 control-label" for="guardar"></label>
    <div class="col-md-4">
      <button id="guardar" name="guardar" class="btn btn-primary">Guardar</button>
    </div>
  </div>

  <!-- Button -->
  <div class="form-group">
    <label class="col-md-4 control-label" for="cancelar"></label>
    <div class="col-md-4">
      <button id="cancelar" name="cancelar" class="btn btn-danger">Cancelar</button>
    </div>
  </div>

  </fieldset>
  </form>

  <?=form_close();?>
</aside>