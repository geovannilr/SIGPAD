				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre') !!}
						{!!Form::text('nombre_cat_ctg_tra',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre de Categoría de TDG'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Tipos de Habilidades') !!}
					  	@if (isset($select))
  							{!! $select !!}
						@else
  							{!! Form::select('tipoSkill[]', $tiposSkill,null,['class'=>'form-control searchable', 'multiple'=>'multiple','id'=>'tiposSkill']) !!}
						@endif
					  	
					</div>
				</div>
				
				

				