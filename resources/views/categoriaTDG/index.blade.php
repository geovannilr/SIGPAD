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
        if(!confirm('Estas seguro que deseas eliminar esta Categoría')){

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
                    columns: [0]
                },
                title: 'Listado de Categorías'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de Categorías'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de Categorías'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de Categorías'
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
	          <h5><a href="{{ route('catCatalogo.index') }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     CATEGORIAS DE TDG</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado de Categorías de Trabajo de Graduación</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('categoriaTDG.create')
    <div class="col-sm-3">
      <a class="btn btn-primary" href="{{route('categoriaTDG.create')}}" ><i class="fa fa-plus"></i> Nueva Categoría de TDG</a>
    </div>
  @endcan
  </div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Nombre de Categoría</th>
         			@can('categoriaTDG.edit')
                    <th style="text-align: center;">Acciones</th>
                    @endcan
                    @can('categoriaTDG.destroy')
                    @endcan
  				</thead>
  				<tbody>
  				@foreach($categoriasTDG as $categoria)
					<tr>
						<td>{{ $categoria->nombre_cat_ctg_tra}}</td>
                        <td style="width: 160px">
                            <div class="row">
                                @can('categoriaTDG.edit')
                                    <div class="col-6">
                                        <a class="btn " style="background-color:  #102359;color: white" href="{{route('categoriaTDG.edit',$categoria->id_cat_ctg_tra)}}"><i class="fa fa-pencil"></i></a>
                                    </div>
                                @endcan
                                    @can('categoriaTDG.destroy')
                                    <div class="col-6">
                                        {!! Form::open(['route'=>['categoriaTDG.destroy',$categoria->id_cat_ctg_tra],'method'=>'DELETE','class' => 'deleteButton']) !!}
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        </div>
                                        {!! Form:: close() !!}
                                    </div>
                                @endcan
                            </div>

					</tr>
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop