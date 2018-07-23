	<div class="row">
		<div class="form-group col-sm-6">
			{!! Form::label('Documento') !!}
			{!!Form::file('documento',['class'=>'form-control documentoPublicacion','accept'=>"pdf/docx/doc"])  !!}
			{{ Form::hidden('publicacion', $publicacion->id_pub) }}
		</div>
		<div class="form-group col-sm-6">
			{!! Form::label('Descripción') !!}
			{!!Form::textarea('descripcion_pub_arc',null,['class'=>'form-control ','placeholder'=>'Ingrese Descripción del documento']) !!}
		</div>
	</div>

</div>
				
				