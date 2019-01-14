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
        if(!confirm('Estas seguro que deseas eliminar esta materia?')){

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
                title: 'Listado de materias'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de materias'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de materias'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1, 2]
                },
                title: 'Listado de tipo Documento'
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
	          <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Materias</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('catMateria.create')
    <div class="col-sm-3">
      <a class="btn " href="{{route('catMateria.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nueva Materia</a>
    </div>
  @endcan
  </div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Código de materia</th>
                    <th>Nombre de Materia</th>
                    <th>Año de pensum</th>
                    <th>Ciclo</th>
                    @can('catMateria.edit')
                    <th style="text-align: center;">Acciones</th>
                    @endcan
                    @can('catMateria.destroy')
                    @endcan
  				</thead>
  				<tbody>
  				@foreach($catMateria as $catMateri)
					<tr>
						<td>{{ $catMateri->codigo_mat }}</td>
                        <td>{{ $catMateri->nombre_mat }}</td>
						<td>{{ $catMateri->anio_pensum }}</td>
                        <td>{{ $catMateri->ciclo }}</td>
                         @can('catMateria.edit')
                            @can('catMateria.destroy')

                            <td style="text-align: center;">
  			            	<a class="btn " style="background-color:  #102359;color: white" href="{{route('catMateria.edit',$catMateri->id_cat_mat)}}"><i class="fa fa-pencil"></i></a>
                                {!! Form::open(['route'=>['catMateria.destroy',$catMateri->id_cat_mat],'method'=>'DELETE','class' => 'deleteButton']) !!}
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