@extends('template')

@section('content')

		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5> <a href="javasript:void(0)" onclick="calificarEtapa({{$idGrupo}},{{$etapa->id_cat_eta_eva}}); return false;" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Grupo {{$nombreGrupo}}</h5>
	        </li>
	        <li class="breadcrumb-item active">Resultado de carga de Notas para la etapa <b>{{$etapa->nombre_cat_eta_eva}} - {{$etapa->ponderacion_cat_eta_eva}}%</b></li>
		</ol>
		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Carnet</th>
					<th>Nombre</th>
					<th>Nota Etapa</th>
					<th>Estado</th>
					<th>Leyenda</th>
  				</thead>
  				<tbody>
  					{!!$bodyHtml!!}
				</tbody>
			</table>
	   </div>
		{!!Html::script('js/TrabajoGraduacion/etapaEvaluativa.js')!!}
@stop