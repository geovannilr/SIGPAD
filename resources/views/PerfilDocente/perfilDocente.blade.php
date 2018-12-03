<!DOCTYPE html>

<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="current-url" content="{{\Illuminate\Support\Facades\URL::to('/')}}">
    <title>.::Perfl Docente - EISI</title>

    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet">
    
    <!-- Bootstrap core CSS -->
    {!!Html::style('PerfilDocente/vendor/fontawesome-free/css/all.min.css')!!}
    {!!Html::style('PerfilDocente/vendor/bootstrap/css/bootstrap.min.css')!!}
    {!!Html::style('PerfilDocente/css/resume.css')!!}
    
  </head>

  <body id="page-top">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="sideNav">
      <a class="navbar-brand js-scroll-trigger" href="#page-top">
        <span class="d-block d-lg-none">Clarence Taylor</span>
        <span class="d-none d-lg-block">
          <img class="img-fluid img-profile rounded-circle mx-auto mb-2" id="profileFoto" src="">
        </span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#about">Acerca de mi</a>
          </li>
		  <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#experiencia">Experiencia Laboral</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#historial">Historial Acádemico</a>
          </li>
		   <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#certificaciones">Certificaciones</a>
          </li>
		   <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#skills">Habilidades</a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="container-fluid p-0">

      <section class="resume-section p-3 p-lg-5 d-flex d-column" id="about">
        <div class="my-auto">
          <h1 class="mb-0" id="nombreDocente">
          </h1>
          <div class="subheading mb-5" ><div id="cargoDocente"></div>
            <a href="mailto:name@email.com" id="correoDocente" target="_blank"></a>
          </div>
          <p class="lead mb-5" id="descripcionDocente"></p>
          <div class="social-icons">
            <a href="" id="linkLinkedind_" target="_blank">
              <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="" id="linkGit_" target="_blank">
              <i class="fab fa-github"></i>
            </a>
            <a href="" id="linkTw_" target="_blank">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="" id="linkFb_" target="_blank">
              <i class="fab fa-facebook-f"></i>
            </a>
          </div>
        </div>
      </section>

      <hr class="m-0">


      <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="experiencia">
        <div class="my-auto" id="seccionExperiencia">
          <h2 class="mb-5">Experiencia Laboral</h2>

        </div>
      </section>
	   <hr class="m-0">
	  <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="historial">
        <div class="my-auto" id="seccionHistorial">
          <h2 class="mb-5">Historial Acádemico</h2>

        </div>
      </section>
	   <hr class="m-0">
	   <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="certificaciones">
        <div class="my-auto" id="seccionCertificaciones">
          <h2 class="mb-5">Certificaciones</h2>

        </div>
      </section>
		
      <hr class="m-0">

      <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="skills">
        <div class="my-auto" id="seccionSkills">
          <h2 class="mb-5">Habilidades</h2>

          
          
        </div>
      </section>

      <hr class="m-0">



      

    </div>

  {!!Html::script('PerfilDocente/vendor/jquery/jquery.min.js')!!}
  {!!Html::script('PerfilDocente/vendor/bootstrap/js/bootstrap.bundle.min.js')!!}
   {!!Html::script('PerfilDocente/vendor/jquery-easing/jquery.easing.min.js')!!}
	 {!!Html::script('PerfilDocente/js/main.js')!!}
        <!-- Custom scripts for this template -->
    {!!Html::script('PerfilDocente/js/resume.js')!!}        <!-- Plugin JavaScript -->
   

	<script>
	$( document ).ready(function() {
    console.log( "ready!" );
		getHistorialAcademico({{$idDocente}});
		getExperienciaDocente({{$idDocente}});
		getCertificacionesDocente({{$idDocente}});
		getSkillsDocente({{$idDocente}});
    getInformacionDocente({{$idDocente}});
	});
	</script>
  </body>

</html>
