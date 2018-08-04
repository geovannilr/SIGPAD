@extends('template')
@section('content')
<ol class="breadcrumb"  style="text-align: center; margin-top: 1em" >
        <li class="breadcrumb-item">
          <h5>PERMISO </h5>
        </li>
        <li class="breadcrumb-item active">Nuevo Registro</li>
</ol>
 <div > <a href="{{ redirect()->getUrlGenerator()->previous() }}"><i class="fa fa-arrow-left"></i></a></div>
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
