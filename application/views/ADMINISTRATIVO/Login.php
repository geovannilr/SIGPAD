<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <!-- CSS -->	
    <link href="<?=base_url('assets/css/bootstrap.min.css') ?>	" rel="stylesheet" type="text/css" />   
    <link href="<?=base_url('assets/css/style_login.css') ?>	" rel="stylesheet" type="text/css" />
    <script src="<?= base_url('assets/js/jquery-2.2.1.min.js')?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>" type="text/javascript"></script>
    <script>
        $(function(){
            $('[data-toggle="tooltip"]').tooltip();
            $("#proc_admin").hide();
            $("#tipo_usuario").hide();
            $("#remember").hide();
/*            $("#tipo_usuario").change(function(){
                var str = "";
                $( "#tipo_usuario option:selected" ).each(function() {
                    if($(this).val() != 1 ){
                        $("#proc_admin").show();
                    }//if
                    else{
                        $("#proc_admin").hide();
                    }
                });*/
                //alert(str);
            //});
        });
    </script>
    
  </head>
  <body>

    <div class="container">
        <div class="card">
            <h3 class="display-4"><center>SISTEMA INFORMÁTICO PARA LA GESTIÓN Y CONTROL DE LOS PROCESOS ACADÉMICOS-ADMINISTRATIVOS</center></h3>
            <h4 class="display-4"><center>EISI - FIA - UES</center></h4>
        </div>
        <div class="card card-container">

            <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <p class="text-danger"><?=$mensaje?></p>
            <form class="form-signin" action="<?=BASE_URL.'/ADMINISTRATIVO/Login/Entrar'?>" method="post">
                <span id="reauth-email" class="reauth-email"></span>
                <input name = "nombre_usuario" type="text" id="nombre_usuario" class="form-control" placeholder="Nombre de usuario" required autofocus>
                <input type="password" name = "password" id="password" class="form-control" placeholder="Password" required>
                

                <div id="remember" class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me"> Recordarme
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Iniciar Sesión</button>
            </form><!-- /form -->
            
            <!-- 
            <p>
            <a href="<?=BASE_URL.'/Administrador/Usuario/Registro_Usuario';?>" class="forgot-password">
                Registrate
            </a></p>
            -->
        </div><!-- /card-container -->
    </div><!-- /container -->
  </body>
</html>  
<!-- *******Contenido de la página **********************-->
