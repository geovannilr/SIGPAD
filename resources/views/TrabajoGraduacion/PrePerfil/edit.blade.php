@extends('template')
@section('content')
    		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
          <li class="breadcrumb-item">
            <h5>Pre-Perfil</h5>
          </li>
          <li class="breadcrumb-item active">Actualizar registro</li>
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
      		{!! Form:: model($prePerfil,['route'=>['prePerfil.update',$prePerfil->id_pdg_ppe],'method'=>'PUT','files'=>'true','enctype'=>'multipart/form-data']) !!}
      			@include('TrabajoGraduacion.PrePerfil.forms.formCreate')
            <div class="row">
              <div class="form-group col-sm-6">
                {!!Form::submit('Actualizar',['class'=>'btn btn-primary'])!!}
              </div>
            </div>
  				</div>
  			{!! Form:: close() !!}
</div>
@stop