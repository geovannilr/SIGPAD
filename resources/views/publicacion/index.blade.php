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
      		titulo ="Eliminar Pre-Perfil";
      		mensaje="Estas seguro que quiere eliminar este Pre-Perfil?";
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
	  	 <a class="btn btn-primary" href="{{route('prePerfil.create')}}"><i class="fa fa-plus"></i></a>
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
					<th>Modificar</th>
					<th>Eliminar</th>
					<th>Descargar</th>
					
						
  				</thead>
  				<tbody>

  				@foreach($publicaciones as $publicacion)
  						<tr>
  						<td>{{ $publicacion->codigo_pub }}</td>	
						<td>{{ $publicacion->anio_pub }}</td>
						<td>{{ $publicacion->titulo_pub}}</td>
						@can('prePerfil.edit')
							<td>
								<a class="btn btn-primary" href="{{route('prePerfil.edit',$publicacion->id_pub)}}"><i class="fa fa-pencil"></i></a>
							</td>
						@endcan
						@can('prePerfil.destroy')
							<td>
								{!! Form::open(['route'=>['prePerfil.destroy',$publicacion->id_pub],'method'=>'DELETE','class' => 'deleteButton']) !!}
							 		<div class="btn-group">
										<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
						@endcan

							<td>
								{!! Form::open(['route'=>['downloadPrePerfil'],'method'=>'POST']) !!}
							 		<div class="btn-group">
							 			{!!Form::hidden('archivo',$publicacion->id_pub,['class'=>'form-control'])!!}
										<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
					</tr>				
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop