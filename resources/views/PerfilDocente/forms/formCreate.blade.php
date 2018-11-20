	<div class="row">
		<div class="form-group col-sm-12">
		{!! Form::label('Perfil Docente en formato Excel') !!}
		{!!Form::file('documentoPerfil',['class'=>'form-control documentoNotas','accept'=>"xlsx"])  !!}
		</div>
	
	</div>
	<br>
	<div class="row">
		<div class="col-sm-4"></div>
		<div class="col-sm-4">
			<p class="text-danger text-center"> NOTA: Al marcar la casilla "Perfil público" usted acepta que la información cargada será visible  para todas las personas que acceden al sitio oficial de la Escuela de Ingeniería de Sistemas informáticos (EISI).<br>
				{!! Form::label('PERFIL PUBLICO',null,['class'=>'text-secondary']) !!}
				{!!Form::checkbox('perfilPrivado',1,['class'=>'form-control'])  !!}
	 </p>
		</div>
		<div class="col-sm-4"></div>
	</div>
	<div class="row">
		<div class="col-sm-4"></div>
		<div class="form-group col-sm-4">
		
		</div>
		<div class="col-sm-4"></div>
	</div>
	
</div>

