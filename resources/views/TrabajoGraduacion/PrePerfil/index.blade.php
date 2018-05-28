@extends('template')

@section('content')
@if(Session::has('message'))
  		<script type="text/javascript">
  			$( document ).ready(function() {
    			swal("", "{{Session::get('message')}}", "success");
			});
  		</script>		
@endif
<script type="text/javascript">
	$( document ).ready(function() {
		 $('.deleteButton').on('submit',function(e){
        if(!confirm('Estas seguro que deseas eliminar este Pre-Perfil?')){

              e.preventDefault();
        	}
      	});
		
    	$("#listTable").DataTable({
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                title: 'Listado de Pre-Perfiles'
            },
            {
                extend: 'pdfHtml5',
                title: 'Listado de Pre-Perfiles'
            },
             {
                extend: 'csvHtml5',
                title: 'Listado de Pre-Perfiles'
            },
            {
                extend: 'print',
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
	});
	function borrar(id) {
		var idUsuario=id;
		$("#modalBorrar").modal()
	}
	
	
</script>
		<ol class="breadcrumb">
	        <li class="breadcrumb-item">
	          <h5>Pre-Perfil</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado - GRUPO X</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('usuario.create')
	  <div class="col-sm-3">Nuevo 
	  	 <a class="btn btn-primary" href="{{route('prePerfil.create')}}"><i class="fa fa-plus"></i></a>
	  </div>
  @endcan
</div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Tema</th>
					<th>Fecha de Creación</th>
					<th>Estado</th>
					<th>Tipo</th>
					@can('usuario.edit')
						<th>Modificar</th>
					@endcan
					@can('usuario.destroy')
						<th>Eliminar</th>
					@endcan
					@can('usuario.destroy')
						<th>Descargar</th>
					@endcan
  				</thead>
  				<tbody>

  				@foreach($prePerfiles as $prePerfil)
  						<tr>
						<td>{{ $prePerfil->tema_pdg_ppe }}</td>
						<td>{{ date_format(date_create($prePerfil->fecha_creacion_pdg_ppe), 'd/m/Y H:i:s')}}</td>
						<td><span class="badge badge-info">{{ $prePerfil->id_cat_sta }}</span>&nbsp;</td>
						<td>{{ $prePerfil->id_cat_tpo_tra_gra}}</td>
						@can('usuario.edit')
							<td>
								<a class="btn btn-primary" href="{{route('prePerfil.edit',$prePerfil->id_pdg_ppe)}}"><i class="fa fa-pencil"></i></a>
							</td>
						@endcan
						@can('prePerfil.destroy')
							<td>
								{!! Form::open(['route'=>['prePerfil.destroy',$usuario->id],'method'=>'DELETE','class' => 'deleteButton']) !!}
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