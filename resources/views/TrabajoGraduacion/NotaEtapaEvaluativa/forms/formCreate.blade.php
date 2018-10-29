	<div class="form-group col-sm-12">
		{!! Form::label('Documento en formato excel.') !!}
		{!!Form::file('documentoNotas',['class'=>'form-control documentoNotas','accept'=>"xlsx"])  !!}
		{{ Form::hidden('etapa', $etapa->id_cat_eta_eva) }}
	</div>
</div>
				
				