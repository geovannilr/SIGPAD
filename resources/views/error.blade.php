@extends('template')
@section('content')
<div class="page-wrap d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <span class="display-1 d-block">404</span>
                <div class="mb-4 lead">La página a la que quiere acceder no esta disponible</div>
                <a href="{{route('inicio')}}" class="btn btn-link">Regresar a Inicio.</a>
            </div>
        </div>
    </div>
</div>
@stop