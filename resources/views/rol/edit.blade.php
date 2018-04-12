@extends('template')
@section('content')

    		<ol class="breadcrumb">
          <li class="breadcrumb-item">
            <h5>ROL</h5>
          </li>
          <li class="breadcrumb-item active">Actualizar registro</li>
        </ol>
    		<div class="panel-body">
      		{!! Form:: model($rol,['route'=>['rol.update',$rol->id],'method'=>'PUT']) !!}
      			@include('rol.forms.formCreate')
            <div class="row">
              <div class="form-group col-sm-6">
                {!!Form::submit('Actualizar',['class'=>'btn btn-primary'])!!}
              </div>
            </div>
  				</div>
  			{!! Form:: close() !!}
</div>
@stop