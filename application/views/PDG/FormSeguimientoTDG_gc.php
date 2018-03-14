
<aside class="right-side strech">
  <?php $formClass=array('class'=>'form-horizontal','role'=>'form','autocomplete'=>'on','id' =>'FrmSeguimientoTDG');?>
  <?=form_open('PDG/perfil/create',$formClass); ?>
  <fieldset>

  <!-- Form Name -->
  <legend>Seguimiento Trabajo de Graduación</legend>

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

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="textinput">ACA HIRIA EL RESUMEN 1</label>  
    <div class="col-md-4">
    <input id="textinput" name="textinput" type="text" placeholder="placeholder" class="form-control input-md">
    <span class="help-block">help</span>  
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="textinput">ACA HIRIA EL RESUMEN 2</label>  
    <div class="col-md-4">
    <input id="textinput" name="textinput" type="text" placeholder="placeholder" class="form-control input-md">
    <span class="help-block">help</span>  
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="col-md-4 control-label" for="textinput">ACA HIRIA EL RESUMEN 3</label>  
    <div class="col-md-4">
    <input id="textinput" name="textinput" type="text" placeholder="placeholder" class="form-control input-md">
    <span class="help-block">help</span>  
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
  <?=form_close();?>
</aside>
