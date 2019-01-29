@extends('template')
@section('content')
    <script type="text/javascript">
        $('.documentoPublicaciones').change(function (){
            var sizeByte = this.files[0].size;
            var siezekiloByte = parseInt(sizeByte / 1024);
            var nombre = $(this).val();
            var extension = nombre.substring(nombre.lastIndexOf('.') + 1).toLowerCase();
            if(siezekiloByte > 25600){
                swal("", "El tamaño documento no debe ser mayor a 25 MB", "error");
                $(this).val('');
            }else {
                if(!(extension =='xlsx' || extension =='xls')){
                    swal("", "Solo se permiten documentos de formato de archivo de Microsfot Excel", "error");
                    $(this).val('');
                }

            }
        });
        function descargarPlantilla(){
            $("#frmDownload").submit();
        }
    </script>
    <ol class="breadcrumb" style="text-align: center; margin-top: 1em">
        <li class="breadcrumb-item">
            <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Carga de Publicaciones</h5>
        </li>

    </ol>
    <h3>
        <b>&nbsp;&nbsp;Indicaciones Generales</b><br>
    </h3>
    <div class="row">
        <div class="form-group col-sm-12">
            &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="descargarPlantilla();">Descargue</a> y rellene la plantilla con la información requerida.<br>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;<b>IMPORTANTE: </b><i>Asegurese de completar la infomación en cada una de las hojas de la plantilla.</i></p>
            <br><br>
        </div>
    </div>
    <br>
    <div class="panel-body" >
        <div class="row">
            <div class="mx-auto" id="loader" style="display: none;">
                <img src="{!!asset('img/loading.gif')!!}" class="img-responsive" id="imgLoading">
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {!! Form:: open(['route'=>'storePublicaciones','method'=>'POST', 'id'=>'formPublicaciones','files'=>'true','enctype'=>'multipart/form-data']) !!}
        <div class="form-group col-sm-12">
            <label>Listado de Publicaciones de trabajos de graduación</label>
            {!!Form::file('documentoPublicaciones',['class'=>'form-control documentoPublicaciones','accept'=>"xlsx"])  !!}
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                {!! Form::submit('Enviar',['class'=>'btn btn-primary']) !!}
            </div>
        </div>
        {!! Form:: close() !!}
    </div>

    <div id="divFrmDownload" style="display: none;">
        {!! Form::open(['route'=>['plantillaPublicaciones'],'method'=>'POST', 'id'=>'frmDownload', 'target' => '_blank']) !!}
        <div class="btn-group">
            <button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
        </div>
        {!! Form:: close() !!}
    </div>
@stop
