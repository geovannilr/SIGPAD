@extends('template')
@section('content')
<div class="page-wrap d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <span class="display-1 d-block">{{$titulo}}</span>
                <div class="mb-4 lead">{{$mensaje}}</div>
                <a href="{{route('publicacion.index')}}" class="btn btn-link">Regresar a publicaciones TDG.</a>
            </div>
        </div>
    </div>
</div>
@stop