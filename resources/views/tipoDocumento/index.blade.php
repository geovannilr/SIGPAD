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
        if(!confirm('Estas seguro que deseas eliminar este tipo Documento?')){

              e.preventDefault();
        	}
      	});
		
    	$("#listTable").DataTable({
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de tipo Documento'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de tipo Documento'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de tipo Documento'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1, 2, 3]
                },
                title: 'Listado de tipo Documento'
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
	
</script>
		<ol class="breadcrumb"  style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Tipo Documento</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('permiso.create')
    <div class="col-sm-3">
      <a class="btn " href="{{route('tipoDocumento.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo Tipo Documento</a>
    </div>
  @endcan
  </div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Nombre Tipo Documento</th>
                    <th>Descipción</th>
                    <th>Año</th>
                    @can('tipoDocumento.edit')
                    <th>Modificar</th>
                    @endcan
                    @can('tipoDocumento.destroy')
					<th>Eliminar</th>
                    @endcan
  				</thead>
  				<tbody>
  				@foreach($tipoDocumento as $tipoDocument)
					<tr>
						<td>{{ $tipoDocument->nombre_pdg_tpo_doc }}</td>
                        <td>{{ $tipoDocument->descripcion_pdg_tpo_doc }}</td>
						<td>{{ $tipoDocument->anio_cat_pdg_tpo_doc }}</td>
                         @can('tipoDocumento.edit')
                          <td style="text-align: center;">
  			            	<a class="btn " style="background-color:  #102359;color: white" href="{{route('tipoDocumento.edit',$tipoDocument->id_cat_tpo_doc)}}"><i class="fa fa-pencil"></i></a>
                          </td>
                         @endcan
                        @can('tipoDocumento.destroy')
  						<td style="text-align: center;">
  							{!! Form::open(['route'=>['tipoDocumento.destroy',$tipoDocument->id_cat_tpo_doc],'method'=>'DELETE','class' => 'deleteButton']) !!}
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