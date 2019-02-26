				<div class="row">
					<div class="form-group col-sm-6">
						{!! Form::label('Nombre Etapa Evaluativa') !!}
						{!!Form::text('nombre_cat_eta_eva',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre de etapa evaluativa'])  !!}
					</div>
					<div class="form-group col-sm-6">
						{!! Form::label('Ponderación Etapa Evaluativa') !!}
						{!!Form::number('ponderacion_cat_eta_eva',null,['class'=>'form-control ','min'=>0,'max'=>100,'placeholder'=>'Ingrese Ponderación de etapa evaluativa'])  !!}
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-6">
						{!! Form::label('Año Etapa Evaluativa') !!}
						{!!Form::number('anio_cat_eta_eva',null,['class'=>'form-control ','min'=>2017,'max'=>2040,'placeholder'=>'Año de la etapa evaluativa'])  !!}
					</div>
					<div class="form-group col-sm-6">
						{!! Form::label('¿Nota Grupal?') !!}
						{!! Form::select('notagrupal_cat_eta_eva',[1=>"SI",0=>"NO"],null,['class'=>'form-control','id'=>'notagrupal_cat_eta_eva']) !!}
					</div>
				</div>