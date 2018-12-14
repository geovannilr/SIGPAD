@extends('template')
@section('content')

    		<ol class="breadcrumb"  style="text-align: center; margin-top: 1em">
          <li class="breadcrumb-item">
            <h5><a href="{{ route('listadoDocentes') }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Actualizar registro Docente</h5>
          </li>
          <li class="breadcrumb-item active"><b>{{ $docente->display_name}} - {{ $docente->carnet_pdg_dcn}}</b> </li>
        </ol>
        <br>
        @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
        @endif
    		<div class="panel-body">
      		{!! Form:: model($docente,['route'=>['actualizarDocente'],'method'=>'POST']) !!}
          <input type="hidden" name="docente" value="{{ $docente->id_pdg_dcn}}">
      			 <div class="row">
                <div class="col-sm-3 col-md-2 col-5">
                  <label style="font-weight:bold;">Cargo principal</label>
                </div>
                <div class="col-md-8 col-6">
                  <select name="cargoPrincipal" class="form-control" id="cargoPrincipal">
                     <option value="">Seleccione un cargo principal</option>
                        {!!$bodySelectPrincipal!!}
                  </select>
                </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-3 col-md-2 col-5">
                  <label style="font-weight:bold;">Cargo Secundario</label>
              </div>
                <div class="col-md-8 col-6">
                  <select name="cargoSegundario" class="form-control" id="cargoSegundario">
                     <option value="">--Sin cargo secundario--</option>
                     {!!$bodySelectSecundario!!}
                   </select>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-3 col-md-2 col-5">
                <label style="font-weight:bold;">Jornada</label>
              </div>
              <div class="col-md-8 col-6">
                <select name="jornada" class="form-control" id="jornada">
                   {!!$bodySelectJornada!!}
                </select>
              </div>
              </div>
              <br>
                @can('gestionDocente.edit')
            <div class="row">
              <div class="form-group col-sm-6">
                {!!Form::submit('Actualizar',['class'=>'btn btn-primary'])!!}
              </div>
            </div>
                @endcan
  				</div>
  			{!! Form:: close() !!}
</div>
@stop