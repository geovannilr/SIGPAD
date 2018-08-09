<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="app-url" content="{{ env('APP_URL') }}" >
  <meta name="current-url" content="{{\Illuminate\Support\Facades\URL::to('/')}}">
  <title>.::SIGPAD - EISI</title>
  <!-- CSS-->
  {!!Html::style('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css')!!}
  {!!Html::style('css/sb-admin.css')!!}
  {!!Html::style('css/font-awesome/css/font-awesome.min.css')!!}
  {!!Html::style('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css')!!}
  {!!Html::script('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js')!!}
  
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
    {!!Html::style('css/multi-select.css')!!}
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"> </script>
    {!!Html::script('js/main.js')!!}
    {!!Html::script('js/TrabajoGraduacion/trabajoGraduacion.js')!!}
    {!!Html::script('js/jquery.multi-select.js')!!}
 <style type="text/css">
@font-face {
    font-family: 'American_Captain';
    src: url('/SIGPAD/public/fonts/American_Captain.woff') format('woff');
}

  </style>
   
    
    
</head>
<body class="fixed-nav sticky-footer " id="page-top" style="background-color: #29282b; ">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg fixed-top" id="mainNav" style="background-color: #29282b; color: #ffffff;">
    <a class="navbar-brand" href="{{  url('/') }}" style="font-family: American_Captain; font-size: 2em; margin-left: -50px; color: #ffffff; text-decoration: underline; text-indent: 1em; text-decoration-style: solid;"><i class="fa fa-eercast fa-lg" aria-hidden="true">  </i>    S I G P A D   </a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon fa fa-bars" style="color: #ffffff"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarResponsive" style=" color: #ffffff; "  >
      <ul class="navbar-nav navbar-sidenav " id="exampleAccordion" style="overflow: auto;background-color: #29282b;color: #ffffff; z-index: 0; margin-top: 73px;">
        @can('prePerfil.index')
           <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Trabajo de graduación">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseTrabajoGraduacion" data-parent="#exampleAccordion" style="color: #ffffff; font-weight: bold; background-color: #DF1D20; ">
              <i class="fa fa-fw fa fa-mortar-board"></i>
              <span class="nav-link-text" style="color: #ffffff" >Trabajo de Graduacion</span>
            </a>
            <ul class="sidenav-second-level collapse" id="collapseTrabajoGraduacion">
              @can('grupotdg.create','grupo.index')
                <li>
                  <a class="nav-link" href="{{route('grupo.create')}}" style="color: #ffffff">
                    <i class="fa fa-users"></i>
                    <span class="nav-link-text">Grupos de TG</span>
                  </a>  
              </li>
              @endcan
              @can('prePerfil.index')
              <li>
                <a class="nav-link" href="{{route('prePerfil.index')}}" style="color: #ffffff">
                  <i class="fa fa-file-o"></i>
                  <span class="nav-link-text">Pre-Perfil</span>
                </a>
                     
              </li>
              @endcan
               @can('prePerfil.index')
              <li>
                <a class="nav-link" href="{{route('perfil.index')}}" style="color: #ffffff">
                  <i class="fa fa-file-o"></i>
                  <span class="nav-link-text">Perfil</span>
                </a>
                     
              </li>
              @endcan
              @can('dashboard.index')
                <li>
                  <a class="nav-link" href="{{route('dashboard')}}" style="color: #ffffff">
                    <i class="fa fa-area-chart"></i>
                    <span class="nav-link-text">Dashboard</span>
                  </a>   
              </li>
              @endcan
            </ul>
          
          </li>
        @endcan
        @can('publicacion.index')
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="publicaciones">
                <a class="nav-link nav-link-collapse collapsed"  data-toggle="collapse" href="#collapsePublicaciones" data-parent="#exampleAccordion" style="color: #ffffff; font-weight: bold; background-color: #DF1D20; margin-top: 20px"  >
                  <i class="fa fa-book"></i>
                  <span class="nav-link-text">Biblioteca de Tesis</span>
                </a>

                 <ul class="sidenav-second-level collapse" id="collapsePublicaciones">
                      @can('publicacion.create','publicacion.index')
                        <li>
                          <a class="nav-link" href="{{route('publicacion.index')}}" style="color: #ffffff">
                            <i class="fa fa-file-text"></i>
                            <span class="nav-link-text">Historico de Tesis</span>
                          </a>  
                        </li>

                       @endcan
            </ul>
                     
          </li>
        @endcan
        @can('usuario.index', 'permiso.index', 'rol.index')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Administracion" style="color: #ffffff">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseAdministracion" data-parent="#exampleAccordion" style="color: #ffffff; font-weight: bold; background-color: #DF1D20; margin-top: 20px">
            <i class="fa fa-slack"></i>
            <span class="nav-link-text" style="color: #ffffff">Administración</span>
          </a>
           <ul class="sidenav-second-level collapse" id="collapseAdministracion" >
            @can('usuario.create','usuario.edit','usuario.destroy','usuario.index')
                 <li>
                  <a class="nav-link" href="{{route('usuario.index')}}" style="color: #ffffff">
                    <i class="fa fa-users"></i>
                    <span class="nav-link-text">Usuarios</span>
                  </a>  
              </li>
            @endcan
            @can('rol.create','rol.edit','rol.destroy','rol.index')
                <li>
                  <a class="nav-link" href="{{route('rol.index')}}" style="color: #ffffff">
                    <i class="fa fa-address-card"></i>
                    <span class="nav-link-text">Roles</span>
                  </a>  
              </li>
            @endcan
            @can('permiso.create','permiso.edit','permiso.destroy','permiso.index')
            <li>
                  <a class="nav-link" href="{{route('permiso.index')}}" style="color: #ffffff" >
                    <i class="fa fa-lock"></i>
                    <span class="nav-link-text">Permisos</span>
                  </a>  
              </li>
            @endcan
            
          </ul>
        </li>
        @endcan
        

      </ul>
      <ul class="navbar-nav sidenav-toggler" >
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler" style="background-color :#0A122A" >
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
         <a class="nav-link">
            <i class="fa fa-user"></i>&nbsp;{!!Auth::user()->name!!}</a>
        <li class="nav-item">
          <a class="nav-link"  data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-fw fa-sign-out"></i>Salir</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="content-wrapper">
    <div class="row-fluid user-row">
        @include('alerts.errors')
        
    </div>
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      @yield('content')
       
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Copyright © SIGPAD 2018</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cerrar Sesión</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">¿Estas seguro que deseas cerrar sesión?</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <a class="btn btn-primary" href="{{route('LogOut')}}">Salir</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  {!!Html::script('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js')!!}
  {!!Html::script('js/sb-admin.min.js')!!}
  {!!Html::script('https://unpkg.com/sweetalert/dist/sweetalert.min.js')!!} 
</body>

</html>
