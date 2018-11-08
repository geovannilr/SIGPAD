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
        order: [ 2, 'asc' ],
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
          titulo ="Eliminar Documento de {{$nombreEtapa}}";
          mensaje="Estas seguro que quiere eliminar este Documento?";
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
      <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a> </h5>
          <li class="breadcrumb-item">
            @if($nombreEtapa == "" )
              SIN DOCUMENTOS
            @else
              <h3>{{$nombreEtapa}} - </h3>
            @endif
            <li> <h3>&nbsp;{{$ponderacion}}</h3></li>
            &nbsp;&nbsp;&nbsp; 
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
<div class="row">
  <div class="col-md-6">
    <div class="card text-black bg-secundary o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-cog"></i>
                  </div>
                  <div class="mr-5">Configuracion</div>
                </div>
                <a class="card-footer text-black clearfix small z-1" data-toggle="modal" data-target="#modalConfig"  href="#">
                  <span class="float-left">Cambiar</span>
                  <span class="float-right">
                   <i class="fa fa-angle-right"></i>
                  </span>
                </a>
              </div>
  </div>
   <div class="col-md-6">
    <div class="card text-black bg-secundary o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-list-alt"></i>
                  </div>
                  <div class="mr-5">Notas</div>
                </div>
                <a class="card-footer text-black clearfix small z-1" href="{{route('createNotas',$id)}}">
                  <span class="float-left">Ingresar</span>
                  <span class="float-right">
                   <i class="fa fa-angle-right"></i>
                  </span>
                </a>
              </div>
  </div>
</div>
    <br>
    {!!$bodyHtml!!}
    <!-- Button trigger modal -->
<!-- Modal -->
<div class="modal fade" id="modalConfig" tabindex="-1" role="dialog" aria-labelledby="modalConfig" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header breadcrumb">
        <h5>Configuración de Etapa Evaluativa</h5>
         
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form:: open(['route'=>'enviarConfigEtapa','method'=>'POST', 'id'=>'formConfig']) !!}
          <p class="text-danger">Tenga en cuenta que una vez definido la cantidad de entregables no puede cambiarlos durante todo el proceso de trabajo de graduación</p>
          <label>Cantidad de Entregables de esta etapa</label>
          <select class="form-control" name="cantidadEntregables">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>
          <input type="hidden" name="idEtapa" value="{{$id}}">
           <input type="hidden" name="idGrupo" value="{{$idGrupo}}">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
      {!! Form:: close() !!}
    </div>
  </div>
</div>

@stop