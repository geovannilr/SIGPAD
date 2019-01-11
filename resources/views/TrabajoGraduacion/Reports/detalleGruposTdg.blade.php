@extends('pdfTemplate')

@section('content')
<body>
	<center>
	<h5>{{$title}}</h6>
	 
	</center>
    <table class="table table-bordered" style="font-size: 12px;">
        <tr>
            <td><b>Grupo</b></td>
            <td><b>Estado</b></td>
            <td><b>Integrantes</b></td>
        </tr>
        {!!$currId = 0;!!}
          @foreach($datos as $dato)
              <tr>
                  @if($currId !=$dato->idGru )
                      <td rowspan="{{$dato->cantGru}}">
                          {{$dato->numGrupo}}
                      </td>
                      <td rowspan="{{$dato->cantGru}}">
                          {{$dato->nomSta}}
                      </td>
                      <td>
                          {{$dato->nomEst}}@if($dato->bLider==1)<i> (Lider)</i>@endif
                      </td>
                  @else
                      <td>{{$dato->nomEst}}</td>
                  @endif

              </tr>

              {!! $currId = $dato->idGru;!!}
        @endforeach
    </table>
</body>
@stop