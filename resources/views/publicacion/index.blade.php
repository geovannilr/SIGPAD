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
                title: 'Listado de Publicaciones de Trabajos de Graduación'
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
      		titulo ="Eliminar Publicación de Trabajo de graduación";
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
		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5>Publicaciones de Trabajo de Graduación</h5>
	        </li>
				 <li class="breadcrumb-item active">Listado Histórico </li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('prePerfil.create')
	  <div class="col-sm-3">Nueva Pubicación
	  	 <a class="btn btn-primary" href="{{route('publicacion.create')}}"><i class="fa fa-plus"></i></a>
	  </div>
  @endcan
</div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
  					<th>Cod. Pubicación</th>
					<th>Año</th>
					<th>Título</th>
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
  				@foreach($publicaciones as $publicacion)
  						<tr>
  						<td>{{ $publicacion->codigo_pub }}</td>	
						<td>{{ $publicacion->anio_pub }}</td>
						<td>{{ $publicacion->titulo_pub}}</td>
						@can('publicacion.show')
							<td>
								<a class="btn btn-primary" href="{{route('publicacion.show',$publicacion->id_pub)}}"><i class="fa fa-eye"></i></a>
							</td>
						@endcan	
						@can('publicacion.edit')
							<td>
								<a class="btn btn-primary" href="{{route('publicacion.edit',$publicacion->id_pub)}}"><i class="fa fa-pencil"></i></a>
							</td>
						@endcan
						@can('publicacion.destroy')
							<td>
								{!! Form::open(['route'=>['publicacion.destroy',$publicacion->id_pub],'method'=>'DELETE','class' => 'deleteButton']) !!}
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