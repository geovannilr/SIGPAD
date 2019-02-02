@extends('template')

@section('content')

    <ol class="breadcrumb" style="text-align: center; margin-top: 1em">
        <li class="breadcrumb-item">
            <h5> <a href="{{url('/publicacion')}}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Publicaciones</h5>
        </li>
        <li class="breadcrumb-item active">Resultado de carga</li>
    </ol>
    <br>
    <div class="table-responsive">
        <table class="table table-hover table-striped  display" id="listTable">

            <thead>
            <th>Grupo</th>
            <th>Resultado</th>
            </thead>
            <tbody>
            @foreach($salida as $publicacion)
                <tr>
                    <td>
                        @if($publicacion[1])
                            <span class="badge badge-success">{{$publicacion[0]}}</span>
                        @else
                            <span class="badge badge-warning">{{$publicacion[0]}}</span>
                        @endif
                    </td>
                    <td>{{$publicacion[2]}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop