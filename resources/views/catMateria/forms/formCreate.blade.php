				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Código ') !!}
						{!!Form::text('codigo_mat',null,['class'=>'form-control ','placeholder'=>'Ingrese el código de la materia'])  !!}
					</div>
					<div class="form-group col-sm-6">
						{!! Form::label('Nombre') !!}
						{!!Form::text('nombre_mat',null,['class'=>'form-control ','placeholder'=>'Ingrese el nombre de la materia'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Año de pensum') !!}
						{!!Form::number('anio_pensum',null,['class'=>'form-control ','min'=>2017,'max'=>2040,'placeholder'=>'Ingrese Año del Pensum'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Ciclo') !!}
						{!!Form::number('ciclo',null,['class'=>'form-control ','min'=>1,'max'=>2,'placeholder'=>'Ingrese ciclo de materia'])  !!}
					</div>
				</div>