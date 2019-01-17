				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre Tipo Documento') !!}
						{!!Form::text('nombre_pdg_tpo_doc',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre de tipo Documento'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Año Tipo Documento') !!}
						{!!Form::number('anio_cat_pdg_tpo_doc',null,['class'=>'form-control ','min'=>2017,'max'=>2040,'placeholder'=>'Ingrese Año de tipo documento'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Descripcion Tipo Documento') !!}
						{!!Form::textarea('descripcion_pdg_tpo_doc',null,['class'=>'form-control ','placeholder'=>'Ingrese Descripción de tipo documento'])  !!}
					</div>
				</div>