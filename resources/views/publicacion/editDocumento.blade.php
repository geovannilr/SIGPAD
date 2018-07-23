@extends('template')
@section('content')
<ol class="breadcrumb">
        <li class="breadcrumb-item">
          <h5>Actualizar Documento</h5>
        </li>
         <li class="breadcrumb-item active"><b>Publicacion:</b> &nbsp;{{ $publicacion->codigo_pub }}&nbsp; - {{ $publicacion->titulo_pub }}</li>
          
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
        {!! Form:: model($archivo,['route'=>['updateDocumentoPublicacion'],'method'=>'POST','id'=>'formDocumento','files'=>'true','enctype'=>'multipart/form-data']) !!}
        <div class="row">
        </div>
         <input type="hidden" name="archivo" value="{{ $archivo->id_pub_arc }}">
          @include('publicacion.forms.formCreate')
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
