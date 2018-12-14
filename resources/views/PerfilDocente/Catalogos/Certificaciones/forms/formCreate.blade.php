				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre de Certificacion') !!}
						{!!Form::text('nombre_dcn_cer',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre de certificacion'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Año de Expedicion') !!}
						{!!Form::text('anio_expedicion_dcn_cer',null,['class'=>'form-control ','placeholder'=>'####','id'=>'from','readonly'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Institucion') !!}
						{!!Form::text('institucion_dcn_cer',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre de institucion'])  !!}
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Idioma') !!}
						{{ Form::select('id_cat_idi', $idiomas, null, ['class' => 'form-control']) }}
					</div>
				</div>
				
				

				