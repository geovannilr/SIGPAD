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

        $( ".btnEnviar" ).click(function() {
            if ($(this)[0].id == 'acc') {
                $("#opc").val(1);
            }else if ($(this)[0].id  == 'rec') {
                $("#opc").val(2);
            }
            $("#formCierre").submit();
        });
	});
</script>
		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5> <a href="{{ route('publicacion.index') }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Detalle de Pubicación de Trabajo de Graduación</h5>
	        </li>
			 <li class="breadcrumb-item active"><b>Publicacion:</b> &nbsp;{{ $publicacion->codigo_pub }}&nbsp;
				 @can('etapa.aprobar')
					 @if($publicacion->es_visible_pub == 0 )
						 <a class="btn-sm btn-warning"  data-toggle="modal" data-target="#modalAprobar"><i class="fa fa-check-square-o"></i>&nbsp;APROBAR</a>
					 @endif
				 @endcan
			 </li>
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
                    <tr><td>NO SE HAN REGISTRADO AUTORES PARA ESTA PUBLICACIÓN DE TRABAJO DE GRADUACIÓN</td></tr>
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
                    <tr><td style="text-align: center;">NO SE HAN REGISTRADO COLABORADORES PARA ESTA PUBLICACIÓN DE TRABAJO DE GRADUACIÓN</td></tr>
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

		<p class="text-center"> {{$publicacion->resumen_pub}}</p>
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
						<td>{{ date_format(date_create($publicacionArchivo->fecha_subida_pub_arc), 'd/m/Y H:i:s')}}</td>
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

@can('etapa.aprobar')
	<div class="modal fade" id="modalAprobar" tabindex="-1" role="dialog" aria-labelledby="modalAprobar" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header breadcrumb" style="margin-bottom: 0px !important;">
					<h5>Aprobar Cierre de Trabajo de Graduación</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					Esto publicará el tomo en la Biblioteca de Trabajos de Graduación y dará por finalizado el proceso de Trabajo de Graduación.
					<p class="text-danger">Tenga en cuenta que esta acción no puede deshacerse.</p>
				</div>
				{!! Form:: open(['route'=>'aprobarCierreGrupo','method'=>'POST', 'id'=>'formCierre']) !!}
				<div class="modal-footer">
					{!! Form::button('Aprobar',['class'=>'btn btn-success btnEnviar','id'=>'acc']) !!}
					{!! Form::button('Rechazar',['class'=>'btn btn-danger btnEnviar','id'=>'rec']) !!}
					<button type="button" class="btn btn-secondary" onclick="$('#modalAprobar').modal('toggle');">Cancelar</button>
					<input type="hidden" name="opc" id="opc" />
					<input type="hidden" name="pub" id="pub" value="{{$publicacion->id_pub}}" />
				</div>
				{!! Form:: close() !!}
			</div>
		</div>
	</div>
@endcan
@stop