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
                url: '../es-ar.json' //Ubicacion del archivo con el json del idioma.
        },
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0]
                },
                title: 'Listado de Permisos'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0]
                },
                title: 'Listado de Permisos'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0]
                },
                title: 'Listado de Permisos'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [0]
                },
                title: 'Listado de Permisos'
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
	          <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Rol </h5>
	        </li>
	        <li class="breadcrumb-item active">Listado de Permisos Asignados al Rol  <b><span class="badge badge-pill badge-info">{{$rol->name}}</span></b></li>
		</ol>
		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Permisos</th>
          
  				</thead>
  				<tbody>
            <?php
              $permisos=$rol->getPermissions();
            ?>
  				@foreach($permisos as $permiso)
					<tr>
						<?php
                   $name= Caffeinated\Shinobi\Models\Permission::where("slug","=",$permiso)->first();
            ?>
                   <td>{{$name->name}}</td>
            
					</tr>
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop