@extends('template')
@section('content')
        {{ var_dump($roles)}}
    		<ol class="breadcrumb">
          <li class="breadcrumb-item">
            <h5>USUARIO</h5>
          </li>
          <li class="breadcrumb-item active">Actualizar registro</li>
        </ol>
    		<div class="panel-body">
      		{!! Form:: model($usuario,['route'=>['usuario.update',$usuario->id],'method'=>'PUT']) !!}
      			@include('usuario.forms.formCreate')
            <div class="row">
              <div class="form-group col-sm-6">
                {!!Form::submit('Actualizar',['class'=>'btn btn-primary'])!!}
              </div>
            </div>
  				</div>
  			{!! Form:: close() !!}
</div>
@stop