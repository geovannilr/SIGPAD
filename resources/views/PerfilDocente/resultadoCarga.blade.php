@extends('template')

@section('content')

		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Perfil Docente</h5>
	        </li>
	        <li class="breadcrumb-item active">Resultado de carga de registros de Perfil Docente</li>
		</ol>
		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Apartado</th>
					<th>Estado</th>
					<th>Leyenda</th>
  				</thead>
  				<tbody>
  					{!!$bodyHtml!!}
				</tbody>
			</table>
	   </div>

@stop