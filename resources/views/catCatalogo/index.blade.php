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
             @can('catCatalogo.create')
                 <div class="col-sm-3">
                     <a class="btn " href="{{route('catCatalogo.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo Catálogo</a>
                 </div>
             @endcan
  </div>


		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

                <thead>
                <th>Nombre</th>
                <th>Descripción</th>
               @can('catCatalogo.edit')
                    <th style="text-align: center;">Acciones</th>
                @endcan
                @can('catCatalogo.destroy')
                @endcan
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
                        <td style="width: 160px">
                        <div class="row">
                            @can('catCatalogo.edit')
                                <div class="col-6">
                                    <a class="btn " style="background-color:  #102359;color: white" href="{{route('catCatalogo.edit',$catCatalog->id_gen_cat)}}"><i class="fa fa-pencil"></i></a>
                                </div>
                            @endcan
                            @can('catCatalogo.destroy')
                                <div class="col-6">
                                    {!! Form::open(['route'=>['catCatalogo.destroy',$catCatalog->id_gen_cat],'method'=>'DELETE','class' => 'deleteButton']) !!}
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </div>
                                    {!! Form:: close() !!}
                                </div>
                            @endcan
                        </div>
                        @can('catalogo.edit')
                            <td style="text-align: center;">
                                <script>
                                    function pendienteConfiguracion() {
                                        alert("Pendiente de configurar código (catálogo)")
                                    }
                                </script>

                                <?php

                                try {
                                    $ruta = route($catCatalog->ruta_gen_cat . '.index');
                                    $enlace= '<a class="btn " title="Configurar" style="background-color:  #102359;color: white"  href="'.$ruta.'"><i class="fa fa-cog"></i></a> ';
                                }  catch (\Exception $exception) {
                                    $enlace='<a class="btn " title="Pendiente de configurar"   href="javascript:pendienteConfiguracion();"><i class="fa fa-cog"></i></a> ';
                                }
                                echo $enlace;
                                ?>
                            </td>
                         @endcan

					</tr>
                    @endif
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop