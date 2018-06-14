@extends('template')
@section('content')
<ol class="breadcrumb">
        <li class="breadcrumb-item">
          <h5>Pre-Perfil</h5>
        </li>
        <li class="breadcrumb-item active">Nuevo</li>
          
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
    		{!! Form:: open(['route'=>'prePerfil.store','method'=>'POST', 'id'=>'formPrePerfil','files'=>'true','enctype'=>'multipart/form-data']) !!}
    			@include('TrabajoGraduacion.PrePerfil.forms.formCreate')
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
