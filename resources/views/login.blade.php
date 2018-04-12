<link href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" type="text/css" href="css/login.css">
<!------ Include the above in your HEAD tag ---------->

<!-- 
 * parallax_login.html
 * @Author original @msurguy (tw) -> http://bootsnipp.com/snippets/featured/parallax-login-form
 * @Tested on FF && CH
 * @Reworked by @kaptenn_com (tw)
 * @package PARALLAX LOGIN.
-->
        <body>
            <div class="container">
                <div class="row vertical-offset-100">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">                                
                                <div class="row-fluid user-row">
                                    @include('alerts.errors')
                                    <img src="img/eisi2.jpg" class="img-responsive" alt="Conxole Admin"/>
                                </div>
                            </div>
                            <div class="panel-body">
                                
                                {!! Form:: open(['route'=>'login.store','method'=>'POST','class'=>'form-signin']) !!}
                                    <fieldset>
                                        <label class="panel-login">
                                            <div class="login_result"></div>
                                        </label>
                                        {!!Form::text('usuario',null,['class'=>'form-control input-lg','placeholder'=>'Usuario'])  !!}
                                        {!!Form::password('password',['class'=>'form-control input-lg','placeholder'=>'Contraseña'])  !!}
                                        <br></br>
                                        {!! Form::submit('Entrar',['class'=>'btn boton btn-lg btn-block']) !!}
                                    </fieldset>
                               {!! Form:: close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
         <script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> 
        </body>
            </div>