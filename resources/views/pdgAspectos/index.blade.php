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
        if(!confirm('Estas seguro que deseas eliminar este aspecto?')){

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
                    columns: [ 0, 1]
                },
                title: 'Listado de aspectos'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de aspectos'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de aspectos'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de aspectos'
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
	          <h5><a href="{{ route('catCatalogo.index') }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Aspectos</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('pdgAspectos.create')
    <div class="col-sm-3">
      <a class="btn " href="{{route('pdgAspectos.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo aspecto</a>
    </div>
  @endcan
  </div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Nombre de aspecto</th>
                    <th>Ponderación</th>
                    <th>Etapa Evaluativa</th>

                    @can('pdgAspectos.destroy')
                    <th style="text-align: center;">Acciones</th>
                    @can('pdgAspectos.edit')

                    @endcan
                    @endcan

                </thead>
                <tbody>
                @foreach($pdgAspectos as $pdgAspecto)
					<tr>
						<td>{{ $pdgAspecto->nombre_pdg_asp}}</td>
                        <td>{{ $pdgAspecto->ponderacion_pdg_asp}}</td>
                        <td>{{ $pdgAspecto->catEtaEva->nombre_cat_eta_eva}}</td>
                        <td style="width: 160px">
                            <div class="row">
                                @can('pdgAspectos.edit')
                                    <div class="col-6">
                                        <a class="btn " style="background-color: #102359;color: white" href="{{route('pdgAspectos.edit',$pdgAspecto->id_pdg_asp)}}"><i class="fa fa-pencil"></i></a>
                                    </div>
                                @endcan
                                    @can('pdgAspectos.destroy')
                                    <div class="col-6">
                                        {!! Form::open(['route'=>['pdgAspectos.destroy',$pdgAspecto->id_pdg_asp],'method'=>'DELETE','class' => 'deleteButton']) !!}
                                        <div class="d-inline-block">
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