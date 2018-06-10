				<div class="row">
					<div class="form-group col-sm-6">
					
						{!! Form::label('Tema') !!}
						{!!Form::text('tema_pdg_ppe',null,['class'=>'form-control','placeholder'=>'Ingrese el tema del Pre-Perfil'])  !!}
					</div>
					<div class="form-group col-sm-6">
					
						{!! Form::label('Tipo de Trabajo de Graduación') !!}
						{!! Form::select('id_cat_tpo_tra_gra', $tiposTrabajos,null,['class'=>'form-control','id'=>'tipoTrabajo']) !!}
					</div>
					
				</div>
				<div class="row">
					<div class="form-group col-sm-12">
					
						{!! Form::label('Documento') !!}
						{!!Form::file('documento',['class'=>'form-control documento','accept'=>"pdf/docx/doc"])  !!}

					</div>
				</div>
				
				