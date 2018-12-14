@extends('template')
@section('content')
<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
          <li class="breadcrumb-item">
            <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Grupo {{ $numero }}</h5>
          </li>
          <li class="breadcrumb-item active">Cierre de Trabajo de Graduación</li>
    </ol>
  		<div class="panel-body" >
        <div class="row">
          <div class="mx-auto" id="loader" style="display: none;">
            <img src="{!!asset('img/loading.gif')!!}" class="img-responsive" id="imgLoading">
          </div>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
        @endif
        <p class="text-center">
          @if($tema!="NA")
          TEMA: <br><b>{{strtoupper($tema)}}</b>
          @else
            TEMA:<b>NO SE ENCONTRO TEMA PARA ESTE GRUPO DE TRABAJO DE GRADUACION</b>
          @endif
        </p>
        <div class="row">
          <div class="col-sm-6">
            <div class="table-responsive">
              <table class="table table-hover table-striped  display" id="listTable">
                <thead>
                 <th colspan="2">Integrantes</th>
                </thead>
                <tbody>
                 @if(isset($estudiantesGrupo))
                            @foreach($estudiantesGrupo as $estudiante)
                              <tr>
                                <td>{{strtoupper($estudiante->carnet)}}</td>
                                <td>
                                  {{$estudiante->Nombre}}
                                  @if($estudiante->Cargo == "Lider" )
                                    <span class="badge badge-info">LIDER</span>
                                  @endif
                                </td> 
                              </tr>
                        @endforeach
                  @endif
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="table-responsive">
                <table class="table table-hover table-striped">
                  <th>Tribunal Evaluador</th>
                  @if ($tribunal=="NA")
                    <tr><td>NO SE HA ASIGNADO UN TRIBUNAL EVALUADOR AL GRUPO</td></tr>
                  @else
                    @foreach($tribunal as $tri)
                      <tr>
                        <td>{{$tri->name}}
                            @if($tri->id_pdg_tri_rol == 1)
                                <span class="badge badge-danger">{{$tri->nombre_tri_rol}}</span>
                            @endif
                        </td>
                      </tr>
                    @endforeach
                  @endif
                </table>
          </div>
        </div>
      </div>
     <br>
    		{!! Form:: open(['route'=>'storeCierreTDG','method'=>'POST', 'id'=>'formCierre','files'=>'true','enctype'=>'multipart/form-data']) !!}
    			@include('TrabajoGraduacion.TrabajoDeGraduacion.Cierre.forms.formCreate')
          <input type="hidden" name="idGrupo" value="{{$idGrupo}}">
        <div class="row">
          <div class="form-group col-sm-6">
            {!! Form::submit('Enviar',['class'=>'btn btn-primary']) !!}
          </div>
        </div>
				</div>
			  {!! Form:: close() !!}


  </div>
   <div id="loader"></div>
@stop
