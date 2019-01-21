@extends('template')
@section('content')
@if(Session::has('message'))
      <script type="text/javascript">
        $( document ).ready(function() {
          swal("", "{{Session::get('message')}}", "success");
      });
      </script>   
@endif
@if(Session::has('message-warning'))
      <script type="text/javascript">
        $( document ).ready(function() {
          swal("", "{{Session::get('message-warning')}}", "warning");
      });
      </script>   
@endif
<script type="text/javascript">
    $(function () {
        colorProgress();
    });
    function colorProgress() {
        var val = parseInt({{$avance}});
        var bgcolor = "";
        if(val <= 25) bgcolor = "bg-warning";
        else if (val > 75) bgcolor = "bg-success";
        else bgcolor = "";
        $("#progressBarDiv").addClass(bgcolor);
    }
</script>
<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb" style="text-align: center; margin-top: 1em">
          <li class="breadcrumb-item">
            <h5>  <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Trabajo de Graduación</h5>
          </li>
          <li class="breadcrumb-item active">Grupo {{$numero}} </li>
    </ol>
    <h3 class="text-center">{{strtoupper($tema)}}</h3>
      <br>
      <br>
      Progreso
      <div class="progress">
        <div id="progressBarDiv" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{$avance}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
            {{$avance>100.00?100.00:$avance}}%
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-sm-6">
              <div class="table-responsive">
                <table class="table table-hover table-striped">
                  <th colspan="2">Integrantes</th>
                  @if(isset($estudiantes))
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
                  @else
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
                  @endif
                  
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
                            @else
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
          No se han registrado etapas evaluativas asociadas a tu tipo de trabajo de graduación, consulte al administrador
        @else
           @foreach ($etapas as $etapa)
              @if($etapa->id_cat_eta_eva == $actual)
                <div class="col-xl-3 col-sm-6 mb-3">
                  <div class="card text-gray  o-hidden h-100" style="background-color: #DF1D20">
                    <div class="card-body">
                      <div class="card-body-icon">
                        <i class="fa fa-mortar-board"></i>
                      </div>
                      <div class="mr-5 text-white" >{{$etapa->nombre_cat_eta_eva}}</div>
                    </div>
                    <a class="card-footer text-gray clearfix small z-1" href="{{url("/")."/detalleEtapa/".$etapa->id_cat_eta_eva."/".$idGrupo}}">
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
                    <a class="card-footer text-gray clearfix small z-1" href="{{url("/")."/detalleEtapa/".$etapa->id_cat_eta_eva."/".$idGrupo}}">
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
    @if($actual == 999)
      <div class="row">
        <div class="col-sm-4">
        </div>

          <div class="col-sm-4">
            <div class="card text-gray  o-hidden h-100" style="{{!isset($idPublicacion[0]->id_pub)?'background-color: #DF1D20':''}}" >
                    <div class="card-body">
                      <div class="card-body-icon">
                        <i class="fa fa-flag-checkered"></i>
                      </div>
                      <div class="mr-5 {{!isset($idPublicacion[0]->id_pub)?'text-white':''}}">Cierre de Trabajo de graduación</div>
                    </div>
                @if(isset($idPublicacion[0]->id_pub))
                    <a class="card-footer text-gray clearfix small z-1" href="{{url("/").'/publicacion/'.$idPublicacion[0]->id_pub}}">
                        <span class="float-left">Ver Detalles</span>
                        <span class="float-right">
                            <i class="fa fa-angle-right"></i>
                            </span>
                    </a>
                @else
                    @if(Auth::user()->isRole('estudiante'))
                        <a class="card-footer text-gray clearfix small z-1" href="{{url("/")."/cierreTDG"}}">
                            <span class="float-left">Realizar</span>
                            <span class="float-right">
                                <i class="fa fa-angle-right"></i>
                                </span>
                        </a>
                    @else
                        <a class="card-footer text-gray clearfix small z-1"  data-toggle="modal" data-target="#modalDetalleCierre">
                            <span class="float-left">Ver Detalles</span>
                            <span class="float-right">
                            <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    @endif
                @endif
                  </div>
          </div>
          <div class="col-sm-4">
        </div>
      </div>
    @endif
    </div>
<div class="modal fade" id="modalDetalleCierre" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cierre Trabajo de Graduación</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                El grupo todavía no realiza acciones para el Cierre de Trabajo de Graduación, deben subir su tomo para la aprobación y publicación correspondiente.
                <p class="text-danger">Esta acción es obligatoria para dar por finalizado el proceso de Trabajo de Graduación</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Continuar</button>
            </div>
        </div>
    </div>
</div>
@stop
