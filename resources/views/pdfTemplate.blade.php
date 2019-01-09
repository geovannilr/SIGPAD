<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    {!!Html::style('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css')!!}
    

    <title>REPORTE</title>
  </head>
  <body>
    <p align="right" style="font-size: 12px;">
      {!!date("d/m/Y h:i:s A");!!}
    </p>
    <center>
      <table class="table">
      <tr>
        <td width="50%" style="font-size: 12px;">
          Universidad de El Salvador <br>
          Facultad de Ingenieria y Arquitectura  <br>
          Escuela de Ingenieria de Sistemas Informaticos <br>
          Sistema Informatico para la gestion de procesos academicos y administrativos (SIGPAD) <br>
        </td>
        <td width="50%">
          <center>{!! HTML::image('img/eisi.jpg', 'Logo EISI', array('id' => 'logo', 'width'=>'100px', 'height'=>'100px', 'align'=>"right" )) !!}</center>
        </td>
      </tr>
      </table>
    </center>     
      @yield('content')
  
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    {!!Html::script('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js')!!}
    {!!Html::script('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js')!!}
    {!!Html::script('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js')!!}
  </body>
</html>