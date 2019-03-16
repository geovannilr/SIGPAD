@extends('pdfTemplate')

@section('content')
<body>
	<center>
	<h5>{{$title}}</h6>
	 
	</center>
    <table class="table table-bordered" style="font-size: 12px;">
        <tr>
            <td><b>Categoria</b></td>
            <td><b>Docente</b></td>
        </tr>
        <?php $idCat = 0; ?>
        @foreach($datos as $dato)
            @if($idCat != $dato->id_cat_ctg_tra)
                @if($idCat == 0)
                    <tr><td>{{$dato->nombre_cat_ctg_tra}}</td><td>
                @else
                    </td></tr><tr><td>{{$dato->nombre_cat_ctg_tra}}</td><td>
                @endif
                
            @else
               &nbsp; &nbsp; <li>{{$dato->nombre}}</li>        
            @endif
            <?php $idCat = $dato->id_cat_ctg_tra; ?>
        @endforeach
        </td></tr>
    </table>
</body>
@stop