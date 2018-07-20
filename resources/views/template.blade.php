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
 
   
    
    
</head>
<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="{{  url('/') }}">PORTAL ADMINISTRATIVO</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        @can('usuario.create','usuario.edit','usuario.destroy','usuario.index')
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Usuarios">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseUsuarios" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-user"></i>
            <span class="nav-link-text">Usuarios</span>
          </a>
           <ul class="sidenav-second-level collapse" id="collapseUsuarios">
             <li>
              {!! link_to_route('usuario.create', $title ='Nuevo',null,$attributes = ['class'=>'nav-link']); !!}
            </li>
            <li>
              {!! link_to_route('usuario.index', $title ='Ver',null,$attributes = ['class'=>'nav-link']); !!}
            </li>
          </ul>
        </li>
        @endcan
        @can('rol.create','rol.edit','rol.destroy','rol.index')
           <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Roles">
            <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseRoles" data-parent="#exampleAccordion">
              <i class="fa fa-fw fa-group"></i>
              <span class="nav-link-text">Roles</span>
            </a>
            <ul class="sidenav-second-level collapse" id="collapseRoles">
              <li>
                {!! link_to_route('rol.create', $title ='Nuevo',null,$attributes = ['class'=>'nav-link']); !!}
              </li>
              <li>
                {!! link_to_route('rol.index', $title ='Ver',null,$attributes = ['class'=>'nav-link']); !!}
              </li>
            </ul>
          </li>
        @endcan
        @can('permiso.create','permiso.edit','permiso.destroy','permiso.index')
         <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Permisos">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapsePermisos" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-lock"></i>
            <span class="nav-link-text">Permisos</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapsePermisos">
            <li>
              {!! link_to_route('permiso.create', $title ='Nuevo',null,$attributes = ['class'=>'nav-link']); !!}
            </li>
            <li>
              {!! link_to_route('permiso.index', $title ='Ver',null,$attributes = ['class'=>'nav-link']); !!}
            </li>
          </ul>
        </li>
        @endcan
         <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Trabajo de graduación">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseTrabajoGraduacion" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa fa-mortar-board"></i>
            <span class="nav-link-text">Trabajo de Graduacion</span>
          </a>
          <ul class="sidenav-second-level collapse" id="collapseTrabajoGraduacion">
            @can('grupotdg.create','grupo.index')
            <li>
              <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapseGrupo">Grupo Trabajo de Graduación</a>
              <ul class="sidenav-third-level collapse" id="collapseGrupo">
                @can('grupotdg.create')
                  <li>
                    {!! link_to_route('grupo.create', $title ='Nuevo',null,$attributes = ['class'=>'nav-link']); !!}
                  </li>
                @endcan
                @can('grupo.index')
                   <li>
                    {!! link_to_route('grupo.index', $title ='Listado de Grupos',null,$attributes = ['class'=>'nav-link']); !!}
                  </li>
                @endcan
              </ul>
            </li>
            @endcan
            @can('prePerfil.index')
            <li>
              <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapsePrePerfil">Pre-Perfil</a>
              <ul class="sidenav-third-level collapse" id="collapsePrePerfil">
                @can('prePerfil.create')
                  <li>
                    {!! link_to_route('prePerfil.create', $title ='Nuevo',null,$attributes = ['class'=>'nav-link']); !!}
                  </li>
                @endcan
                @can('prePerfil.index')
                   <li>
                    {!! link_to_route('prePerfil.index', $title ='Listado de Pre-Perfiles',null,$attributes = ['class'=>'nav-link']); !!}
                  </li>
                @endcan
              </ul>
            </li>
            @endcan
            @can('prePerfil.index')
              <li>
               {!! link_to_route('Dashboard', $title ='Vista Principal',null,$attributes = ['class'=>'nav-link']); !!}
              </li>
            @endcan
             @can('publicacion.index')
              <li>

              
               {!! link_to_route('publicacion.index', $title ='Histórico de trabajos de graduación',null,$attributes = ['class'=>'nav-link']); !!}
              </li>
            @endcan
          </ul>
        
        </li>
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
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
          <div class="modal-body">Estas seguro que deseas cerra sesión?</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
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
