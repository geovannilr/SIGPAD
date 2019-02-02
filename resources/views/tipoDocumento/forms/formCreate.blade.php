				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre Tipo Documento') !!}
						{!!Form::text('nombre_pdg_tpo_doc',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre de tipo documento'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Año Tipo Documento') !!}
						{!!Form::number('anio_cat_pdg_tpo_doc',null,['class'=>'form-control ','min'=>2017,'max'=>2040,'placeholder'=>'Ingrese año de tipo documento'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Descripcion Tipo Documento') !!}
						{!!Form::textarea('descripcion_pdg_tpo_doc',null,['class'=>'form-control ','placeholder'=>'Ingrese descripción de tipo documento'])  !!}
					</div>
				</div>