@extends('template')
@section('content')
<ol class="breadcrumb">
        <li class="breadcrumb-item">
          <h5>PERMISO</h5>
        </li>
        <li class="breadcrumb-item active">Nuevo Registro</li>
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
    		{!! Form:: open(['route'=>'permiso.store','method'=>'POST']) !!}
    			@include('permiso.forms.formCreate')
        <div class="row">
          <div class="form-group col-sm-6">
            {!! Form::submit('Registrar',['class'=>'btn btn-primary']) !!}
          </div>
        </div>
				</div> 
			  {!! Form:: close() !!}
  </div>

@stop
