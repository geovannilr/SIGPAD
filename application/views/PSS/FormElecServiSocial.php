<?php 
  $attributes = array('class' => 'form-horizontal');
?>

<?=form_open('PSS/ElecServiSocial/Crear',$attributes)?>



<aside class="right-side strech">
    <div class="panel panel-info">
        <div class="panel-heading text-center">
            <b>Servicios Sociales Disponibles para Inscribir</b>
        </div>
        <p> </p>
        <p class="text-danger"><?=$mensaje?></p>
        <p> </p>
        <div class="panel-body table-responsive">
            <table class="table table-condensed " id="tableTipouser">
              <thead> 
                    <tr>
                        <th>Cod. Servicio Social</th>
                        <th>Institución</th>
                        <th>Modalidad</th>
                        <th>Servicio Social</th>
                        <th>Cant. de Estudiantes Requeridos</th>
                        <th>Disponibilidad</th>
                        <th>Descripción</th>
                        <th>Contacto Directo</th>
                        <th>Email Contacto Directo</th>

                    </tr>
                </thead>  
                <tbody>
                    
                    <?php foreach ($candidatos as $row){?>
                    <tr>
                        <td ><?php echo $row->id_servicio_social; ?></td>
                        <td ><?php echo $row->institucion; ?></td>
                        <td ><?php echo $row->modalidad; ?></td>
                        <td ><?php echo $row->nombre_servicio_social; ?></td>
                        <td ><?php echo $row->cantidad_estudiante; ?></td>
                        <td ><?php echo $row->disponibilidad; ?></td>
                        <td ><?php echo $row->descripcion; ?></td>
                        <td ><?php echo $row->nombre_contacto_ss; ?></td>
                        <td ><?php echo $row->email_contacto_ss; ?></td>                                                                        
                        <td ><input type='checkbox' name="id_candidato[]"  value=<?php echo $row->id_servicio_social; ?>></td>
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
        <button id="crear_equipo" name="crear_equipo" class="btn btn-primary">Inscribir Servicio Social</button>
      </div>
    </div>
</aside>   
<?=form_close()?>

