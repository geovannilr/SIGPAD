				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre Etapa Evaluativa') !!}
						{!!Form::text('nombre_cat_eta_eva',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre de etapa evaluativa'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Ponderación E_E') !!}
						{!!Form::text('ponderacion_cat_eta_eva',null,['class'=>'form-control ','placeholder'=>'Ingrese Ponderación de etapa evaluativa'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Defensas Etapa Evaluativa') !!}
						{!!Form::text('tiene_defensas_cat_eta_eva',null,['class'=>'form-control ','placeholder'=>'Tiene defensas de etapa evaluativa?'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Año etapa Evaluativa') !!}
						{!!Form::text('anio_cat_eta_eva',null,['class'=>'form-control ','placeholder'=>'Año de la etapa evaluativa'])  !!}
					</div>
					<div class="form-group col-sm-6">
						{!! Form::label('Puede Observar E_E') !!}
						{!!Form::text('puede_observar_cat_eta_eva',null,['class'=>'form-control ','placeholder'=>'Puede observar etapa evaluativa?'])  !!}
					</div>
				</div>