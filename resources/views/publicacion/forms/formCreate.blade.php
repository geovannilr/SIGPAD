	<div class="form-group col-sm-12">
		{!! Form::label('Documento') !!}
		{!!Form::file('documento',['class'=>'form-control documentoPublicacion','accept'=>"pdf/docx/doc"])  !!}
		{{ Form::hidden('publicacion', $publicacion->id_pub) }}
	</div>
</div>
				
				