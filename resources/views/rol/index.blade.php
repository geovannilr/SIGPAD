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
        if(!confirm('Estas seguro que deseas eliminar este Rol?')){

              e.preventDefault();
        	}
      	});
		
    	$("#listTable").DataTable({
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                title: 'Listado de Roles'
            },
            {
                extend: 'pdfHtml5',
                title: 'Listado de Roles'
            },
             {
                extend: 'csvHtml5',
                title: 'Listado de Roles'
            },
            {
                extend: 'print',
                title: 'Listado de Roles'
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
	
</script>
		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Roles</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
   @can('rol.create')
    <div class="col-sm-3">
        <a class="btn " href="{{route('rol.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo Rol</a>
    </div>
  @endcan
</div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Nombre</th>
					<th>Descipción</th>
					<th>Fecha de Registro</th>
					@can('rol.edit')
            <th>Modificar</th>
          @endcan
          @can('rol.destroy')
					 <th>Eliminar</th>
          @endcan
  				</thead>
  				<tbody>
  				@foreach($roles as $rol)
					<tr>
						<td>{{ $rol->name }}</td>
						<td>{{ $rol->description }}</td>
						<td>{{$rol->created_at->format('d/m/Y H:i:s')}}</td>
						@can('rol.edit')
                <td>
    							<a class="btn btn-primary" href="{{route('rol.edit',$rol->id)}}"><i class="fa fa-pencil"></i></a>
    						</td>
            @endcan
            @can('rol.destroy')
						<td>
							{!! Form::open(['route'=>['rol.destroy',$rol->id],'method'=>'DELETE','class' => 'deleteButton']) !!}
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
@stop