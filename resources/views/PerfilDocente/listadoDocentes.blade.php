@extends('template')

@section('content')
@if(Session::has('message'))
  		<script type="text/javascript">
  			$( document ).ready(function() {
    			swal("", "{{Session::get('message')}}", "success");
			});
  		</script>		
@endif
<script type="text/javascript">
	$( document ).ready(function() {	
    	$("#listTable").DataTable({
        language: {
                url: 'es-ar.json' //Ubicacion del archivo con el json del idioma.
        },
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5]
                },
                title: 'Listado de Docentes Registrados'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5]
                },
                title: 'Listado de Docentes Registrados'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5]
                },
                title: 'Listado de Docentes Registrados'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5]
                },
                title: 'Listado de Docentes Registrados'
            }


        ],
         responsive: {
            details: {
                type: 'column'
            }
        },
        order: [ 0, 'asc' ],
    	});
	});
	
</script>
		<ol class="breadcrumb"  style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Docentes</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado de Docentes</li>
		</ol>
		<br>
     <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
         @can('gestionDocente.cargar')
    <div class="col-sm-3">
       <a class="btn " href="{{route('cargarActualizacionDocente')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus">Cargar Actualización de Docentes </i></a>
    </div>
         @endcan
</div> 
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
          <th>Nombre</th>
					<th>Usuario</th>
          <th>Tipo Jornada</th>
          <th>Cargo Principal</th>
          <th>Cargo Secundario</th>
          @can('gestionDocente.edit')
          <th>Modificar</th>
          @endcan
  				</thead>
  				<tbody>
  				@foreach($docentes as $docente)
					<tr>
						<td>{{ $docente->display_name }}</td>
            <td>{{ $docente->carnet_pdg_dcn }}</td>
            @if($docente->tipoJornada == 1)
              <td>Tiempo Completo</td>
            @else
              @if($docente->tipoJornada == 2 )
                <td>Tiempo Parcial</td>
              @else
                <td>Servicio Profesional</td>
              @endif 
            @endif
						
            <td><span class="badge badge-danger">{{ $docente->cargoPrincipal->nombre_cargo }}</span></td>
            @if(empty($docente->cargoSecundario->nombre_cargo))
               <td>NO SE HA INGRESADO</td>
            @else
               <td><span class="badge badge-info">{{ $docente->cargoSecundario->nombre_cargo }}</span></td>
            @endif
                        @can('gestionDocente.edit')
            <td style="text-align: center;">
                <a class="btn " style="background-color:  #102359;color: white" href="{{route('docenteEdit',$docente->id_pdg_dcn)}}"><i class="fa fa-pencil"></i></a>
              </td>
                        @endcan
					</tr>
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop