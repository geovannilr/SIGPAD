@extends('template')
@section('content')
@if(Session::has('message'))
      <script type="text/javascript">
        $( document ).ready(function() {
          swal("", "{{Session::get('message')}}", "success");
      });
      </script>   
@endif
<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <h6>Trabajo de Graduación</h6>
          </li>
          <li class="breadcrumb-item active">Grupo {{$numero}}</li>
    </ol>
     <p class="text-center"> SISTEMA INFORMÁTICO PARA LA GESTIÓN DE PROCESOS ACADÉMICOS Y ADMINISTRATIVOS DE LA ESCUELA DE INGENIERÍA DE SISTEMAS INFORMÁTICOS DE LA UNIVERSIDAD DE EL SALVADOR</p>
      <br>
      <br>
      Progreso
      <div class="progress">
        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
      </div>
      <hr>
      <div class="row">
        <div class="col-sm-6">
              <div class="table-responsive">
                <table class="table table-hover table-striped">
                  <th colspan="2">Integrantes</th>
                  @foreach($estudiantes as $estudiante)
                  <tr>
                    <td>{{strtoupper($estudiante->carnet)}}</td>
                    <td>
                      {{$estudiante->nombre}}
                      @if($estudiante->lider == 1 )
                        <span class="badge badge-info">LIDER</span>
                      @endif
                    </td> 
                  </tr>
                  @endforeach
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
                        <td>{{$tri->nombre}}</td> 
                      </tr>
                    @endforeach
                  @endif
                </table>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-sm-12 text-center"><h3>Etapas Evaluativas</h3></div>
      </div>
      <br>
      <!-- Icon Cards-->
      <div class="row">
        @if($etapas == "NA")
          No se han registrado etapas evaluativas asociadas a ti tipo de trabajo de graduación, consulte al administrador
        @else
           @foreach ($etapas as $etapa)
              @if($etapa->nombre_cat_eta_eva == "Analisis y Diseño")
                <div class="col-xl-3 col-sm-6 mb-3">
                  <div class="card text-gray bg-danger o-hidden h-100">
                    <div class="card-body">
                      <div class="card-body-icon">
                        <i class="fa fa-mortar-board btn-danger"></i>
                      </div>
                      <div class="mr-5 btn-danger">{{$etapa->nombre_cat_eta_eva}}</div>
                    </div>
                    <a class="card-footer text-gray clearfix small z-1" href="{{route('etapaEvaluativa.show',$etapa->id_cat_eta_eva)}}">
                      <span class="float-left btn-danger">Ver Detalles</span>
                      <span class="float-right btn-danger">
                        <i class="fa fa-angle-right"></i>
                      </span>
                    </a>
                  </div>
              </div>
              @else
                <div class="col-xl-3 col-sm-6 mb-3">
                  <div class="card text-gray  o-hidden h-100">
                    <div class="card-body">
                      <div class="card-body-icon">
                        <i class="fa fa-mortar-board"></i>
                      </div>
                      <div class="mr-5">{{$etapa->nombre_cat_eta_eva}}</div>
                    </div>
                    <a class="card-footer text-gray clearfix small z-1" href="{{route('etapaEvaluativa.show',$etapa->id_cat_eta_eva)}}">
                      <span class="float-left">Ver detalles</span>
                      <span class="float-right">
                        <i class="fa fa-angle-right"></i>
                      </span>
                    </a>
                  </div>
                </div>
              @endif
          @endforeach
        @endif
      </div>
    </div>
@stop
