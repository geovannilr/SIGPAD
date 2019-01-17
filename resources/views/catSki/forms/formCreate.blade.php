				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre Skill') !!}
						{!!Form::text('nombre_cat_ski',null,['class'=>'form-control ','placeholder'=>'Escriba nombre de Skill'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Tipo Skill') !!}
						{{ Form::select('id_tpo_ski', $tpoSki, null, ['class' => 'form-control']) }}
					</div>
				</div>