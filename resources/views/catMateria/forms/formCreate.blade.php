				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Código de materia') !!}
						{!!Form::text('codigo_mat',null,['class'=>'form-control ','placeholder'=>'Ingrese el código de la materia'])  !!}
					</div>
					<div class="form-group col-sm-6">
						{!! Form::label('Ingrese el nombre de la materia') !!}
						{!!Form::text('nombre_mat',null,['class'=>'form-control ','placeholder'=>'Ingrese el nombre de la materia'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Año Pensum') !!}
						{!!Form::text('anio_pensum',null,['class'=>'form-control ','placeholder'=>'Ingrese Año del Pensum'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Ciclo') !!}
						{!!Form::text('ciclo',null,['class'=>'form-control ','placeholder'=>'Ingrese ciclo de materia'])  !!}
					</div>
				</div>