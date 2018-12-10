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
                 exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de Perfiles'
            },
            {
                extend: 'pdfHtml5',
                 exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de Perfiles'
            },
             {
                extend: 'csvHtml5',
                 exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de Perfiles'
            },
            {
                extend: 'print',
                 exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de Perfiles'
            }


        ],
         responsive: {
            details: {
                type: 'column'
            }
        },
        order: [ 1, 'asc' ],
    	});
    	$(".aprobar").submit(function( event ) {
    		event.preventDefault();
    		var titulo;
   			var mensaje;
      		titulo ="Aprobar Perfil";
      		mensaje="Estas seguro que quiere aprobar este Perfil?";
	        swal({
	            title: titulo,
	            text: mensaje, 
	            icon: "warning",
	            buttons: true,
	            successMode: true,
	        })
	        .then((aceptar) => {
	          if (aceptar) {
	            console.log("Aprobar Perfil");
	            this.submit();
	          } else {
	            return;
	          }
	        });		
		});
		$(".rechazar").submit(function( event ) {
			event.preventDefault();
    		var titulo;
   			var mensaje;
      		titulo ="Rechazar Perfil";
      		mensaje="Estas seguro que quiere rechazar este Perfil?";
	        swal({
	            title: titulo,
	            text: mensaje, 
	            icon: "warning",
	            buttons: true,
	            successMode: true,
	        })
	        .then((aceptar) => {
	          if (aceptar) {
	            console.log("Rechazar Perfil");
	            this.submit();
	          } else {
	            return;
	          }
	        });		
		});

		$(".deleteButton").submit(function( event ) {
			event.preventDefault();
    		var titulo;
   			var mensaje;
      		titulo ="Eliminar Perfil";
      		mensaje="Estas seguro que quiere eliminar este Perfil?";
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
	        <li class="breadcrumb-item"  style="text-align: center;">
	          <h5  style="text-align: center;"">  <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Perfil</h5>
	        </li>
	        @if(isset($numero))
				 <li class="breadcrumb-item active" >Grupo {{$numero}} </li>
			 @else
			 	<li class="breadcrumb-item active" >Listado </li>		 
	        @endif
		</ol>
		 <div class="row">
			  <div class="col-sm-3"> </div>
			  <div class="col-sm-3"></div>
			  <div class="col-sm-3"></div>
			  @if(sizeof($perfiles) == 0)
				 <div class="col-sm-3">
			   	 	 @can('perfil.create')
					  <div class="col-sm-3"> 
					  	 <a class="btn " href="{{route('perfil.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo Perfil </a>
					  </div>
				  @endcan
			   </div>
			  @endif			 
		</div> 
		<br>
		
	<!-- 	<br>
		<h5  style="text-align: center; font-weight: bold">	
			Pre-perfiles 
			@if(isset($numero)) 
				Grupo {{$numero}}
	        @endif</h5> -->

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
  					@if(!isset($numero))
  						<th>Grupo</th>
  					@endif
					<th>Tema</th>
					<th>Fecha Creación</th>
					<th>Estado</th>
					<th>Tipo</th>
					@can('perfil.edit')
						<th>Modificar</th>
					@endcan
					@can('perfil.destroy')
						<th>Eliminar</th>
					@endcan
						<th>Documento</th>
						<th>Resumen</th>
					@can('perfil.aprobar')
						<th>Aprobar</th>
					@endcan
					@can('perfil.rechazar')
						<th>Rechazar</th>
					@endcan	
						
  				</thead>
  				<tbody>

  				@foreach($perfiles as $perfil)
  						<tr>
  							@if(!isset($numero))
  								<td>{{ $perfil->grupo->numero_pdg_gru }}</td>
  							@endif
						<td>{{ $perfil->tema_pdg_per }}</td>
						<td>{{ date_format(date_create($perfil->fecha_creacion_per), 'd/m/Y H:i:s')}}</td>
						<td>
							@if($perfil->id_cat_sta == "9" )
								<span class="badge badge-success">{{ $perfil->categoriaEstado->nombre_cat_sta }}</span>&nbsp;
							@else
								@if($perfil->id_cat_sta == "11" )
									<span class="badge badge-danger">{{ $perfil->categoriaEstado->nombre_cat_sta }}</span>&nbsp;
								@else 
									<span class="badge badge-info">{{ $perfil->categoriaEstado->nombre_cat_sta }}</span>&nbsp;	
								@endif
							@endif
							
						</td>
						<td>{{ $perfil->tipoTrabajo->nombre_cat_tpo_tra_gra}}</td>
						@can('prePerfil.edit')
								<td>
										<a class="btn btn-info" href="{{route('perfil.edit',$perfil->id_pdg_per)}}"><i class="fa fa-pencil"></i></a>
								
								</td>
						@endcan
						@can('perfil.destroy')
							<td style="text-align: center;">
								{!! Form::open(['route'=>['perfil.destroy',$perfil->id_pdg_per],'method'=>'DELETE','class' => 'deleteButton']) !!}
							 		<div class="btn-group">
										<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
						@endcan

							<td style="text-align: center;">
								{!! Form::open(['route'=>['downloadPerfil'],'method'=>'POST']) !!}
							 		<div class="btn-group">
							 			{!!Form::hidden('archivo',$perfil->id_pdg_per,['class'=>'form-control'])!!}
							 			{!!Form::hidden('grupo',$perfil->grupo->id_pdg_gru,['class'=>'form-control'])!!}
										<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
							<td>
								{!! Form::open(['route'=>['downloadPerfilResumen'],'method'=>'POST']) !!}
							 		<div class="btn-group">
							 			{!!Form::hidden('archivo',$perfil->id_pdg_per,['class'=>'form-control'])!!}
							 			{!!Form::hidden('grupo',$perfil->grupo->id_pdg_gru,['class'=>'form-control'])!!}
										<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
							@can('perfil.aprobar')
								<td style="text-align: center;">
									@if($perfil->id_cat_sta != "9"  &&  $perfil->id_cat_sta != "11" )
										{!! Form::open(['route'=>['aprobarPerfil'],'method'=>'POST','class'=>'aprobar']) !!}

									 		<div class="btn-group">
									 			{!!Form::hidden('idPerfil',$perfil->id_pdg_per,['class'=>'form-control'])!!}
												<button type="submit" class="btn btn-success"><i class="fa fa-check"></i></button>
											</div>
										{!! Form:: close() !!}
									@endif
								</td>
							@endcan
							@can('perfil.rechazar')

								<td style="text-align: center;">
									@if($perfil->id_cat_sta != "9"  &&  $perfil->id_cat_sta != "11" )
										{!! Form::open(['route'=>['rechazarPerfil'],'method'=>'POST','class'=>'rechazar']) !!}
									 		<div class="btn-group">
									 			{!!Form::hidden('idPerfil',$perfil->id_pdg_per,['class'=>'form-control'])!!}
												<button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i></button>
											</div>
										{!! Form:: close() !!}
									@endif
								</td>
							@endcan
					</tr>				
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop