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
        language: {
                url: 'es-ar.json' //Ubicacion del archivo con el json del idioma.
        },
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2]
                },
                title: 'Listado de Grupos de trabajo de graduación'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2]
                },
                title: 'Listado de Grupos de trabajo de graduación'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2]
                },
                title: 'Listado de Grupos de trabajo de graduación'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1, 2]
                },
                title: 'Listado de Grupos de trabajo de graduación'
            }


        ],
         responsive: {
            details: {
                type: 'column'
            }
        },
        order: [ 2, 'asc' ],
    	});
	});
	
	
</script>
		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item ">
	          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Grupos de trabajo de graduación</h5>
	        </li>
	        <li class="breadcrumb-item active">Mis Grupos</li>
		</ol>
	
		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Número</th>
					<th>Líder</th>
					<th>Cantidad de Estudiantes</th>
          <th>Estado</th>
					<th>Dashboard</th>
  				</thead>
  				<tbody>

  				@foreach($grupos as $grupo)
  						<tr>
						<td>{{ $grupo->numeroGrupo }}</td>
						<td>
							 	{{ $grupo->Lider }}
						</td>
						<td>{{$grupo->Cant}}</td>
            @if( $grupo->estado == 1)
               <td><span class="badge badge-success">Proceso TDG Iniciado</span></td>
               @else
                 <td><span class="badge badge-info">Proceso TDG Sin Iniciar</span></td>
            @endif
						<td style="text-align: center;">
              @if( $grupo->estado == 1)
                <a class="btn btn-dark" href="{{route('dashboardGrupo',$grupo->ID)}}" ><i class="fa fa-eye"></i></a>  
            @endif
						</td>
					</tr>
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop