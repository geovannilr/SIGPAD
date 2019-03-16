@extends('pdfTemplate')

@section('content')
<body>
	<center>
	<h5>{{$title}}</h6>
	 
	  </center>
	    <table class="table table-bordered" style="font-size: 12px;">
	        <tr>
	            <td><b>Número Grupo</b></td>
	            <td><b>Líder</b></td>
	            <!---<td>Líder</td>-->
	            <td><b>Tribunal Evaluador</b></td>
	        </tr>
	        
	        @foreach($datos as $dato)
	        <tr>
	            <td>
	                {{$dato->NumGrupo}}{{$dato->finalizo==1?' (finalizado)':''}}
	            </td>
	            <td>
	                {{$dato->Carnet}} - {{$dato->Lider}}
	            </td>
	          
	            <td >
	              
	                    <table class="table" >
	                    	<tr>
	                    	<td><b>Rol</b></td>
	                        <td><b>Docente</b></td>
	                    	</tr>
	                        
	                        @foreach($tribs as $trib)
	                            @if($trib->NumGrupo == $dato->NumGrupo)
	                                <tr>
	                                    <td>{{$trib->TribunalRol}}</td>
	                                    <td>{{$trib->Docente}}</td>
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