@extends('template')

@section('content')
@if(Session::has('message'))
  		<script type="text/javascript">
  			$( document ).ready(function() {
    			swal("", "{{Session::get('message')}}", "success");
			});
  		</script>		
@endif
@if(Session::has('error'))
  		<script type="text/javascript">
  			$( document ).ready(function() {
    			swal("", "{{Session::get('error')}}", "error");
			});
  		</script>		
@endif
<script type="text/javascript">
	$( document ).ready(function() {
    	$("#listTable").DataTable({
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                title: 'Listado de Archivos Publicaciones de Trabajos de Graduación'
            },
            {
                extend: 'pdfHtml5',
                title: 'Listado de Publicaciones de Trabajos de Graduación'
            },
             {
                extend: 'csvHtml5',
                title: 'Listado de Publicaciones de Trabajos de Graduación'
            },
            {
                extend: 'print',
                title: 'Listado de Publicaciones de Trabajos de Graduación'
            }


        ],
         responsive: {
            details: {
                type: 'column'
            }
        },
        order: [ 1, 'desc' ],
    	});

		$(".deleteButton").submit(function( event ) {
			event.preventDefault();
    		var titulo;
   			var mensaje;
      		titulo ="Eliminar Pre-Perfil";
      		mensaje="Estas seguro que quiere eliminar esta publicación de trabajo de graduación?";
	        swal({
	            title: titulo,
	            text: mensaje, 
	            icon: "warning",
	            buttons: true,
	            successMode: true,
	        })
	        .then((aceptar) => {
	          if (aceptar) {
	            this.submit();
	          } else {
	            return;
	          }
	        });		
		});
	});
</script>
		<ol class="breadcrumb">
	        <li class="breadcrumb-item">
	          <h5>Detalle de Pubicación de Trabajo de Graduació</h5>
	        </li>
				 <li class="breadcrumb-item active">PUBLICACION </li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('documentoPublicacion.create')
	  <div class="col-sm-3">Nuevo Documento
	  	 {!! link_to_route('nuevoDocumentoPublicacion', $title ='Histórico de trabajos de graduación',$publicacion->id_pub,$attributes = []); !!}
	  </div>
  @endcan
</div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
  					<th>Nombre</th>
					<th>Descripcíón</th>
					<th>Fecha de Subida</th>
					@can('publicacion.show')
						<th>Detalle</th>
					@endcan
					@can('publicacion.edit')
						<th>Modificar</th>
					@endcan
					@can('publicacion.destroy')
						<th>Eliminar</th>
					@endcan	
  				</thead>
  				<tbody>

  				@foreach($publicacionesArchivos as $publicacionArchivo)
  						<tr>
  						<td>{{ $publicacionArchivo->nombre_pub_arc }}</td>	
						<td>{{ $publicacionArchivo->descripcion_pub_arc }}</td>
						<td>{{ $publicacionArchivo->fecha_subida_pub_arc}}</td>
						@can('publicacion.show')
							<td>
								<a class="btn btn-primary" href="{{route('publicacion.show',$publicacionArchivo->id_pub_arc)}}"><i class="fa fa-eye"></i></a>
							</td>
						@endcan	
						@can('publicacion.edit')
							<td>
								<a class="btn btn-primary" href="{{route('publicacion.edit',$publicacionArchivo->id_pub_arc)}}"><i class="fa fa-pencil"></i></a>
							</td>
						@endcan
						@can('publicacion.destroy')
							<td>
								{!! Form::open(['route'=>['publicacion.destroy',$publicacionArchivo->id_pub_arc],'method'=>'DELETE','class' => 'deleteButton']) !!}
							 		<div class="btn-group">
										<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
						@endcan
					</tr>				
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop