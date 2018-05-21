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
  var cantidadMinima = {{$cantidadMinima}};
 
@if (!isset($cards)) 
    //Me agrego como líder en el caso que no pertenezca a un grupo
      verificarGrupo('{{Auth::user()->user}}',0);
@endif
console.log(cantidadMinima);
</script>
<ol class="breadcrumb">
        <li class="breadcrumb-item">
          <h5>Trabajo de Graduación</h5>
        </li>
        <li class="breadcrumb-item active">Conformar Grupo
          @if(isset($estadoGrupo))
             @if($estadoGrupo == 2)
             <p class="badge badge-info card-text">Listo para Enviar</p>
             @else
              @if($estadoGrupo==7)
                <p class="badge badge-info card-text">Enviado para aprobación</p>
             @endif
          @endif
          @endif
        </li>
</ol>
  		<div class="panel-body">
        @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
        @endif
         @if (!isset($cards)) 
         {!! Form:: open(['route'=>'grupo.store','method'=>'POST']) !!}
             <div class="row">
            <div class="form-group col-sm-9">
              {!!Form::text('buscarEstudiante',null,['class'=>'form-control ','placeholder'=>'Búsqueda por Carnet','id'=>'inputBuscar'])!!}
            </div>
            <div class="form-group col-sm-3">
              {!! Form::button('Buscar',['class'=>'btn btn-primary','id'=>'buscarAlumno']) !!}
            </div>
        </div>
        @endif
        <div class="row" id="estudiantes">
          @if (isset($cards)) 
            {!!$cards!!}
          @endif
        </div><br><br><br><br>
        @if (!isset($cards)) 
          <div class="row">
          <div class="form-group col-sm-12">
            {!! Form::submit('Conformar Grupo',['class'=>'btn btn-primary']) !!}
          </div>
        </div>   
        @endif
         @if(isset($estadoGrupo))
          @if ($estadoGrupo == 2) 
         {!! Form:: open(['route'=>'enviarGrupo','method'=>'POST']) !!}
             <div class="row">
              <div class="form-group col-sm-12">
                {!!Form::hidden('idGrupo',$idGrupo)!!}
                {!! Form::submit('Enviar Grupo',['class'=>'btn btn-primary']) !!}
                
              </div>
            </div>
        </div>
        @endif
         @endif
         
			</div> 
			  {!! Form:: close() !!}

  </div>

@stop
