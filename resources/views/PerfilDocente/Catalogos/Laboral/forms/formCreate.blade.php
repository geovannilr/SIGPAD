				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Lugar de Trabajo') !!}
						{!!Form::text('anio',null,['class'=>'form-control ','placeholder'=>'Nombre de Lugar'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Año de Inicio') !!}
						{!!Form::text('anio',null,['class'=>'form-control ','placeholder'=>'####','id'=>'from','readonly'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Año de Fin') !!}
						{!!Form::text('anio',null,['class'=>'form-control ','placeholder'=>'####','id'=>'to','readonly'])  !!}
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Idioma') !!}
						{{ Form::select('id_cat_mat', $idiomas, null, ['class' => 'form-control']) }}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Descripción (Opcional)') !!}
						{!!Form::textarea('descripcion_adicional',null,['class'=>'form-control ','placeholder'=>'Ingrese Descripción'])  !!}
					</div>
				</div>
				
				

				