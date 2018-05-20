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
		 $('.deleteButton').on('submit',function(e){
        if(!confirm('Estas seguro que deseas eliminar este Usuario?')){

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
		<ol class="breadcrumb">
	        <li class="breadcrumb-item ">
	          <h5>Grupos de trabajo de graduación</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('grupotdg.create')
	  <div class="col-sm-3">Nuevo 
	  	 <a class="btn btn-primary" href="{{route('grupo.create')}}"><i class="fa fa-plus"></i></a>
	  </div>
  @endcan
</div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Líder</th>
					<th>Estado</th>
					<th>Cantidad</th>
					@can('usuario.edit')
						<th>Modificar</th>
					@endcan
					@can('usuario.destroy')
						<th>Eliminar</th>
					@endcan
  				</thead>
  				<tbody>

  				@foreach($grupos as $grupo)
  						<tr>
						<td>{{ $grupo->Lider }}</td>
						<td>{{ $grupo->Estado }}</td>
						<td>{{$grupo->Cant}}</td>
						@can('usuario.edit')
							<td>
								<a class="btn btn-primary" href="{{route('usuario.edit',$usuario->id)}}"><i class="fa fa-pencil"></i></a>
							</td>
						@endcan
						@can('usuario.destroy')
							<td>
								{!! Form::open(['route'=>['usuario.destroy',$usuario->id],'method'=>'DELETE','class' => 'deleteButton']) !!}
							 		<div class="btn-group">
										<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
						@endcan
					</tr>
				@endforeach 
				</tbody>
			</table>
	   </div>
	   <!-- Modal Confirmar Borrar-->
		<div class="modal fade" id="modalBorrar" tabindex="-1" role="dialog" aria-labelledby="modalBorrar" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Estas seguro?</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        El usuario se eliminará permanentemente del Sistema.
		      </div>
		      <div class="modal-footer">
		        <button type="button"   class="btn btn-secondary" data-dismiss="modal">No</button>
		        <button type="button"   class="btn btn-primary">Sí</button>
		      </div>
		    </div>
		  </div>
		</div>	
@stop