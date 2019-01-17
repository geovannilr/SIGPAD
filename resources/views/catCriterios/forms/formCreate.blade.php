				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre de Criterio') !!}
						{!!Form::text('nombre_cat_cri_eva',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre del Criterio'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Ponderación') !!}
						{!!Form::number('ponderacion_cat_cri_eva',null,['class'=>'form-control ','min'=>0,'max'=>100,'placeholder'=>'Ingrese ponderación del criterio'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Aspectos') !!}
						{{ Form::select('id_pdg_asp',$catAspEva, null, ['class' => 'form-control']) }}
					</div>
				</div>