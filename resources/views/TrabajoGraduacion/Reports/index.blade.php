@extends('template')

@section('content')
@if(Session::has('message'))
  		<script type="text/javascript">
  			$( document ).ready(function() {
  				@if(Session::has('tipo') == 'error')
  				 	swal("", "{{Session::get('message')}}", "error");
  				@else
  					swal("", "{{Session::get('message')}}", "success");
  				@endif
			});
  		</script>		
@endif
<script type="text/javascript">
	$( document ).ready(function() {
    	$("#listTable").DataTable({
         responsive: {
            details: {
                type: 'column'
            }
        },
		info : false,
		bLengthChange: false,
    	});
	});

</script>
		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item ">
	          <h5> <a href="{{ route('reportesTDG') }}" style="margin-left: 0em">
					  <i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i>
				  </a>Trabajo de Graduación</h5>
	        </li>
	        <li class="breadcrumb-item active">Reportes</li>
		</ol>
		 <div class="row">
			 <div class="col-sm-3"></div>
			 <div class="col-sm-3"></div>
			 <div class="col-sm-3"></div>
		 </div>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">
  				<thead>
					<th>Nombre</th>
					<th>Descripción</th>
  				</thead>
  				<tbody>
  				<tr>
  					<td>
						<a href="{{route('reportes/createTribunalPorGrupo')}}">Tribunal Por Grupo</a>
					</td>
  					<td>
  						Presenta el listado de grupos y su  tribunal evaluador.
  					</td>
				</tr>
				<tr>
					<td>
						<a href="{{route('reportes/createAsignacionesPorDocente')}}">Asignaciones Por Docente</a>
					</td>
					<td>
						Presenta el listado docentes y sus asignaciones.
					</td>
				</tr>
				<tr>
					<td>
						<a href="{{route('reportes/createEstadoGruposEtapa')}}">Estado de Grupos Por Etapa</a>
					</td>
					<td>
						Presenta el listado de grupos en Trabajo de Graduación.
					</td>
				</tr>
				<tr>
					<td>
						<a href="{{route('reportes/createDetalleGruposTdg')}}">Detalle de Grupos de Trabajo de Graduación</a>
					</td>
					<td>
						Presenta el detalle de Grupos de Trabajo de Graduación.
					</td>
  				</tr>
				<tr>
					<td>
						<a href="{{route('reportes/createEstudiantesTdg')}}">Estudiantes en Trabajo de Graduación</a>
					</td>
					<td>
						Presenta el listado de Estudiante Activos en Trabajo de Graduación.
					</td>
				</tr>
				<tr>
					<td>
						<a href="{{route('reportes/createConsolidadoNotas')}}">Consolidado de Notas</a>
					</td>
					<td>
						Presenta el consolidado de notas para un Grupo de Trabajo de Graduación seleccionado.
					</td>
				</tr>
				<tr>
					<td>
						<a href="{{route('reportes/createDocentesCategorias')}}">Docentes por Categorías de trabajo de graduación</a>
					</td>
					<td>
						Presenta el consolidado de notas para un Grupo de Trabajo de Graduación seleccionado.
					</td>
				</tr>
				</tbody>
			</table>
	   </div>
	  
<!-- Modal Detalle de grupo -->
<div class="modal fade" id="detalleGrupo" tabindex="-1" role="dialog" aria-labelledby="Detalle grupo de trabajo de graduación" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Detalle de grupo de trabajo de graduación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="modalDetalleBody" class="modal-body">
        ...
      </div>
      <div class="modal-footer" id="footerModal">
      	{!! Form::open(['route'=>['aprobarGrupo'],'method'=>'POST']) !!}
			<div class="btn-group" id="divBoton">
				
			</div>
		{!! Form:: close() !!}
	  <!-- EJRG begin -->
		{!! Form::open(['route'=>['verGrupo'],'method'=>'POST']) !!}
		  <div class="btn-group" id="divBtnEditarGrupo">

		  </div>
	  	{!! Form:: close() !!}
	  <!-- EJRG end-->
      </div>
    </div>
  </div>
</div>
@stop