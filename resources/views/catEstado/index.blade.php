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
        if(!confirm('Estas seguro que deseas eliminar este Estado?')){

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
                    columns: [ 0, 1]
                },
                title: 'Listado de estados'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de Estados'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de Estados'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de Estados'
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
	          <h5><a href="{{ route('catCatalogo.index') }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Estados</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('catEstado.create')
    <div class="col-sm-3">
      <a class="btn btn-primary" href="{{route('catEstado.create')}}" ><i class="fa fa-plus"></i> Nuevo estado</a>
    </div>
  @endcan
  </div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Estado</th>
                    <th>Descripcion</th>
                    <th>Tipo Estado</th>

                    @can('catEstado.destroy')
                    <th style="text-align: center;">Acciones</th>
                    @can('catEstado.edit')

                    @endcan
                    @endcan

                </thead>
                <tbody>
                @foreach($catEstado as $catEstad)
					<tr>
						<td>{{ $catEstad->nombre_cat_sta}}</td>
                        <td>{{ $catEstad->descripcion_cat_sta}}</td>
                        <td>{{ $catEstad->tipoEstado->nombre_cat_tpo_sta}}</td>
                        <td style="width: 160px">
                            <div class="row">
                                @can('catEstado.edit')
                                    <div class="col-6">
                                        <a class="btn " style="background-color: #102359;color: white" href="{{route('catEstado.edit',$catEstad->id_cat_sta)}}"><i class="fa fa-pencil"></i></a>
                                    </div>
                                @endcan
                                    @can('catEstado.destroy')
                                    <div class="col-6">
                                        {!! Form::open(['route'=>['catEstado.destroy',$catEstad->id_cat_sta],'method'=>'DELETE','class' => 'deleteButton']) !!}
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