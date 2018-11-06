	<div class="form-group col-sm-12">
		{!! Form::label('Resumen') !!}
		{!! Form::textarea('resumen', null, ['id' => 'resumen','class'=>'form-control' ,'rows' => 12, 'cols' => 12, 'style' => 'resize:none']) !!}
	</div>
	<div class="form-group col-sm-12">
		{!! Form::label('Tomo Final') !!}
		{!!Form::file('tomoFinal',['class'=>'form-control documentoNotas','accept'=>"xlsx"])  !!}
	</div>
	


