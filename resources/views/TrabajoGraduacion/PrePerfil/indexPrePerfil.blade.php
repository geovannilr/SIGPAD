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
		 $('.deleteButton').on('submit',function(e){
        if(!confirm('Estas seguro que deseas eliminar este Grupo de trabajo de graduación?')){

              e.preventDefault();
        	}
      	});
		
    	$("#listTable").DataTable({
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                title: 'Listado de Grupos de trabajo de graduación'
            },
            {
                extend: 'pdfHtml5',
                title: 'Listado de Grupos de trabajo de graduación'
            },
             {
                extend: 'csvHtml5',
                title: 'Listado de Grupos de trabajo de graduación'
            },
            {
                extend: 'print',
                title: 'Listado de Grupos de trabajo de graduación'
            }


        ],
         responsive: {
            details: {
                type: 'column'
            }
        },
        order: [ 1, 'asc' ],
    	});
	});
	function borrar(id) {
		var idUsuario=id;
		$("#modalBorrar").modal()
	}
	
	
</script>
		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item ">
	          <h5>Pre-Perfiles</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado de Grupos de Trabajos de Graduación</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('grupo.create')
	  <div class="col-sm-3">
	  	 <a class="btn btn-primary" href="{{route('grupo.create')}}"><i class="fa fa-plus">Nuevo </i></a>
	  </div>
  @endcan
</div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Grupo</th>
					<th>Cantidad de Pre-Perfiles Enviados</th>
					<th>Datalle de Grupo</th>
					<th>Datalle de Pre-Perfiles</th>

  				</thead>
  				<tbody>

  				@foreach($gruposPrePerfil as $grupo)
  						<tr>
						<td>{{ $grupo->grupo->numero_pdg_gru }}</td>
						<td>{{ $grupo->cantidadPrePerfiles }}</td>
						<td>
							 	<a class="btn btn-info" href="#" onclick="getGrupo({{ $grupo->id_pdg_gru }});"><i class="fa fa-eye"></i></a>
						</td>
						<td>
								<a class="btn btn-info" href="{{route('indexPrePerfil', [$grupo->id_pdg_gru])}}"><i class="fa fa-list-alt"></i></a>
							</td>
					</tr>
				@endforeach 
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
      </div>
    </div>
  </div>
</div>
@stop