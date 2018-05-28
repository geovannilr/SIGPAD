@extends('template')
@section('content')
<ol class="breadcrumb">
        <li class="breadcrumb-item">
          <h5>Pre-Perfil</h5>
        </li>
        <li class="breadcrumb-item active">Nuevo</li>
</ol>
  		<div class="panel-body">
        @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
        @endif
    		{!! Form:: open(['route'=>'prePerfil.store','method'=>'POST', 'files'=>'true','enctype'=>'multipart/form-data']) !!}
    			@include('TrabajoGraduacion.PrePerfil.forms.formCreate')
        <div class="row">
          <div class="form-group col-sm-6">
            {!! Form::submit('Registrar',['class'=>'btn btn-primary']) !!}
          </div>
        </div>
				</div> 
			  {!! Form:: close() !!}
  </div>
@stop
