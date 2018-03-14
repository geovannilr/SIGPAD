

<?php 
  $attributes = array('class' => 'form-horizontal');
?>

<?=form_open('PDG/ConfEquiTg/Crear',$attributes)?>

<aside class="right-side strech">
    <p> </p>
    <p class="text-danger"><?=$mensaje?></p>
    <p> </p>    
    <div class="panel panel-info">
        <div class="panel-heading text-center">
            <b>Candidatos para Creación de Equipo de TG</b>
        </div>
  
        <div class="panel-body table-responsive">
            <table class="table table-condensed " id="tableTipouser">
              <thead> 
                    <tr>
                        <th>Id DUE</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Seleccionar</th>
                    </tr>
                </thead>  
                <tbody>
                    
                    <?php foreach ($candidatos as $row){?>
                    <tr>
                        <td ><?php echo $row->id_due; ?></td>
                        <td ><?php echo $row->nombre; ?></td>
                        <td ><?php echo $row->apellido; ?></td>
                        <td ><input type='checkbox' name="id_candidato[]"  value=<?php echo $row->id_due; ?>></td>
                    </tr> 
                <?php 
                } 
                ?>
                </tbody>
            </table>                            
        </div>
    </div>
    <!-- Button -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="crear_equipo"></label>
      <div class="col-md-4">
        <button id="crear_equipo" name="crear_equipo" class="btn btn-primary">Crear Equipo de TG</button>
      </div>
    </div>
</aside>   
<?=form_close()?>

