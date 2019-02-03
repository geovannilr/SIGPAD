				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre') !!}
						{!!Form::text('nombre_gen_par',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre del estado'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Valor parámetro') !!}
						{!!Form::text('valor_gen_par',null,['class'=>'form-control ','min'=>1,'placeholder'=>'Ingrese un valor del parámetro'])  !!}
					</div>

					<div class="form-group col-sm-4">
						{!! Form::label('Tipo parametro') !!}
						{{ Form::select('id_gen_tpo_par', $tpoParametro, null, ['class' => 'form-control']) }}
					</div>
				</div>