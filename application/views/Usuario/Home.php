<!-- ********Verifica el inicio de sesion****************-->
<?php 
    if ($this->session->userdata('usuario') == NULL): 
        $this->load->view('templates/sessionclose'); 
    else: 
?>
<!-- ********Fin Verifica el inicio de sesion************-->


<!-- *******Contenido de la página Index**********************-->

    <div id = "effect" class="jumbotron text-center">
        
        <h1>BIENVENIDO/A <br><small><?= $this->session->userdata('usuario') ?></small></h1>
    </div> <!-- jumbotron -->
<!-- *******Fin de Contenido de la página Index****************-->


<!-- ********Verifica el inicio de sesion****************-->
<?php endif //fin ($this->session->userdata('tipo') == NULL)  ?>
<!-- ********Fin Verifica el inicio de sesion************-->

<!-- *******Scripts propios de la pagina Index*****************-->


<!-- *******Fin Scripts propios de la pagina Index**************-->
