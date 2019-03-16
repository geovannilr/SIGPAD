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
                    columns: [ 0, 1, 2, 3,4]
                },
                title: 'Listado de Pre-Perfiles'
            },
            {
                extend: 'pdfHtml5',
                 exportOptions: {
                    columns: [ 0, 1, 2, 3,4]
                },
                title: 'Listado de Pre-Perfiles'
            },
             {
                extend: 'csvHtml5',
                 exportOptions: {
                    columns: [ 0, 1, 2, 3,4]
                },
                title: 'Listado de Pre-Perfiles'
            },
            {
                extend: 'print',
                 exportOptions: {
                    columns: [ 0, 1, 2, 3,4]
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
    function verTribunal(){
        $("#modalTribunalData").modal();
	}
</script>
		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item"  style="text-align: center;">
	          <h5  style="text-align: center;">  <a href="{{ Auth::user()->can('prePerfil.create')?route('inicio'):route('prePerfil.index') }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Pre-Perfil</h5>
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

					  	 <a class="btn btn-primary" href="{{route('prePerfil.create')}}" ><i class="fa fa-plus"></i> Nuevo Pre-perfil </a>

				  @endcan
						 @if ($tribunal!="NA")

							 <button id="btnTribunalEvaluador" type="button" onclick="verTribunal();" class="btn btn-secondary" title="Ver Tribunal Evaluador">
								 <i class="fa fa-balance-scale" > Tribunal </i>
							 </button>

						 @endif
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
					<th>Categoría</th>
					<th>Acciones</th>

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
						<td>{{ $prePerfil->categoriaTrabajo->nombre_cat_ctg_tra}}</td>
						<td style="width: 170px">
							<div class="row">
								@can('prePerfil.edit')
									<div class="col-4">
										<a class="btn" style="background-color:  #102359;color: white" href="{{route('prePerfil.edit',$prePerfil->id_pdg_ppe)}}"><i class="fa fa-pencil"></i></a>
									</div>
								@endcan
								@can('prePerfil.destroy')
									<div class="col-4">
										{!! Form::open(['route'=>['prePerfil.destroy',$prePerfil->id_pdg_ppe],'method'=>'DELETE','class' => 'deleteButton']) !!}
								 		<div class="btn-group">
											<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
										</div>
										{!! Form:: close() !!}
									</div>
								@endcan
								<div class="col-4">
									{!! Form::open(['route'=>['downloadPrePerfil'],'method'=>'POST']) !!}
								 		<div class="btn-group">
								 			{!!Form::hidden('archivo',$prePerfil->id_pdg_ppe,['class'=>'form-control'])!!}
								 			{!!Form::hidden('grupo',$prePerfil->grupo->id_pdg_gru,['class'=>'form-control'])!!}
											<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
										</div>
								 	{!! Form:: close() !!}
								</div>
								
								@can('prePerfil.aprobar')
									<div class="col-4">
										@if($prePerfil->id_cat_sta != "10"  &&  $prePerfil->id_cat_sta != "12" )
											{!! Form::open(['route'=>['aprobarPreperfil'],'method'=>'POST','class'=>'aprobar']) !!}
										 		<div class="btn-group">
										 			{!!Form::hidden('idPrePerfil',$prePerfil->id_pdg_ppe,['class'=>'form-control'])!!}
													<button type="submit" class="btn btn-success"><i class="fa fa-check"></i></button>
												</div>
											{!! Form:: close() !!}
										@endif
									</div>
								@endcan
								
								@can('prePerfil.rechazar')
									<div class="col-4">
										@if($prePerfil->id_cat_sta != "10"  &&  $prePerfil->id_cat_sta != "12" )
											{!! Form::open(['route'=>['rechazarPrePerfil'],'method'=>'POST','class'=>'rechazar']) !!}
										 		<div class="btn-group">
										 			{!!Form::hidden('idPrePerfil',$prePerfil->id_pdg_ppe,['class'=>'form-control'])!!}
													<button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i></button>
												</div>
											{!! Form:: close() !!}
										@endif
									</div>
									
								@endcan

								
							</div>	

						</td>
						

					</tr>
				@endforeach
				</tbody>
			</table>
	   </div>
<!-- Modal Tribunal Evaluador -->
<div class="modal fade" id="modalTribunalData" tabindex="-1" role="dialog" aria-labelledby="Tribunal Evaluador" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Tribunal Evaluador</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div id="modalTribunalBody" class="modal-body">
				<table class='table table-hover table-striped  display' id='tblTribunal'>
					<thead><th>Nombre</th><th>Rol</th><th>Contacto</th></thead>
					<tbody>
                    @if($tribunal!="NA")
					@foreach($tribunal as $trib)
						<tr>
							<td>{{$trib->name}}</td>
							<td>{{$trib->nombre_tri_rol}}</td>
							<td>{{$trib->email}}</td>
						</tr>
					@endforeach
                    @else
                        <tr><td colspan="3">No se ha configurado tribunal evaluador</td></tr>
                    @endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@stop