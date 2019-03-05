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
        if(!confirm('Estas seguro que deseas eliminar tipo publicación')){

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
                    columns: [ 0,1]
                },
                title: 'Listado de tipo publicación'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0,1]
                },
                title: 'Listado de tipo publicación'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0,1]
                },
                title: 'Listado de tipo publicación'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0,1]
                },
                title: 'Listado de tipo publicación'
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
	          <h5><a href="{{ route('catCatalogo.index') }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Típo Publicación</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado Tipo Publicaciones</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('catTpublicacion.create')
    <div class="col-sm-3">
      <a class="btn btn-primary" href="{{route('catTpublicacion.create')}}" ><i class="fa fa-plus"></i> Nuevo Tipo Publicación</a>
    </div>
  @endcan
  </div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Nombre</th>
                    <th>Descripción</th>
         			@can('catTpublicacion.edit')
                    <th style="text-align: center;">Acciones</th>
                    @endcan
                    @can('catTpublicacion.destroy')
                    @endcan
  				</thead>
  				<tbody>
  				@foreach($catTpublicacion as $catTpublicacio)
					<tr>
						<td>{{ $catTpublicacio->nombre_cat_tpo_pub}}</td>
                        <td>{{ $catTpublicacio->descripcion_cat_tpo_pub}}</td>
                        <td style="width: 160px">
                            <div class="row">
                                @can('catTpublicacion.edit')
                                    <div class="col-6">
                                        <a class="btn " style="background-color:  #102359;color: white" href="{{route('catTpublicacion.edit',$catTpublicacio->id_cat_tpo_pub)}}"><i class="fa fa-pencil"></i></a>
                                    </div>
                                @endcan
                                    @can('catTpublicacion.destroy')
                                    <div class="col-6">
                                        {!! Form::open(['route'=>['catTpublicacion.destroy',$catTpublicacio->id_cat_tpo_pub],'method'=>'DELETE','class' => 'deleteButton']) !!}
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