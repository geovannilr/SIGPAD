@extends('pdfTemplate')

@section('content')
<body>
	<center>
	<h5>{{$title}}</h6>
	 
	</center>
    <table class="table table-bordered" style="font-size: 12px;">
        <tr>
            <td><b>Docente</b></td>
            <td><b>Asignaciones</b></td>
        </tr>
      
        @foreach($datos as $dato)
        <tr>
            <td>
                {{$dato->Docente}}
            </td>
            <td>
                
                    <table class="table">
                    	<tr>
                    		<td><b>Grupo</b></td>
                        	<td><b>Rol</b></td>
                    	</tr>
                        
                        @foreach($grupos as $grupo)
                            @if($grupo->CarnetDoc == $dato->CarnetDoc)
                                <tr>
                                    <td>{{$grupo->NumGrupo}}</td>
                                    <td>{{$grupo->TribunalRol}}</td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
               
            </td>
        </tr>
        @endforeach
    </table>
</body>
@stop