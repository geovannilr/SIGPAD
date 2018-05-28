@extends('template')
@section('content')
<ol class="breadcrumb">
        <li class="breadcrumb-item">
          <h5>USUARIO</h5>
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
    		{!! Form:: open(['route'=>'usuario.store','method'=>'POST']) !!}
    			@include('usuario.forms.formCreate')
        <div class="row">
          <div class="form-group col-sm-6">
            {!! Form::submit('Registrar',['class'=>'btn btn-primary']) !!}
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
