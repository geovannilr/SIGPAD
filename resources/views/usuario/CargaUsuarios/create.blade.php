@extends('template')
@section('content')
<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
        <li class="breadcrumb-item">
          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Carga de usuarios SIGPAD</h5>
        </li>

</ol>
      <p>* IMPORTANTE: Asegurese que los usuarios que  ha agregado en el documento excel tengan todos sus atributos llenos.</p>
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
    		{!! Form:: open(['route'=>'guardarUsuarios','method'=>'POST', 'id'=>'formUsers','files'=>'true','enctype'=>'multipart/form-data']) !!}
    			@include('usuario.CargaUsuarios.forms.formCreate')
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
