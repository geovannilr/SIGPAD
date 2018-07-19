	<div class="form-group col-sm-12">
		{!! Form::label('Documento') !!}
		{!!Form::file('documento',['class'=>'form-control documento','accept'=>"pdf/docx/doc"])  !!}
		{{ Form::hidden('etapa', $idEtapa) }}
		{{ Form::hidden('tipoDocumento', $idTipoDoc) }}
	</div>
</div>
				
				