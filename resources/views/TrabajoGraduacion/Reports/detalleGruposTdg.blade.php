@extends('pdfTemplate')

@section('content')
<style type="text/css">
  
</style>
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
        <tr >
          @foreach($datos as $dato)
              
                  @if($currId !=$dato->idGru )
                    @if($currId != 0)
                      </td>
                      </tr>
                      <tr>    
                    @endif  

                      <td>
                          {{$dato->numGrupo}}
                      </td>
                      <td>
                          {{$dato->nomSta}}
                      </td>
                      <td>
                          {{$dato->nomEst}}@if($dato->bLider==1)<i> (Lider)</i><br>@endif
                      
                  @else
                      {{$dato->nomEst}}<br>
                  @endif

              
              <?php $currId = $dato->idGru;?>

        @endforeach
        </td>
        </tr>
    </table>
</body>
@stop