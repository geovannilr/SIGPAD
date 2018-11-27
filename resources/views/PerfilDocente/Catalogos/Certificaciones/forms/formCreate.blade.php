				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre') !!}
						{!!Form::text('name',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre de Permiso'])  !!}
					</div>
					<div class="form-group col-sm-6">
						{!! Form::label('Slug') !!}
						{!!Form::text('slug',null,['class'=>'form-control ','placeholder'=>'Ej: nombre.operación'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Descripción') !!}
						{!!Form::textarea('description',null,['class'=>'form-control ','placeholder'=>'Ingrese Descripción del Permiso'])  !!}
					</div>
				</div>
				
				

				