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
  var valorActual = -1;
	$( document ).ready(function() {
		 $('.deleteButton').on('submit',function(e){
        if(!confirm('Estas seguro que deseas eliminar este Permiso?')){

              e.preventDefault();
        	}
      	});
     $( ".roles" ).click(function() {
        //console.log($(this).val());
        valorActual = $(this).val();

      });
     $( ".roles" ).change(function() {
      var contador = 0;
        $('.roles').each(function(){
          if (parseInt($(this).val()) == 1 ){
            contador++;
          }

          //console.log($(this).val());
        });
        if (contador>1){
          swal("", "No puede seleccionar más de un líder", "error");
          
          $(this).val(valorActual);
          return;
        }

      });
     $( "#formEditMiembro" ).submit(function( event ) {
      var contador = 0;
      var contadorRetirados = 0 ;
        $('.roles').each(function(){
          if (parseInt($(this).val()) == 1 ){
            contador++;
          }else if (parseInt($(this).val()) == 2 ){
            contadorRetirados++;
          }

          //console.log($(this).val());
        });
        if (contador == 0){

          swal("", "Debe seleccionar al menos un líder", "error");
          event.preventDefault();
          
        }else if (contadorRetirados > 0) {
          var textMsj = "";
          if (contadorRetirados == 1) {
            textMsj = "Se hará el retiro de  "+contadorRetirados + " Estudiante del grupo , Está seguro que desea guardar los cambios?,tenga en cuenta que ESTA ACCION NO PODRA DESHACERCE";
          }else {
            textMsj = "Se hará el retiro de  "+contadorRetirados + " Estudiantes del grupo , Está seguro que desea guardar los cambios?, tenga en cuenta que ESTA ACCION NO PODRA DESHACERCE";
          }
          event.preventDefault();
          swal({
              title: "Actualización de Roles",
              text: textMsj , 
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
            <td>{{$estudiante->carnet}}</td>
            <td>{{$estudiante->Nombre}}</td>
            <td>
              
              @if($estudiante->Cargo == 'RETIRADO')
                <p class="text-danger"><b>{{$estudiante->Cargo}}</b></p>
              @else
                  <input type="hidden" name="carnets[]" value="{{$estudiante->carnet}}">
                  <select class="form-control roles" name="{{$estudiante->carnet}}">
                 @if($estudiante->Cargo == 'Lider')
                   <option value="1" selected="selected">Líder</option>
                   <option value="0"> Miembro</option>
                   <option value="2">Retirado</option>
                 @else
                   @if($estudiante->Cargo == 'Miembro')
                     <option value="1">Lider</option>
                     <option value="0" selected="selected"> Miembro</option>
                     <option value="2">Retirado</option>
                   @endif
                 @endif
                </select>
              @endif
              
            </td>
          </tr>  
          @endforeach
				</tbody>
			</table>
      {!! Form::submit('Actualizar',['class'=>'btn btn-primary']) !!}
      {!! Form:: close() !!}
	   </div>
@stop