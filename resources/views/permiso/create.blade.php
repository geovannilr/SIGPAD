@extends('template')
@section('content')

 
<ol class="breadcrumb"  style="text-align: center; margin-top: 1em;z-index: 0" >
        <li class="breadcrumb-item">
          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>      PERMISO </h5>
        </li>
        <li class="breadcrumb-item active">Nuevo Registro  </li> 
</ol>

   <div class="row">
              <div class="col-md-6"></div>
              <div class="col-md-6 "> </div>
    </div>
 <!-- <div class="form-group col-sm-6 " >   </div> -->
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
