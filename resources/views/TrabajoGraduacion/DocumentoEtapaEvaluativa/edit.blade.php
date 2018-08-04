@extends('template')
@section('content')
<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
        <li class="breadcrumb-item">
          <h5>Actualizar Documento</h5>
        </li>
        <li class="breadcrumb-item active"><b>Etapa:</b> &nbsp;{{ $etapa->nombre_cat_eta_eva }}&nbsp;&nbsp;<b>Tipo de Documento:&nbsp;</b>{{ $tipoDocumento->nombre_pdg_tpo_doc }}</li>
          
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
        {!! Form:: model($documento,['route'=>['documento.update',$documento->id_pdg_doc],'method'=>'PUT','id'=>'formDocumento','files'=>'true','enctype'=>'multipart/form-data']) !!}
        <div class="row">
          <div class="col-sm-12">
            <p class="text-center">
            {{ $tipoDocumento->descripcion_pdg_tpo_doc}}
            </p>
          </div>
        </div>
          @include('TrabajoGraduacion.DocumentoEtapaEvaluativa.forms.formCreate')
        <div class="row">
          <div class="form-group col-sm-6">
            {!! Form::submit('Actualizar',['class'=>'btn btn-primary']) !!}
          </div>
        </div>
        </div> 
        {!! Form:: close() !!}
        
        
  </div>
   <div id="loader"></div> 
@stop
