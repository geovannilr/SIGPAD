@extends('template')
@section('content')
<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
        <li class="breadcrumb-item">
          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Ingreso de Notas de Etapa</h5>
        </li>
        <li class="breadcrumb-item active"><b>Etapa:</b> &nbsp;{{ $etapa->nombre_cat_eta_eva }}</li>
          
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
        <div class="row">
          <div class="form-group col-sm-1">
              Descargar
            {!! Form::open(['route'=>['downloadDocumentoPublicacion'],'method'=>'POST']) !!}
                  <div class="btn-group">
                    <button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
                  </div>
                {!! Form:: close() !!}
          </div>
          <div class="form-group col-sm-6">
            <p><h5>INDICACIONES GENERALES</h5></p>
            <ul>
              <li>Descargar el formato de subida de notas en el caso que no lo tenga.</li>
              <li>Ingrese una nota por cada sub-etapa que usted configuro para dicha etapa anteriormente.</li>
              <li>Se asignará una nota promedio de todas las sub-etapas como la nota del alumno.</li>
              <li>Verificar que todos los campos solicitados esten llenos correctamente.</li>
            </ul>
          </div>
          
        </div>
     
    		{!! Form:: open(['route'=>'enviarNotas','method'=>'POST', 'id'=>'formNotas','files'=>'true','enctype'=>'multipart/form-data']) !!}
    			@include('TrabajoGraduacion.NotaEtapaEvaluativa.forms.formCreate')
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
