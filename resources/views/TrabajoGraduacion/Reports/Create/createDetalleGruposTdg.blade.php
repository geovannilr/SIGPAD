@extends('template')
@section('content')


    <ol class="breadcrumb"  style="text-align: center; margin-top: 1em;z-index: 0" >
        <li class="breadcrumb-item">
            <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>  {{$title}} </h5>
        </li>
    </ol>
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
        {!! Form:: open(['route'=>'reportes/detalleGruposTdg','method'=>'POST','id'=>'formReporte','target'=>'_blank']) !!}
        <div class="row">
            <div class="form-group col-sm-4">
                {!! Form::label('Año de Inicio') !!}
                {!!Form::text('anio',null,['class'=>'form-control ','placeholder'=>'Seleccione el año','id'=>'datepicker','readonly'=>'true','required'=>'true'])  !!}
            </div>
            <div class="form-group col-sm-4">
                {!! Form::label('Estado de grupo') !!}
                <select class="form-control" name="estado">
                    <option value="0">Activos</option>
                    <option value="1">Finalizados</option>
                    <option value="2">Todos</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-6">
                {!! Form::button('Generar',['class'=>'btn btn-success btnEnviar','id'=>'gen']) !!}
                {!! Form::button('Descargar',['class'=>'btn btn-primary btnEnviar','id'=>'des']) !!}
                <input type="hidden" name="tipo" id="tipo">
            </div>
        </div>
    </div>
    {!! Form:: close() !!}
    </div>
    <script type="text/javascript">
        $( document ).ready(function() {
            $( ".btnEnviar" ).click(function() {
                console.log($(this)[0].id);
                if ($(this)[0].id == 'gen') {
                    $("#tipo").val(1);
                    console.log("Generar");
                }else if ($(this)[0].id  == 'des') {
                    console.log("Descargar");
                    $("#tipo").val(2);
                }
                $("#formReporte").submit();
            });
        });
    </script>
@stop
