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
                title: 'Listado de Pre-Perfiles'
            },
            {
                extend: 'pdfHtml5',
                 exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de Pre-Perfiles'
            },
             {
                extend: 'csvHtml5',
                 exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de Pre-Perfiles'
            },
            {
                extend: 'print',
                 exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de Pre-Perfiles'
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
      		titulo ="Aprobar Pre-Perfil";
      		mensaje="Estas seguro que quiere aprobar este Pre-Perfil?";
	        swal({
	            title: titulo,
	            text: mensaje, 
	            icon: "warning",
	            buttons: true,
	            successMode: true,
	        })
	        .then((aceptar) => {
	          if (aceptar) {
	            console.log("Aprobar PrePerfil");
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
      		titulo ="Rechazar Pre-Perfil";
      		mensaje="Estas seguro que quiere rechazar este Pre-Perfil?";
	        swal({
	            title: titulo,
	            text: mensaje, 
	            icon: "warning",
	            buttons: true,
	            successMode: true,
	        })
	        .then((aceptar) => {
	          if (aceptar) {
	            console.log("Rechazar PrePerfil");
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
		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item"  style="text-align: center;">
	          <h5  style="text-align: center;"">  <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Pre-Perfil</h5>
	        </li>
	        @if(isset($numero))
				 <li class="breadcrumb-item active" >Grupo {{$numero}} </li>
	        @endif
		</ol>
		 <div class="row">
			  <div class="col-sm-3"> </div>
			  <div class="col-sm-3"></div>
			  <div class="col-sm-3"></div>
			   <div class="col-sm-3">
			   	 	 @can('prePerfil.create')
					  <div class="col-sm-3"> 
					  	 <a class="btn " href="{{route('prePerfil.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo Pre-perfil </a>
					  </div>
				  @endcan
			   </div>
			 
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
					@can('prePerfil.edit')
						<th>Modificar</th>
					@endcan
					@can('prePerfil.destroy')
						<th>Eliminar</th>
					@endcan
						<th>Descargar</th>
					@can('prePerfil.aprobar')
						<th>Aprobar</th>
					@endcan
					@can('prePerfil.rechazar')
						<th>Rechazar</th>
					@endcan	
						
  				</thead>
  				<tbody>

  				@foreach($prePerfiles as $prePerfil)
  						<tr>
  							@if(!isset($numero))
  								<td>{{ $prePerfil->grupo->numero_pdg_gru }}</td>
  							@endif
						<td>{{ $prePerfil->tema_pdg_ppe }}</td>
						<td>{{ date_format(date_create($prePerfil->fecha_creacion_pdg_ppe), 'd/m/Y H:i:s')}}</td>
						<td>
							@if($prePerfil->id_cat_sta == "10" )
								<span class="badge badge-success">{{ $prePerfil->categoriaEstado->nombre_cat_sta }}</span>&nbsp;
							@else
								@if($prePerfil->id_cat_sta == "12" )
									<span class="badge badge-danger">{{ $prePerfil->categoriaEstado->nombre_cat_sta }}</span>&nbsp;
								@else
								<span class="badge badge-info">{{ $prePerfil->categoriaEstado->nombre_cat_sta }}</span>&nbsp; 	
								@endif
									
							@endif
							
						</td>
						<td>{{ $prePerfil->tipoTrabajo->nombre_cat_tpo_tra_gra}}</td>
						@can('prePerfil.edit')
							<td style="text-align: center;">
								<a class="btn" style="background-color:  #102359;color: white" href="{{route('prePerfil.edit',$prePerfil->id_pdg_ppe)}}"><i class="fa fa-pencil"></i></a>
							</td>
						@endcan
						@can('prePerfil.destroy')
							<td style="text-align: center;">
								{!! Form::open(['route'=>['prePerfil.destroy',$prePerfil->id_pdg_ppe],'method'=>'DELETE','class' => 'deleteButton']) !!}
							 		<div class="btn-group">
										<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
						@endcan

							<td style="text-align: center;">
								{!! Form::open(['route'=>['downloadPrePerfil'],'method'=>'POST']) !!}
							 		<div class="btn-group">
							 			{!!Form::hidden('archivo',$prePerfil->id_pdg_ppe,['class'=>'form-control'])!!}
							 			{!!Form::hidden('grupo',$prePerfil->grupo->id_pdg_gru,['class'=>'form-control'])!!}
										<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
									</div>
								 {!! Form:: close() !!}
							</td >
							@can('prePerfil.aprobar')
								<td style="text-align: center;">
									@if($prePerfil->id_cat_sta != "10"  &&  $prePerfil->id_cat_sta != "12" )
										{!! Form::open(['route'=>['aprobarPreperfil'],'method'=>'POST','class'=>'aprobar']) !!}
									 		<div class="btn-group">
									 			{!!Form::hidden('idPrePerfil',$prePerfil->id_pdg_ppe,['class'=>'form-control'])!!}
												<button type="submit" class="btn btn-success"><i class="fa fa-check"></i></button>
											</div>
										{!! Form:: close() !!}
									@endif
								</td>
							@endcan
							@can('prePerfil.rechazar')
								<td style="text-align: center;">
									@if($prePerfil->id_cat_sta != "10"  &&  $prePerfil->id_cat_sta != "12" )
										{!! Form::open(['route'=>['rechazarPrePerfil'],'method'=>'POST','class'=>'rechazar']) !!}
									 		<div class="btn-group">
									 			{!!Form::hidden('idPrePerfil',$prePerfil->id_pdg_ppe,['class'=>'form-control'])!!}
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