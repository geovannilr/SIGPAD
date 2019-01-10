				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Estado') !!}
						{!!Form::text('nombre_cat_sta',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre del estado'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Descripción') !!}
						{!!Form::textarea('descripcion_cat_sta',null,['class'=>'form-control ','placeholder'=>'Ingrese descripción del estado'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Tipo estado') !!}
						{{ Form::select('id_cat_tpo_sta', $tpoSta, null, ['class' => 'form-control']) }}
					</div>
				</div>