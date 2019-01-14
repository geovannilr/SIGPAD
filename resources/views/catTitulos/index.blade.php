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
        if(!confirm('Estas seguro que deseas eliminar título')){

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
                    columns: [ 0]
                },
                title: 'Listado de título'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de título'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de título'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de título'
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
	          <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Títulos</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado Títulos</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('catTitulos.create')
    <div class="col-sm-3">
      <a class="btn " href="{{route('catTitulos.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo Título</a>
    </div>
  @endcan
  </div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Título</th>
         			@can('catTitulos.edit')
                    <th style="text-align: center;">Acciones</th>
                    @endcan
                    @can('catTitulos.destroy')
                    @endcan
  				</thead>
  				<tbody>
  				@foreach($catTitulos as $catTitulo)
					<tr>
						<td>{{ $catTitulo->nombre_titulo_cat_tit}}</td>
           				@can('catTitulos.edit')
                            @can('catTitulos.destroy')

                            <td style="text-align: center;">
  							<a class="btn " style="background-color:  #102359;color: white" href="{{route('catTitulos.edit',$catTitulo->id_cat_tit)}}"><i class="fa fa-pencil"></i></a>
  						    {!! Form::open(['route'=>['catTitulos.destroy',$catTitulo->id_cat_tit],'method'=>'DELETE','class' => 'deleteButton']) !!}
  						 		<div class="btn-group">
  									<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
  								</div>
  							{!! Form:: close() !!}
  						</td>
                            @endcan
                        @endcan
					</tr>
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop