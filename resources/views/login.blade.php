
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<style>
@import url("//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
.login-block{
    background: #DE6262;  /* fallback for old browsers */
background: -webkit-linear-gradient(to bottom, #8d2525, #ffffff);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to bottom, #8d2525, #ffffff); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
float:left;
width:100%;
padding : 50px 0;
}
.banner-sec{ min-height:500px; border-radius: 0 10px 10px 0; padding:0;}
.container{background:#fff; border-radius: 10px; box-shadow:15px 20px 0px rgba(0,0,0,0.1);}
.carousel-inner{border-radius:0 10px 10px 0;}
.carousel-caption{text-align:left; left:5%;}
.login-sec{padding: 50px 30px; position:relative;}
.login-sec .copy-text{position:absolute; width:80%; bottom:20px; font-size:13px; text-align:center;}
.login-sec .copy-text i{color:#FEB58A;}
.login-sec .copy-text a{color:#E36262;}
.login-sec h3{margin-bottom:30px; font-weight:800; font-size:30px; color: #8d2525;}
.login-sec h3:after{content:" "; width:100px; height:5px; background:#8d2525; display:block; margin-top:20px; border-radius:3px; margin-left:auto;margin-right:auto}
.btn-login{background: #8d2525; color:#fff; font-weight:600;}
.banner-text{width:70%; position:absolute; bottom:40px; padding-left:20px;}
.banner-text h3{color:#fff; font-weight:600;}
.banner-text h3:after{content:" "; width:100px; height:5px; background:#FFF; display:block; margin-top:20px; border-radius:3px;}
.banner-text p{color:#fff;}
</style>
<section class="login-block">

    <div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
        <img class="img-fluid" src="http://aula.fia.ues.edu.sv/materialpublico/pdm115/anyos_anteriores/2015/background_eisi.jpg" alt="UES">
        </div>
        
    </div>
    <div class="row">
        <div class="col-md-4 login-sec">
        @include('alerts.errors')
            <h3 class="text-center">Iniciar Sesión</i></h3>
            {!! Form:: open(['route'=>'login.store','method'=>'POST','class'=>'login-form']) !!}
  <div class="form-group">
    <label  class="text-uppercase">Usuario</label>
    {!!Form::text('usuario',null,['class'=>'form-control'])  !!}
    
  </div>
  <div class="form-group">
    <label class="text-uppercase">Contraseña</label>
    {!!Form::password('password',['class'=>'form-control'])  !!}
  </div>
  
  
    <div class="form-check">
    {!! Form::submit('Entrar',['class'=>'btn-login']) !!}
  </div>
{!! Form:: close() !!}
        </div>
        <div class="col-md-8 banner-sec">
           <div class="carousel-item active">
      <img class="d-block img-fluid" src="http://farm5.static.flickr.com/4102/4906666532_dcf1e037b3_b.jpg" alt="EISI">
      <div class="carousel-caption d-none d-md-block">
        <div class="banner-text">
            <h2>SIGPAD</h2>
            <p>Sistema informático para la gestión de procesos académicos y administrativos de la Escuela de Ingeniería de Sistemas de la Universidad de El Salvador</p>
        </div>  
  </div>
    </div>
        </div>
</div>
</section>