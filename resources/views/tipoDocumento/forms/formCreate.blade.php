				<script type="text/javascript">
					var trabajos = <?php echo  json_encode($trabajosGraduacion); ?>;
					var etapas = <?php echo  json_encode($etapasEvaluativas); ?>;
					var trabajoSelect = <?php echo  $idTrabajo ?>;
					var etapaSelect = <?php echo  $idEtapa ?>;
					$( document ).ready(function() {
						if (parseInt(trabajoSelect)==0 && parseInt(etapaSelect)==0) { //CREATE
							getEtapas(trabajos[0].id_cat_tpo_tra_gra,etapaSelect);
						}else{
							getEtapas(trabajoSelect,etapaSelect); //EDIT
						}
    					
    					$('#tipoTrabajo').change(function() {
    						$('#etapa').empty();
  							getEtapas($(this).val(),0);
						});
					});
					function getEtapas(idTipoTrabajo,idEtapa){
						for (var i = 0; i < etapas.length ; i++) {
							if (parseInt(idTipoTrabajo) == parseInt(etapas[i].id_cat_tpo_tra_gra)) {
								if (parseInt(idEtapa) == parseInt(etapas[i].id_cat_eta_eva)) {
									$('#etapa').append('<option value="'+etapas[i].id_cat_eta_eva+'" selected="selected">'+etapas[i].nombre_cat_eta_eva+'</option>');
								}else{
									$('#etapa').append('<option value="'+etapas[i].id_cat_eta_eva+'">'+etapas[i].nombre_cat_eta_eva+'</option>');
								}
								
							}
						}
					}
				</script>
				<div class="row">
					<div class="form-group col-sm-6">
						{!! Form::label('Tipo Trabajo de Graduación') !!}
						{!! Form::select('tipoTrabajo', $trabajos,null,['class'=>'form-control','id'=>'tipoTrabajo']) !!}
					</div>
					<div class="form-group col-sm-6">
						{!! Form::label('Etapa Evaluativa') !!}
						<select name="etapa" id="etapa" class="form-control">
							
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre Tipo Documento') !!}
						{!!Form::text('nombre_pdg_tpo_doc',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre de tipo documento'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Año Tipo Documento') !!}
						{!!Form::number('anio_cat_pdg_tpo_doc',null,['class'=>'form-control ','min'=>2017,'max'=>2040,'placeholder'=>'Ingrese año de tipo documento'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Descripcion Tipo Documento') !!}
						{!!Form::textarea('descripcion_pdg_tpo_doc',null,['class'=>'form-control ','placeholder'=>'Ingrese descripción de tipo documento'])  !!}
					</div>
				</div>
				