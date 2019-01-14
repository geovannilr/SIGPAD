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
        if(!confirm('Estas seguro que deseas eliminar los catalogos')){

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
                title: 'Listado de Catálogos'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0,1]
                },
                title: 'Listado de Catálogos'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0,1]
                },
                title: 'Listado de Catálogos'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0,1]
                },
                title: 'Listado de Catálogos'
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
	          <h5>     Catálogos</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  </div>

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Nombre</th>
                    <th>Descripción</th>

                    @can('catalogo.edit')
                    <th style="text-align: center;">Gestionar</th>
                    @endcan
                </thead>
  				<tbody>
                @foreach($catCatalogo as $catCatalog)
                    @if(Auth::user()->isRole($catCatalog->tipo_gen_cat))
					<tr>
						<td>{{ $catCatalog->nombre_gen_cat}}</td>
                        <td>{{ $catCatalog->descripcion_gen_cat}}</td>

                    @can('catalogo.edit')
                          <td style="text-align: center;">
                             <a class="btn " title="Configurar" style="background-color:  #102359;color: white" href="{{route($catCatalog->ruta_gen_cat.'.index')}}"><i class="fa fa-cog"></i></a>
                          </td>
                         @endcan
					</tr>
                    @endif
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop