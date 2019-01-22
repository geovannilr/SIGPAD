@extends('pdfTemplate')

@section('content')
<body>
	<center>
	<h5>{{$title}}</h6>
	 
	</center>
    <table class="table table-bordered" style="font-size: 12px;">
        <tr>
            <td><b>Grupo</b></td>
            <td><b>Lider</b></td>
            <td><b>Estado</b></td>
        </tr>
      
        @foreach($datos as $dato)
        <tr>
            <td>
                {{$dato->numero_pdg_gru}}
            </td>
            <td>
                {{$dato->carnet_gen_est}} - {{$dato->nombre_gen_est}}
            </td>
            <td>
                <p>
                    <b>Etapa Actual :</b> {{$dato->nombre_cat_eta_eva}} 
                </p>
                <p>
                    @if($dato->CantAprobadas <= 0)
                        <b>Porcentaje de Avance :</b> 0%
                    @else
                        <b>Porcentaje de Avance :</b> {{number_format(($dato->CantAprobadas/$dato->totalEtapas)*100,2)}}%
                    @endif
                    
                </p>
            </td>
        </tr>
        @endforeach
    </table>
</body>
@stop