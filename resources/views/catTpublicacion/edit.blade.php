@extends('template')
@section('content')

    		<ol class="breadcrumb"  style="text-align: center; margin-top: 1em">
          <li class="breadcrumb-item">
            <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>         Catálogo de tipo de publicaciones</h5>
          </li>
          <li class="breadcrumb-item active">Actualizar Tipo</li>
        </ol>
    		<div class="panel-body">
      		{!! Form:: model($catTpublicacion,['route'=>['catTpublicacion.update',$catTpublicacion->id_cat_tpo_pub],'method'=>'PUT']) !!}
      			@include('catTpublicacion.forms.formCreate')
            <div class="row">
              <div class="form-group col-sm-6">
                {!!Form::submit('Actualizar',['class'=>'btn btn-primary'])!!}
              </div>
            </div>
  				</div>
  			{!! Form:: close() !!}
</div>
@stop