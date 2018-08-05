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
    	// $("#listTable").DataTable({
     //    dom: '<"top"l>frt<"bottom"Bip><"clear">',
     //    buttons: [
     //       // {
     //       //      extend: 'excelHtml5',
     //       //      title: 'Listado de Perfiles'
     //       //  },
     //       //  {
     //       //      extend: 'pdfHtml5',
     //       //      title: 'Listado de Perfiles'
     //       //  },
     //       //   {
     //       //      extend: 'csvHtml5',
     //       //      title: 'Listado de Perfiles'
     //       //  },
     //       //  {
     //       //      extend: 'print',
     //       //      title: 'Listado de Perfiles'
     //       //  }


     //    ],
     //     responsive: {
     //        details: {
     //            type: 'column'
     //        }
     //    },
     //    order: [ 1, 'asc' ],
    	// });
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
		<ol class="breadcrumb">
	        <li class="breadcrumb-item">
	          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Perfil</h5>
	        </li>
	        @if(isset($numero))
				 <li class="breadcrumb-item active"> Grupo {{$numero}} </li>
	        @endif
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('prePerfil.create')
	  <div class="col-sm-3"> 
	  	 <a class="btn " href="{{route('perfil.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i>Nuevo Perfil</a>
	  </div>
  @endcan
</div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
  					@if(!isset($numero))
  						<th>Grupo</th>
  					@endif
					<th>Tema</th>
					<th>Fecha de Creación</th>
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
						<td><span class="badge badge-info">{{ $prePerfil->categoriaEstado->nombre_cat_sta }}</span>&nbsp;</td>
						<td>{{ $prePerfil->tipoTrabajo->nombre_cat_tpo_tra_gra}}</td>
						@can('prePerfil.edit')
							<td>
								<a class="btn btn-primary" href="{{route('prePerfil.edit',$prePerfil->id_pdg_ppe)}}"><i class="fa fa-pencil"></i></a>
							</td>
						@endcan
						@can('prePerfil.destroy')
							<td>
								{!! Form::open(['route'=>['prePerfil.destroy',$prePerfil->id_pdg_ppe],'method'=>'DELETE','class' => 'deleteButton']) !!}
							 		<div class="btn-group">
										<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
						@endcan

							<td>
								{!! Form::open(['route'=>['downloadPrePerfil'],'method'=>'POST']) !!}
							 		<div class="btn-group">
							 			{!!Form::hidden('archivo',$prePerfil->nombre_archivo_pdg_ppe,['class'=>'form-control'])!!}
										<button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
									</div>
								{!! Form:: close() !!}
							</td>
							@can('prePerfil.aprobar')
								<td>
									{!! Form::open(['route'=>['aprobarPreperfil'],'method'=>'POST','class'=>'aprobar']) !!}
								 		<div class="btn-group">
								 			{!!Form::hidden('idPrePerfil',$prePerfil->id_pdg_ppe,['class'=>'form-control'])!!}
											<button type="submit" class="btn btn-success"><i class="fa fa-check"></i></button>
										</div>
									{!! Form:: close() !!}
								</td>
							@endcan
							@can('prePerfil.rechazar')
								<td>
									{!! Form::open(['route'=>['rechazarPrePerfil'],'method'=>'POST','class'=>'rechazar']) !!}
								 		<div class="btn-group">
								 			{!!Form::hidden('idPrePerfil',$prePerfil->id_pdg_ppe,['class'=>'form-control'])!!}
											<button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i></button>
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