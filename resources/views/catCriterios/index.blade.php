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
        if(!confirm('Estas seguro que deseas eliminar este criterio?')){

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
                title: 'Listado de criterios'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de criterios'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de criterios'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de criterios'
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
	          <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Criterios</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('catCriterios.create')
    <div class="col-sm-3">
      <a class="btn " href="{{route('catCriterios.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo criterio</a>
    </div>
  @endcan
  </div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Nombre de criterio</th>
                    <th>Ponderación</th>
                    <th>Aspectos</th>

                    @can('catCriterios.destroy')
                    <th style="text-align: center;">Acciones</th>
                    @can('catCriterios.edit')

                    @endcan
                    @endcan

                </thead>
                <tbody>
                @foreach($catCriterios as $catCriterio)
					<tr>
						<td>{{ $catCriterio->nombre_cat_cri_eva}}</td>
                        <td>{{ $catCriterio->ponderacion_cat_cri_eva}}</td>
                        <td>{{ $catCriterio->catAspEva->nombre_pdg_asp}}</td>
                    @can('catCriterios.edit')
                            @can('catCriterios.destroy')
                          <td style="text-align: center;">
                             <div class="d-inline-block">
                              <a class="btn " style="background-color: #102359;color: white" href="{{route('catCriterios.edit',$catCriterio->id_cat_cri_eva)}}"><i class="fa fa-pencil"></i></a>
                             </div>
                              {!! Form::open(['route'=>['catCriterios.destroy',$catCriterio->id_cat_cri_eva],'method'=>'DELETE','class' => 'deleteButton']) !!}
                              <div class="d-inline-block">
                                  <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                              </div>
                              {!! Form:: close() !!}

                          </td>
                         @endcan
                            @endcan
					</tr>
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop