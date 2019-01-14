@extends('pdfTemplate')

@section('content')
<body>
	<center>
	<h5>{{$title}}</h6>
	 
	</center>
    <table class="table table-bordered" style="font-size: 12px;">
        <tr>
            <td style="width: 10%;"><b>Correl.</b></td>
            <td style="width: 20%;"><b>Carnet</b></td>
            <td style="width: 50%;"><b>Nombre</b></td>
            <td style="width: 20%;"><b>Número de Grupo</b></td>
        </tr>
        {!! $contador = 0; !!}
        @foreach($datos as $dato)
            {!! $contador++; !!}
            <tr>
                <td style="text-align: center;">
                    {{$contador}}
                </td>
                <td>
                    {{$dato->carnet_gen_est}}
                </td>
                <td>
                    {{$dato->nombre_gen_est}}
                </td>
                <td>
                    {{$dato->numero_pdg_gru}}
                </td>
            </tr>
        @endforeach
    </table>
</body>
@stop