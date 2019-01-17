				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre de Aspecto') !!}
						{!!Form::text('nombre_pdg_asp',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre del aspecto'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Ponderación') !!}
						{!!Form::number('ponderacion_pdg_asp',null,['class'=>'form-control ','min'=>0,'max'=>100,'placeholder'=>'Ingrese ponderación del aspecto'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Etapa Evaluativa') !!}
						{{ Form::select('id_cat_eta_eva',$catEtaEva, null, ['class' => 'form-control']) }}
					</div>
				</div>