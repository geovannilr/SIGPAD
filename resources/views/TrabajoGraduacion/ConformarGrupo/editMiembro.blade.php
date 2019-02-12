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
        if(!confirm('Estas seguro que deseas eliminar este Permiso?')){

              e.preventDefault();
        	}
      	});
	});
	
</script>
		<ol class="breadcrumb"  style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>   Grupo {{$nombre}}</h5>
	        </li>
	        <li class="breadcrumb-item active">Actualizar Rol de Miembros de Grupo</li>
		</ol>
		 

		<br>
  		<div class="table-responsive">
        {!! Form:: open(['route'=>'updateRolMiembro','method'=>'POST', 'id'=>'formEditMiembro']) !!}
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
          <th>Carnet</th>
					<th>Nombre</th>
          <th>Rol</th>
  				</thead>
  				<tbody>
  				@foreach($resultado as $estudiante)
          <tr>
            <input type="hidden" name="carnets[]" value="{{$estudiante->carnet}}">
            <td>{{$estudiante->carnet}}</td>
            <td>{{$estudiante->Nombre}}</td>
            <td>
              <select class="form-control" name="{{$estudiante->carnet}}">
               @if($estudiante->Cargo == 'Lider')
                 <option value="1" selected="selected">Líder</option>
                 <option value="0"> Miembro</option>
                 <option value="2">Retirado</option>
               @else
                 @if($estudiante->Cargo == 'Miembro')
                   <option value="1">Lider</option>
                   <option value="0" selected="selected"> Miembro</option>
                   <option value="2">Retirado</option>
                 @else
                   <option value="1">Lider</option>
                   <option value="0" > Miembro</option>
                   <option value="2" selected="selected">Retirado</option>  
                 @endif
               
               @endif
              </select>
            </td>
          </tr>  
          @endforeach
				</tbody>
			</table>
      {!! Form::submit('Actualizar',['class'=>'btn btn-primary']) !!}
      {!! Form:: close() !!}
	   </div>
@stop