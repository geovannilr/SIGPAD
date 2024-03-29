@extends('template')
@section('content')
    <script type="text/javascript">
        function descargarPlantilla(){
            $("#frmDownload").submit();
        }
    </script>
<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
        <li class="breadcrumb-item">
          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Carga de Perfil Docente</h5>
        </li>

</ol>
<h3>
    <b>&nbsp;&nbsp;Indicaciones Generales</b><br>
</h3>
    <div class="row">
        <div class="form-group col-sm-12">
            &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="descargarPlantilla();">Descargue</a> y rellene la plantilla con la información requerida.<br>&nbsp;&nbsp;&nbsp;&nbsp;Tenga en cuenta que subir el archivo automáticamente sustituye la información de una carga previa.
            <br><br>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;<b>IMPORTANTE:</b><i> Indicaciones Adicionales incluídas en la plantilla!</i></p>
        </div>
    </div>
<br>
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
            {!! Form:: open(['route'=>'guardarPerfilDocente','method'=>'POST', 'id'=>'formUsers','files'=>'true','enctype'=>'multipart/form-data']) !!}
            @include('PerfilDocente.forms.formCreate')
        <div class="row">
          <div class="form-group col-sm-6">
            {!! Form::submit('Enviar',['class'=>'btn btn-primary']) !!}
          </div>
        </div>
				</div>
			  {!! Form:: close() !!}


  </div>
   <div id="loader"></div>
<div id="divFrmDownload" style="display: none;">
{!! Form::open(['route'=>['plantillaPerfilDocente'],'method'=>'POST', 'id'=>'frmDownload', 'target' => '_blank']) !!}
    <div class="btn-group">
        <button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
    </div>
{!! Form:: close() !!}
</div>
@stop
