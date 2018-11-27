				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Materia') !!}
						{{ Form::select('id_cat_mat', $materias, null, ['class' => 'form-control']) }}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Cargo') !!}
						{{ Form::select('id_cat_car', $cargos, null, ['class' => 'form-control']) }}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Año') !!}
						{!!Form::text('anio',null,['class'=>'form-control ','placeholder'=>'####','id'=>'datepicker','readonly'])  !!}
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Descripción (Opcional)') !!}
						{!!Form::textarea('descripcion_adicional',null,['class'=>'form-control ','placeholder'=>'Ingrese Descripción'])  !!}
					</div>
				</div>
				
				

				