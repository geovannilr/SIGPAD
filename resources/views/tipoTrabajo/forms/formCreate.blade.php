				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre de Tipo Trabajo de Graduación') !!}
						{!!Form::text('nombre_cat_tpo_tra_gra',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre TDG'])  !!}
					</div>
					<div class="form-group col-sm-6">
						{!! Form::label('Año de Tipo Trabajo de Graduación') !!}
						{!!Form::number('anio_cat_tpo_tra_gra',null,['class'=>'form-control ','min'=>2017,'max'=>2040,'placeholder'=>'Año TDG'])  !!}
					</div>
				</div>