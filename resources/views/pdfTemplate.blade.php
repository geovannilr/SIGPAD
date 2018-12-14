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
    <br><br>
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          {!! HTML::image('img/eisi.jpg', 'a picture', array('class' => 'img-fluid')) !!}
        
        </div>
        <div class="col-md-9 text-justify">
        <p>Universidad de El Salvador</p>
        <p>Facultad de Ingenieria y Arquitectura</p>
        <p>Escuela de Ingenieria de Sistemas Informaticos</p>
        <p>Sistema Informatico paraa la gestion de procesos academicos y administrativos (SIGPAD)</p>
        <p><h4>Reporte de Grupos xxxxxxx</h4></p>

        </div>
        
        
      </div>
       <br><br>
      <div class="row">

        <table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td colspan="2">Larry the Bird</td>
      <td>@twitter</td>
    </tr>
  </tbody>
</table>

      </div>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    {!!Html::script('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js')!!}
    {!!Html::script('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js')!!}
    {!!Html::script('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js')!!}
  </body>
</html>