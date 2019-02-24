@extends('template')

@section('content')
@if(Session::has('message'))
      <script type="text/javascript">
        $( document ).ready(function() {
          swal("", "{{Session::get('message')}}", "success");
      });
      </script>   
@endif
@if(Session::has('message-error'))
      <script type="text/javascript">
        $( document ).ready(function() {
          swal("", "{{Session::get('message-error')}}", "error");
      });
      </script>   
@endif
<script type="text/javascript">
  $( document ).ready(function() {
    
      $("#listTable").DataTable({
        language: {
                url: '../es-ar.json' //Ubicacion del archivo con el json del idioma.
        },
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de Estudiantes Retirados'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de Estudiantes Retirados'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de Estudiantes Retirados'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1]
                },
                title: 'Listado de Estudiantes Retirados'
            }


        ],
        
        order: [ 1, 'asc' ],
      });

      $( "#formCambiarRetirado" ).submit(function( event ) {
         event.preventDefault();
         swal({
              title: "Cambiar Alumno Retirado",
              text: "¿Está seguro que desea cambiar este alumno a disponible para crear un nuevo grupo de trabajo de graduación?", 
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
    <ol class="breadcrumb"  style="text-align: center; margin-top: 1em">
          <li class="breadcrumb-item">
            <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a> Alumnos Retirados</h5>
          </li>
          <li class="breadcrumb-item active">Listado</li>
    </ol>
    <br>
      <div class="table-responsive">
        <table class="table table-hover table-striped  display" id="listTable">

          <thead>
          <th>Carnet</th>
          <th>Nombre</th>
          <th>Cambiar Estado</th>
          </thead>
          <tbody>
          @foreach($estudiantes as $estudiante)
          <tr>
            <td>{{ $estudiante->carnet_gen_est }}</td>
            <td>{{ $estudiante->nombre_gen_est }}</td> 
            <td style="width:60px">
              {!! Form:: open(['route'=>'incidencias/cambiarRetirado','method'=>'POST' ,'id'=>'formCambiarRetirado']) !!}
                    <div class="btn-group">
                      <input type="hidden" name="carnet" value="{{ $estudiante->carnet_gen_est }}">
                      <button type="submit" class="btn" style="background-color:  #102359;color: white"><i class="fa fa-refresh"></i></button>
                    </div>
              {!! Form:: close() !!}
            </td>
          </tr>
        @endforeach 
        </tbody>
      </table>
     </div>
@stop