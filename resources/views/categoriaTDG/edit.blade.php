@extends('template')
@section('content')

    		<ol class="breadcrumb"  style="text-align: center; margin-top: 1em">
          <li class="breadcrumb-item">
            <h5><a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>         CATEGORIAS DE TDG</h5>
          </li>
          <li class="breadcrumb-item active">Actualizar Categorías de TDG</li>
        </ol>
    		<div class="panel-body">
      		{!! Form:: model($categoriaTDG,['route'=>['categoriaTDG.update',$categoriaTDG->id_cat_ctg_tra],'method'=>'PUT']) !!}
      			@include('categoriaTDG.forms.formCreate')
            <div class="row">
              <div class="form-group col-sm-6">
                {!!Form::submit('Actualizar',['class'=>'btn btn-primary'])!!}
              </div>
            </div>
  				</div>
  			{!! Form:: close() !!}
</div>
@stop