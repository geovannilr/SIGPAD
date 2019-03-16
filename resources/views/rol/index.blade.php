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
        language: {
                url: 'es-ar.json' //Ubicacion del archivo con el json del idioma.
        },
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2 ]
                },
                title: 'Listado de Roles'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2 ]
                },
                title: 'Listado de Roles'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2 ]
                },
                title: 'Listado de Roles'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1, 2 ]
                },
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
        <a class="btn btn-primary" href="{{route('rol.create')}}" ><i class="fa fa-plus"></i> Nuevo Rol</a>
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
          <th style="width: 140px">Acciones </th>
  				</thead>
  				<tbody>
  				@foreach($roles as $rol)
            <?php
              $permisos=$rol->getPermissions();
            ?>
					<tr>
						<td>{{ $rol->name }}</td>
						<td>{{ $rol->description }}</td>
						<td>{{$rol->created_at->format('d/m/Y H:i:s')}}</td>
            <td style="text-align: center;">
              <div class="row">
                <div class="col-4">
                  <a class="btn btn-info"  href="{{route('rol.show',$rol->id)}}"><i class="fa fa-eye"></i></a>
                </div>
                @can('rol.edit')
                <div class="col-4">
                   <a class="btn " style="background-color:  #102359;color: white" href="{{route('rol.edit',$rol->id)}}"><i class="fa fa-pencil"></i></a>
                </div>
                @endcan
                @can('rol.destroy')
                  <div class="col-4">
                    {!! Form::open(['route'=>['rol.destroy',$rol->id],'method'=>'DELETE','class' => 'deleteButton']) !!}
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