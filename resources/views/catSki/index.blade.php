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
        if(!confirm('Estas seguro que deseas eliminar este Skill?')){

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
                    columns: [ 0]
                },
                title: 'Listado de Skills'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de Skills'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de Skills'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de Skills'
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
	          <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Skills</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado</li>
		</ol>
		 <div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('permiso.create')
    <div class="col-sm-3">
      <a class="btn " href="{{route('catSki.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo Skill</a>
    </div>
  @endcan
  </div> 

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Nombre Skill</th>
                    <th>Tipo Skill</th>


                    @can('catSki.edit')
                    <th style="text-align: center;">Acciones</th>
                    @endcan
                   @can('catEstado.destroy')
                    @endcan
  				</thead>
  				<tbody>
                @foreach($catSki as $catSk)
					<tr>
						<td>{{ $catSk->nombre_cat_ski}}</td>
                        <td>{{ $catSk->tipoSki->descripcion_tpo_ski}}</td>
                        <td style="width: 160px">
                            <div class="row">
                                @can('catSki.edit')
                                    <div class="col-6">
                                        <a class="btn " style="background-color:  #102359;color: white" href="{{route('catSki.edit',$catSk->id_cat_ski)}}"><i class="fa fa-pencil"></i></a>
                                    </div>
                                @endcan
                                    @can('catSki.destroy')
                                    <div class="col-6">
                                        {!! Form::open(['route'=>['catSki.destroy',$catSk->id_cat_ski],'method'=>'DELETE','class' => 'deleteButton']) !!}
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