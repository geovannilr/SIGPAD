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
        if(!confirm('Estas seguro que deseas eliminar esta Etapa Evaluativa?')){

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
                    columns: [ 0, 1, 2,4,5]
                },
                title: 'Listado de Etapa evaluativa'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2,4,5]
                },
                title: 'Listado de Etapa evaluativa'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2,4,5]
                },
                title: 'Listado de Etapa evaluativa'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1, 2,4,5]
                },
                title: 'Listado de Etapa evaluativa'
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
	          <h5><a href="{{ route('catCatalogo.index') }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Etapa Evaluativa</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('etapaEvaluativa.create')
    <div class="col-sm-3">
      <a class="btn " href="{{route('etapaEvaluativa.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nueva Etapa evaluativa</a>
    </div>
  @endcan
  </div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>

					<th>Nombre Etapa evaluativa</th>
          <th>Tipo Trabajo de Graduación</th>
                    <th>Ponderación</th>
                    <th>Año</th>
                    <th>Nota Grupal</th>
                    <th>Orden</th>
                    @can('etapaEvaluativa.edit')
                    <th style="text-align: center;">Modificar</th>
                    @endcan
                   {{-- @can('etapaEvaluativa.destroy')
					<th>Eliminar</th>
                    @endcan--}}
  				</thead>
  				<tbody>
  				@foreach($etapaEvaluativa as $etapaEvaluativ)
          @if(!empty($etapaEvaluativ->relEtapaTrabajo->trabajos->nombre_cat_tpo_tra_gra))
    					<tr>
                 

        						<td>{{ $etapaEvaluativ->nombre_cat_eta_eva}}</td>

                    <td>{{ $etapaEvaluativ->relEtapaTrabajo->trabajos->nombre_cat_tpo_tra_gra}}</td>
                                <td>{{ $etapaEvaluativ->ponderacion_cat_eta_eva}}</td>
                                <td>{{ $etapaEvaluativ->anio_cat_eta_eva}}</td>
                                <td><?php
                                    $badge = $etapaEvaluativ->notagrupal_cat_eta_eva==1?'<span class="badge badge-info">SI</span>':'<span class="badge badge-danger">NO</span>';
                                    echo $badge?>
                                </td>
                            <td>{{ $etapaEvaluativ->relEtapaTrabajo->orden_eta_eva}}</td>
                            @can('etapaEvaluativa.edit')
                                  <td style="text-align: center;">
          			            	<a class="btn " style="background-color:  #102359;color: white" href="{{route('etapaEvaluativa.edit',$etapaEvaluativ->id_cat_eta_eva)}}"><i class="fa fa-pencil"></i></a>
                                  </td>
                                 @endcan
                            {{--@can('etapaEvaluativa.destroy')
      						<td style="text-align: center;">
      							{!! Form::open(['route'=>['etapaEvaluativa.destroy',$etapaEvaluativ->id_cat_eta_eva],'method'=>'DELETE','class' => 'deleteButton']) !!}
      						 		<div class="btn-group">
      									<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
      								</div>
      							{!! Form:: close() !!}
      						</td>
                            @endcan--}}
                            
    					</tr>
           @endif 
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop