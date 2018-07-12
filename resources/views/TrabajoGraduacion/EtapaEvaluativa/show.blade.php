@extends('template')

@section('content')
@if(Session::has('message'))
      <script type="text/javascript">
        $( document ).ready(function() {
          swal("", "{{Session::get('message')}}}", "success");
      });
      </script>   
@endif
@if(Session::has('error'))
      <script type="text/javascript">
        $( document ).ready(function() {
          swal("", "{{Session::get('error')}}", "error");
      });
      </script>   
@endif
<script type="text/javascript">
  $( document ).ready(function() {
      $("table.display").DataTable({
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                title: 'Listado de Documentos de Etapa  {{$nombreEtapa}}'
            },
            {
                extend: 'pdfHtml5',
                title: 'Listado de Documentos de Etapa {{$nombreEtapa}}'
            },
             {
                extend: 'csvHtml5',
                title: 'Listado de Documentos de Etapa {{$nombreEtapa}}'
            },
            {
                extend: 'print',
                title: 'Listado de Documentos de Etapa {{$nombreEtapa}}'
            }


        ],
         responsive: {
            details: {
                type: 'column'
            }
        },
        order: [ 1, 'asc' ],
      });
      $(".aprobar").submit(function( event ) {
        event.preventDefault();
        var titulo;
        var mensaje;
          titulo ="Aprobar Pre-Perfil";
          mensaje="Estas seguro que quiere aprobar este Pre-Perfil?";
          swal({
              title: titulo,
              text: mensaje, 
              icon: "warning",
              buttons: true,
              successMode: true,
          })
          .then((aceptar) => {
            if (aceptar) {
              console.log("Aprobar PrePerfil");
              this.submit();
            } else {
              return;
            }
          });   
    });
    $(".rechazar").submit(function( event ) {
      event.preventDefault();
        var titulo;
        var mensaje;
          titulo ="Rechazar Pre-Perfil";
          mensaje="Estas seguro que quiere rechazar este Pre-Perfil?";
          swal({
              title: titulo,
              text: mensaje, 
              icon: "warning",
              buttons: true,
              successMode: true,
          })
          .then((aceptar) => {
            if (aceptar) {
              console.log("Rechazar PrePerfil");
              this.submit();
            } else {
              return;
            }
          });   
    });

    $(".deleteButton").submit(function( event ) {
      event.preventDefault();
        var titulo;
        var mensaje;
          titulo ="Eliminar Pre-Perfil";
          mensaje="Estas seguro que quiere eliminar este Pre-Perfil?";
          swal({
              title: titulo,
              text: mensaje, 
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
    //AGREGAMOS EL TOKEN PARA ENVIO DE FORMULARIO
    $(".formPost").append('<input name="_token" value="{{csrf_token()}}" type="hidden">');
    
  });
</script>
    <ol class="breadcrumb">
          <li class="breadcrumb-item">
            @if($nombreEtapa == "" )
              SIN DOCUMENTOS
            @else
              <h3>{{$nombreEtapa}} - </h3>
            @endif
            <li> <h3>&nbsp;{{$ponderacion}}</h3></li>
          </li>
    </ol>
  <!--<div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-3"></div>
   <div class="col-sm-3"></div>
  @can('prePerfil.create')
    @if($nombreEtapa != "" )
    <div class="col-sm-3">Nuevo 
       <a class="btn btn-primary" href="{{route('nuevoDocumento',$id)}}"><i class="fa fa-plus"></i></a>
    </div>
    @endif
  @endcan
</div> -->

    <br>
    {!!$bodyHtml!!}

@stop