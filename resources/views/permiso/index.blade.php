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
        if(!confirm('Estas seguro que deseas eliminar este Permiso?')){

              e.preventDefault();
        	}
      	});
		
    	$("#listTable").DataTable({
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                title: 'Listado de Permisos'
            },
            {
                extend: 'pdfHtml5',
                title: 'Listado de Permisos'
            },
             {
                extend: 'csvHtml5',
                title: 'Listado de Permisos'
            },
            {
                extend: 'print',
                title: 'Listado de Permisos'
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
		<ol class="breadcrumb"  style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5>Permisos</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('permiso.create')
    <div class="col-sm-3">
      Nuevo  <a class="btn btn-primary" href="{{route('permiso.create')}}"><i class="fa fa-plus"></i></a>
    </div>
  @endcan
  </div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Nombre</th>
          <th>Slug</th>
					<th>Descipción</th>
					<th>Fecha de Registro</th>
					@can('permiso.edit')
            <th>Modificar</th>
          @endcan 
          @can('permiso.destroy') 
					   <th>Eliminar</th>
          @endcan
  				</thead>
  				<tbody>
  				@foreach($permisos as $permiso)
					<tr>
						<td>{{ $permiso->name }}</td>
            <td>{{ $permiso->slug }}</td>
						<td>{{ $permiso->description }}</td>
						<td>{{$permiso->created_at->format('d/m/Y H:i:s')}}</td>
						@can('permiso.edit')
              <td>
  							<a class="btn btn-primary" href="{{route('permiso.edit',$permiso->id)}}"><i class="fa fa-pencil"></i></a>
  						</td>
            @endcan
            @can('permiso.destroy')
  						<td>
  							{!! Form::open(['route'=>['permiso.destroy',$permiso->id],'method'=>'DELETE','class' => 'deleteButton']) !!}
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