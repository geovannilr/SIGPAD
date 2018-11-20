				<div class="row">
					<div class="form-group col-sm-4">
						{!! Form::label('Nombre Tipo Documento') !!}
						{!!Form::text('nombre_pdg_tpo_doc',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre de tipo Documento'])  !!}
					</div>
					<div class="form-group col-sm-6">
						{!! Form::label('Puede Observar T_D') !!}
						{!!Form::text('puede_observar_cat_pdg_tpo_doc',null,['class'=>'form-control ','placeholder'=>'Puede observar categoria de tipo doc?'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Año T_D') !!}
						{!!Form::text('anio_cat_pdg_tpo_doc',null,['class'=>'form-control ','placeholder'=>'Ingrese Año de tipo documento'])  !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('Descripcion T_D') !!}
						{!!Form::textarea('descripcion_pdg_tpo_doc',null,['class'=>'form-control ','placeholder'=>'Ingrese Descripción de tipo documento'])  !!}
					</div>
				</div>