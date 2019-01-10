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
        if(!confirm('Estas seguro que deseas eliminar este Skill?')){

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
                title: 'Listado de Skills'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de Skills'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de Skills'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de Skills'
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
	          <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Catálogos</h5>
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
                    <th>Tipo Catálogo</th>

                    @can('catalogo.edit')
                    <th>Gestionar</th>
                    @endcan
                </thead>
  				<tbody>
                @foreach($catCatalogo as $catCatalog)
                    @if(Auth::user()->isRole($catCatalog->tipo_gen_cat))
					<tr>
						<td>{{ $catCatalog->nombre_gen_cat}}</td>
                        <td>{{ $catCatalog->descripcion_gen_cat}}</td>
                        <td>{{ $catCatalog->tipo_gen_cat}}</td>

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