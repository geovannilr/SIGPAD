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
            language: {
                url: 'es-ar.json' //Ubicacion del archivo con el json del idioma.
            },
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2]
                },
                title: 'Listado de tipo Documento'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2]
                },
                title: 'Listado de tipo Documento'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2]
                },
                title: 'Listado de tipo Documento'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1, 2]
                },
                title: 'Listado de tipo Documento'
            }


        ],
         responsive: {
            details: {
                type: 'column'
            }
        },
        order: [ 0, 'asc' ],
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
  @can('tipoDocumento.create')
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
                    <th style="text-align: center;">Acciones</th>
                    @endcan
                    @can('tipoDocumento.destroy')
                    @endcan
  				</thead>
  				<tbody>
  				@foreach($tipoDocumento as $tipoDocument)
					<tr>
						<td>{{ $tipoDocument->nombre_pdg_tpo_doc }}</td>
                        <td>{{ $tipoDocument->descripcion_pdg_tpo_doc }}</td>
						<td>{{ $tipoDocument->anio_cat_pdg_tpo_doc }}</td>
                        <td style="width: 160px">
                            <div class="row">
                                @can('tipoDocumento.edit')
                                    <div class="col-6">
                                        <a class="btn " style="background-color:  #102359;color: white" href="{{route('tipoDocumento.edit',$tipoDocument->id_cat_tpo_doc)}}"><i class="fa fa-pencil"></i></a>
                                    </div>
                                @endcan
                                    @can('tipoDocumento.destroy')
                                    <div class="col-6">
                                        {!! Form::open(['route'=>['tipoDocumento.destroy',$tipoDocument->id_cat_tpo_doc],'method'=>'DELETE','class' => 'deleteButton']) !!}
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        </div>
                                        {!! Form:: close() !!}
                                    </div>
                                @endcan
                            </div>
                        </td>
					</tr>
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop