@extends('pdfTemplate')

@section('content')
<body>
	<center>
	<h5>{{$title}}</h5>
    <h6>{{$subtitle}}</h6>
	</center>
    <table class="table table-bordered" style="font-size: 12px;">
        <tr>
            <td><b>Carnet</b></td>
            <td><b>Estudiantes</b></td>
            {!! $contador = 0; !!}
        @foreach($datos as $dato)
                {!! $contador++; !!}
                <td><b>{{$dato->etapa}}</b></td>
            @if($contador==$dato->cantEtapas)
                @break
            @endif
        @endforeach
        </tr>
        {!! $current=''; $contador = 0;  !!}
        @foreach($datos as $dato)
            {!! $contador++;  !!}
            @if($current == '')
                {!! $current = $dato->carnet; !!}
                <tr>
                    <td style="text-align: center;">{{$current}}</td>
                    <td>{{$dato->estudiante}}</td>
                    <td style="text-align: center;">{{$dato->notaEtapa}}</td>
            @else
                    <td style="text-align: center;">{{$dato->notaEtapa}}</td>
            @endif
            @if($contador==$dato->cantEtapas)
                    {!! $current='';$contador = 0;  !!}
                </tr>
            @endif
        @endforeach
    </table>
</body>
@stop