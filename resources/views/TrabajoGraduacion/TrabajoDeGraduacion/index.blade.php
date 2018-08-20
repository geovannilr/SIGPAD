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
      <ol class="breadcrumb" style="text-align: center; margin-top: 1em">
          <li class="breadcrumb-item">
            <h5>  <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Trabajo de Graduación</h5>
          </li>
          <li class="breadcrumb-item active">Grupo {{$numero}}</li>
    </ol>
    <h3 class="text-center">{{strtoupper($tema->tema_pdg_tra_gra)}}</h3>
      <br>
      <br>
      Progreso
      <div class="progress">
        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 20%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">11%</div>
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
                  <div class="card text-gray  o-hidden h-100" style="background-color: #DF1D20">
                    <div class="card-body">
                      <div class="card-body-icon">
                        <i class="fa fa-mortar-board"></i>
                      </div>
                      <div class="mr-5 text-white" >{{$etapa->nombre_cat_eta_eva}}</div>
                    </div>
                    <a class="card-footer text-gray clearfix small z-1" href="{{route('etapaEvaluativa.show',$etapa->id_cat_eta_eva)}}">
                      <span class="float-left text-white ">Ver Detalles</span>
                      <span class="float-right text-white">
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
