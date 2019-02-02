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
    	language: {
                url: 'es-ar.json' //Ubicacion del archivo con el json del idioma.
        },	
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
	          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Publicaciones de Trabajo de Graduación</h5>
	        </li>
				 <li class="breadcrumb-item active">Listado Histórico </li>
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
  					<th>Cod. Pubicación</th>
					<th>Año</th>
					<th>Título</th>
					@if(Auth::user()->can(['publicacion.show'])||Auth::user()->can(['publicacion.destroy']))
						<th>Acciones</th>
					@endif
  				</thead>
  				<tbody>
  				@foreach($publicaciones as $publicacion)
					<tr>
  						<td>{{ $publicacion->codigo_pub }}</td>	
						<td>{{ $publicacion->anio_pub }}</td>
						<td>{{ $publicacion->titulo_pub}}</td>
						@if(Auth::user()->can(['publicacion.show'])||Auth::user()->can(['publicacion.destroy']))
							<td style="width: 100px;">
								<div class="row">
								@can('publicacion.show')
									<div class="col-6">
										<a class="btn btn-dark" href="{{route('publicacion.show',$publicacion->id_pub)}}"><i class="fa fa-eye"></i></a>
									</div>
								@endcan
								@can('publicacion.destroy')
									<div class="col-6">
									{!! Form::open(['route'=>['publicacion.destroy',$publicacion->id_pub],'method'=>'DELETE','class' => 'deleteButton']) !!}
										<div class="btn-group">
											<button type="submit" class="btn btn-danger" ><i class="fa fa-trash"></i></button>
										</div>
									{!! Form:: close() !!}
									</div>
								@endcan
								<div>
							</td>
						@endif
					</tr>
				@endforeach
				</tbody>
			</table>
	   </div>
@stop