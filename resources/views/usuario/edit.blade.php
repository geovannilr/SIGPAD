@extends('template')
@section('content')
    		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
          <li class="breadcrumb-item">
            <h5>  <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     USUARIO</h5>
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
<script type="text/javascript">
  // run pre selected options
  $('#roles').multiSelect({
    selectableHeader: "<div class='custom-header'>Disponibles</div>",
    selectionHeader: "<div class='custom-header'>Seleccionados</div>"
    });
</script>
@stop