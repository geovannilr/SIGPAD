				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre tipo de publicación') !!}
						{!!Form::text('nombre_cat_tpo_pub',null,['class'=>'form-control ','placeholder'=>'Introduzca el nombre'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Descripción tipo de publicación') !!}
						{!!Form::textArea('descripcion_cat_tpo_pub',null,['class'=>'form-control ','placeholder'=>'Introduzca descripción'])  !!}
					</div>
				</div>
