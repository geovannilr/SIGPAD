@extends('template')

@section('content')

		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     UESPLAY</h5>
	        </li>
	        <li class="breadcrumb-item active">Resultado de Actualización de Docentes</li>
		</ol>
		<br>
		<div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
	  <div class="col-sm-3">
	  	 <a class="btn " href="{{route('listadoDocentes')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-list">Listado Docentes </i></a>
	  </div>
</div> 
<br>
<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Usuario</th>
					<th>Nombre</th>
					<th>Estado</th>
					<th>Leyenda</th>
  				</thead>
  				<tbody>
  					{!!$bodyHtml!!}
				</tbody>
			</table>
	   </div>

@stop