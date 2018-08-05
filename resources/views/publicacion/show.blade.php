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
    	

		$(".deleteButton").submit(function( event ) {
			event.preventDefault();
    		var titulo;
   			var mensaje;
      		titulo ="Eliminar Documento de Publicación de trabajo de graduación";
      		mensaje="Estas seguro que quiere eliminar este documento de  publicación de trabajo de graduación?";
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
	          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Detalle de Pubicación de Trabajo de Graduación</h5>
	        </li>
				 <li class="breadcrumb-item active"><b>Publicacion:</b> &nbsp;{{ $publicacion->codigo_pub }}&nbsp; </li>
		</ol>
		<br>

		<h5 style="text-align: center; font-weight: bold"> TEMA:  </h5>
		      <p class="text-center"> {{ $publicacion->titulo_pub }}</p>
      <br>
      		<hr />
      <br>


      <h5 style="text-align: center; font-weight: bold"> INTEGRANTES  </h5>
		<br>
		 <div class="row">
        <div class="col-sm-6">
              <div class="table-responsive">
                <table class="table table-hover table-striped">
                  <th style="text-align: center;">Autores</th>
                  @if ($autores=="NA")
                    <tr><td>NO SE HAN REGISTRADO AUTORES PARA ESTA PUBLCIACIÓN DE TRABAJO DE GRADUACIÓN</td></tr>
                  @else
	                   @foreach($autores as $autor)
	                   		<tr>
		                   		<td style="text-align: center;">
			                      {{$autor->nombres_pub_aut}} &nbsp;{{$autor->apellidos_pub_aut}}
			                    </td> 	
			                    
	                   		</tr>
		                  
	                   @endforeach
                   @endif
                </table>
              </div>
        </div>
        <div class="col-sm-6">
          <div class="table-responsive">
                <table class="table table-hover table-striped">
                  <th colspan="2" style="text-align: center;">Colaboradores</th>
                  @if ($colaboradores=="NA")
                    <tr><td style="text-align: center;">NO SE HAN REGISTRADO COLABORADORES PARA ESTA PUBLCIACIÓN DE TRABAJO DE GRADUACIÓN</td></tr>
                  @else
	                   @foreach($colaboradores as $colaborador)
	                   		<tr>
		                   		<td style="text-align: center;">
			                      {{$colaborador->nombres_pub_col}} &nbsp;{{$colaborador->apellidos_pub_col}}
			                    </td>
			                    <td>
			                    	<span class="badge badge-pill badge-info">{{$colaborador->nombre_cat_tpo_col_pub}}</span>
			                    </td> 	
			                    
	                   		</tr>
		                  
	                   @endforeach
                   @endif
                </table>

          </div>
        </div>
      </div>

      	
      	<br>
		<hr />
		<h5 style="text-align: center; font-weight: bold"> RESUMEN  </h5>
		<br>

		<p class="text-center"> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      <br>


      <hr />
      <br>

      

      <h5 style="text-align: center; font-weight: bold"> DOCUMENTOS  </h5>
		<br>
	   
		 <div class="row">
		  <div class="col-sm-3"></div>
		  <div class="col-sm-3"></div>
		  <div class="col-sm-3"></div>
			  @can('documentoPublicacion.create')
				  <div class="col-sm-3">
				  	 <a class="btn btn-primary" href="{{route('nuevoDocumentoPublicacion',$publicacion->id_pub)}}"><i class="fa fa-plus"></i> Agregar Documento</a>
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
					@can('documentoPublicacion.edit')
						<th>Modificar</th>
					@endcan
					@can('documentoPublicacion.destroy')
						<th>Eliminar</th>
					@endcan	
						<th>Descargar</th>
  				</thead>
  				<tbody>

  				@foreach($publicacionesArchivos as $publicacionArchivo)
  						<tr>
  						<td>{{ $publicacionArchivo->nombre_pub_arc }}</td>	
						<td>{{ $publicacionArchivo->descripcion_pub_arc }}</td>
						<td>{{ $publicacionArchivo->fecha_subida_pub_arc}}</td>
						@can('documentoPublicacion.edit')
							<td>
								<a class="btn btn-primary" href="{{ url('/')}}/editDocumentoPublicacion/{{ $publicacionArchivo->id_pub }}/{{ $publicacionArchivo->id_pub_arc }}"><i class="fa fa-pencil"></i></a>
			
							</td>
						@endcan
						@can('documentoPublicacion.destroy')
							<td>
								{!! Form::open(['route'=>['deleteDocumentoPublicacion'],'method'=>'POST','class' => 'deleteButton']) !!}
									{{ Form::hidden('publicacion', $publicacion->id_pub) }}
									{{ Form::hidden('archivo',$publicacionArchivo->id_pub_arc) }}
							 		<div class="btn-group">
										<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
						@endcan
						
							<td>
								{!! Form::open(['route'=>['downloadDocumentoPublicacion'],'method'=>'POST']) !!}
							 		<div class="btn-group">
							 			{!!Form::hidden('archivo',$publicacionArchivo->id_pub_arc,['class'=>'form-control'])!!}
										<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
					
					</tr>				
				@endforeach 
				</tbody>
			</table>
	   </div>

	   <br>

	  

@stop