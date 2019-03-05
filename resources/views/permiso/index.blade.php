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
        language: {
                url: 'es-ar.json' //Ubicacion del archivo con el json del idioma.
        },
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de Permisos'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de Permisos'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de Permisos'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
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
	          <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Permisos</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('permiso.create')
    <div class="col-sm-3">
      <a class="btn btn-primary" href="{{route('permiso.create')}}" ><i class="fa fa-plus"></i> Nuevo Permiso</a>
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
					<th>Fecha Registro</th>
					<th>Acciones</th>
  				</thead>
  				<tbody>
  				@foreach($permisos as $permiso)
					<tr>
						<td>{{ $permiso->name }}</td>
            <td>{{ $permiso->slug }}</td>
						<td>{{ $permiso->description }}</td>
						<td>{{$permiso->created_at->format('d/m/Y H:i:s')}}</td>
            <td style="width:140px">
              <div class="row">
                @can('permiso.edit')
                  <div class="col-6">
                    <a class="btn " style="background-color:  #102359;color: white" href="{{route('permiso.edit',$permiso->id)}}"><i class="fa fa-pencil"></i>
                    </a>
                  </div>
                @endcan
                @can('permiso.destroy')
                  <div class="col-6">
                      {!! Form::open(['route'=>['permiso.destroy',$permiso->id],'method'=>'DELETE','class' => 'deleteButton']) !!}
                    <div class="btn-group">
                      <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </div>
                      {!! Form:: close() !!}
                  </div>
                @endcan
              </div>
            </td>
					</tr>
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop