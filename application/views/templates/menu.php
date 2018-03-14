<!-- ********Verifica el tipo de usuario****************-->
<?php 
//echo(json_encode($this->session->userdata()));

$tipo = $this->session->userdata('tipo');
$id_cargo_administrativo = $this->session->userdata('id_cargo_administrativo');
$id_cargo = $this->session->userdata('id_cargo');
$tipo_estudiante = $this->session->userdata('tipo_estudiante');
$id_doc_est = $this->session->userdata('id_doc_est');
$nombre = $this->session->userdata('nombre');
$apellido = $this->session->userdata('apellidos');
$usuario = $nombre.' '.$apellido;
//echo("id_doc_est".$id_doc_est);
//echo($tipo);
//echo($id_cargo_administrativo);
//echo($id_cargo);
//echo($tipo_estudiante);
?>
<!-- ********Fin Verifica el tipo de usuario*************-->

<div class="panel-group" id="accordion">    
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-th">
                </span>Trabajo de Graduación</a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <?php 
                        if($id_cargo_administrativo=='4'){
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/ADMINISTRATIVO/CargaArchivoExcel')?>">Carga de Archivo Excel a Trabajo de Graduación</a>
                            </td>
                        </tr> 
                        <?php   
                        };
                        ?>

                        <?php   
                        if($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) {
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PDG/ConfEquiTg')?>">Conformar Equipo de Trabajo de Graduación</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>
                        <?php   
                        if($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) {
                        ?>
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/IngresoTema_gc')?>">Registrar Tema de TG</a>
                            </td>
                        </tr>   
                        <?php   
                        };
                        ?>                        
                        <?php   
                        if($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) {
                        ?>
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/Perfil_gc')?>">Registrar Perfil</a>
                            </td>
                        </tr>   
                        <?php   
                        };
                        ?>
                        <?php  
                        if  (
                                ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) or
                                ($tipo == 'Docente' and
                                    (
                                        $id_cargo_administrativo == '4' or $id_cargo_administrativo == '1' or
                                        ($id_cargo_administrativo == '6' and ($id_cargo == '2' or $id_cargo == '5'
                                            or $id_cargo == '6'))
                                    )   
                                )
                            ) {
                        ?>
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/EstatusPerfil_gc')?>">Estatus de Perfiles de TG</a>
                            </td>
                        </tr>   
                        <?php   
                        };
                        ?>                        
                        <?php 
                        if($id_cargo_administrativo=='4'){
                        ?>
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/AproDenePerfil_gc')?>">Aprobar/ Denegar Perfil</a>
                            </td>
                        </tr>  
                        <?php   
                        };
                        ?>
                        <?php 
                        if($id_cargo_administrativo=='4'){
                        ?>
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/AsigDocenteAsesor_gc')?>">Asignar Docente Asesor</a>
                            </td>
                        </tr>   
                        <?php   
                        };
                        ?>  
                        <?php 
                        if($id_cargo_administrativo=='4'){
                        ?>                                    
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/RegisTribuEva_gc')?>">Agregar Tribunal Evaluador</a>
                            </td>
                        </tr>  
                        <?php   
                        };
                        ?>                   
                        <?php   
                        if($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) {
                        ?>                  
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/RegisAnteproy_gc')?>">Registrar Anteproyecto</a>
                            </td>
                        </tr> 
                        <?php   
                        };
                        ?>     
                        <?php 
                        if($id_cargo_administrativo=='4'){
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/AproDebeAnteproy_gc')?>">Aprobar/ Denegar Anteproyecto</a>
                            </td>
                        </tr>   
                        <?php   
                        };
                        ?>  
                        <?php  
                        if  (
                                ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) or
                                ($tipo == 'Docente' and
                                    (
                                        $id_cargo_administrativo == '4' or $id_cargo_administrativo == '1' or
                                        ($id_cargo_administrativo == '6' and ($id_cargo == '2' or $id_cargo == '5'
                                            or $id_cargo == '6'))
                                    )   
                                )
                            ) {
                        ?>

                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/ResumenAproDebeAnteproy_gc')?>">Resumen de Anteproyectos Aprobados y Denegados</a>
                            </td>
                        </tr>    
                        <?php   
                        };
                        ?> 

                        <?php 
                        if($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) {
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/SubirEtapas_gc')?>">Subir Etapas</a>
                            </td>
                        </tr>    
                        <?php   
                        };
                        ?>    
                         
                        <?php  
                        if  (
                                ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) or
                                ($tipo == 'Docente' and
                                    (
                                        $id_cargo_administrativo == '4' or $id_cargo_administrativo == '1' or
                                        ($id_cargo_administrativo == '6' and ($id_cargo == '2' or $id_cargo == '5'
                                            or $id_cargo == '6'))
                                    )   
                                )
                            ) {
                        ?>
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/ResumSubirEtapas_gc')?>">Resumen de Documentos de Etapas Evaluativas</a>
                            </td>
                        </tr>    
                        <?php   
                        };
                        ?>  

                        <?php  
                        if  (
                                ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) or
                                ($tipo == 'Docente' and
                                    ($id_cargo_administrativo == '6' and $id_cargo == '2' )
                                       
                                )
                            ) {
                        ?>

                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/ControlAsesorias_gc')?>">Control de Asesorias</a>
                            </td>
                        </tr>  

                        <?php   
                        };
                        ?> 

                        <?php 
                        if($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) {
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/IngresoSoliCambioNombreTG_gc')?>">Ingreso de Solicitud de cambio de Nombre de TG</a>
                            </td>
                        </tr>  
                        <?php   
                        };
                        ?>   
                        <?php 
                        if($id_cargo_administrativo=='4'){
                        ?>                                        
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/AproDeneCambioNombreTG_gc')?>">Aprobar/ Denegar Cambio Nombre TG</a>
                            </td>
                        </tr> 
                        <?php   
                        };
                        ?> 

                        <?php  
                        if  (
                                ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) or
                                ($tipo == 'Docente' and
                                    (
                                        $id_cargo_administrativo == '4' or $id_cargo_administrativo == '1' or
                                        ($id_cargo_administrativo == '6' and ($id_cargo == '2' or $id_cargo == '5'
                                            or $id_cargo == '6'))
                                    )   
                                )
                            ) {
                        ?>

                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/ResumenAproDeneCambioNombre_gc')?>">Resumen de Solicitudes de Cambio de Nombre</a>
                            </td>
                        </tr>       
                        <?php   
                        };
                        ?> 
                        <?php 
                        if($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) {
                        ?>
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/IngresoSoliProrro_gc')?>">Ingresar Solicitud de Prorroga</a>
                            </td>
                        </tr> 
                        <?php   
                        };
                        ?> 

                        <?php 
                        if($id_cargo_administrativo=='4'){
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/AproDeneProrro_gc')?>">Aprobar/ Denegar Prorroga</a>
                            </td>
                        </tr>  
                        <?php   
                        };
                        ?> 

                        <?php  
                        if  (
                                ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) or
                                ($tipo == 'Docente' and
                                    (
                                        $id_cargo_administrativo == '4' or $id_cargo_administrativo == '1' or
                                        ($id_cargo_administrativo == '6' and ($id_cargo == '2' or $id_cargo == '5'
                                            or $id_cargo == '6'))
                                    )   
                                )
                            ) {
                        ?>            
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/ResumenAproDeneProrro_gc')?>">Resumen de Solicitudes de Prorroga</a>
                            </td>
                        </tr>      
                        <?php   
                        };
                        ?> 

                        <?php 
                        if($id_cargo_administrativo=='6' and $id_cargo == '2'){
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/CalifAnteproyecto_gc')?>">Calificaciones Anteproyecto</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>

                        <?php 
                        if($id_cargo_administrativo=='6' and $id_cargo == '2'){
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/CalifEtapa1_gc')?>">Calificaciones Etapa 1</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>

                        <?php 
                        if($id_cargo_administrativo=='6' and $id_cargo == '2'){
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/CalifEtapa2_gc')?>">Calificaciones Etapa 2</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>

                        <?php 
                        if($id_cargo_administrativo=='6' and ($id_cargo == '2' or $id_cargo == '5' or $id_cargo== '6')){
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/CalifDefenPubli_gc')?>">Calificaciones Defensa Pública</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>

                        <?php 
                        if($id_cargo_administrativo=='4'){
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/CierreNotas_gc')?>">Cierre de Notas</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>
                        
                        <?php  
                        if  (
                                ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '1' or $tipo_estudiante == '3' or $tipo_estudiante == '5' or $tipo_estudiante == '7')) or
                                ($tipo == 'Docente' and
                                    (
                                        $id_cargo_administrativo == '4' or $id_cargo_administrativo == '1' or
                                        ($id_cargo_administrativo == '6' and ($id_cargo == '2' or $id_cargo == '5'
                                            or $id_cargo == '6'))
                                    )   
                                )
                            ) {
                        ?>

                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/ConsolNotas_gc')?>">Consolidado de Notas</a>
                            </td>
                        </tr>  
                        <?php   
                        };
                        ?>

                        <?php 
                        if($id_cargo_administrativo=='4'){
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/RecolectNotas_gc')?>">Generar Recolector de Notas</a>
                            </td>
                        </tr>  
                        <?php   
                        };
                        ?>

                        <?php 
                        if($id_cargo_administrativo=='4'){
                        ?>                                           
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/RemiEjemplares_gc')?>">Generar Remision de Ejemplares</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>

                        <?php 
                        if($id_cargo_administrativo=='4'){
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/RatiEjemplares_gc')?>">Generar Ratificación de Notas
                            </td>
                        </tr>      
                        <?php   
                        };
                        ?>

                        <?php 
                        if($id_cargo_administrativo=='4' or $id_cargo_administrativo == '1'){
                        ?>           
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/RepListadoTemas_gc')?>">Listado de Temas de TG</a>
                            </td>
                        </tr>  
                        <?php   
                        };
                        ?>

                        <?php 
                        if($id_cargo_administrativo=='4' or $id_cargo_administrativo == '1'){
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/RepListadoAsesores_gc')?>">Listado de Asesores de TG</a>
                            </td>
                        </tr>   
                        <?php   
                        };
                        ?>

                        <?php 
                        if($id_cargo_administrativo=='4' or $id_cargo_administrativo == '1'){
                        ?> 
                        <tr>
                            <td>
                            <a href="<?= base_url('index.php/PDG/RepListadoAseTribuEva_gc')?>">Listado de Asesores y Tribunal Evaluador de TG</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>                                        
                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><span class="glyphicon glyphicon-th">
                </span>Servicio Social</a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <?php 
                        if($id_cargo_administrativo=='3'){
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/ADMINISTRATIVO/CargaArchivoExcel')?>">Carga de Archivo Excel a Proceso de Servicio Social</a>
                            </td>
                        </tr> 
                        <?php
                        };
                        ?>
                        <?php 
                        if($id_cargo_administrativo=='3'){
                        ?>    
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/Rubros_gc')?>">Ingreso de rubros</a>
                            </td>
                        </tr>
                        <?php
                        };
                        ?>
                        <?php 
                        if($id_cargo_administrativo=='3'){
                        ?>   
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/Instituciones_gc')?>">Ingreso de Instituciones</a>
                            </td>
                        </tr>
                        <?php
                        };
                        ?>  
                        <?php 
                        if($id_cargo_administrativo=='3' or $tipo == 'Contacto'){
                        ?>                    
                        <tr>

                            <td>
                                <a href="<?= base_url('index.php/PSS/Contacto_gc')?>">Contactos de Instituciones</a>
                            </td>
                        </tr>  
                        <?php
                        };
                        ?>
                        <?php 
                        if($tipo == 'Contacto'){
                        ?>                  
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/IngresarServicioSocial_gc')?>">Ingreso de Servicios Sociales</a>
                            </td>
                        </tr> 
                        <?php
                        };
                        ?> 
                        <?php 
                        if($id_cargo_administrativo=='3'){
                        ?>  
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/AproDeneServicioSocial_gc')?>">Aprobación/ Denegación de Servicios Sociales</a>
                            </td>
                        </tr>
                        <?php
                        };
                        ?>  
                        <?php 
                        if($id_cargo_administrativo=='3' 
                            or $id_cargo_administrativo =='1' 
                            or ($id_cargo_administrativo == '6' and $id_cargo == '1')
                            or ($tipo == 'Estudiante' and ($tipo_estudiante == '2' or $tipo_estudiante == '3' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))
                            or ($tipo == 'Contacto')) {
                        ?>                    
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/ConsultaServiSocialesApro_gc')?>">Servicios Sociales disponibles</a>
                            </td>
                        </tr>    
                        <?php
                        };
                        ?>   
                        <?php 
                        if($id_cargo_administrativo=='3'){
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/AperturaExpedientePss_gc')?>">Aperturar Expediente de Servicio Social</a>
                            </td>
                        </tr>  
                        <?php
                        };
                        ?>
                        <?php   
                        if($tipo == 'Estudiante' and ($tipo_estudiante == '2' or $tipo_estudiante == '3' or $tipo_estudiante == '6' or $tipo_estudiante == '7')){
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/ElecServiSocial')?>">Elección de Servicio Social</a>
                            </td>
                        </tr>
                        <?php
                        };
                        ?> 
                        <?php 
                        if($id_cargo_administrativo=='3'){
                        ?>                                     
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/AsigDocenteAsesor_ss_gc')?>">Asignacion de tutores</a>
                            </td>
                        </tr>  
                        <?php
                        };
                        ?>   
                        <?php 
                        if($id_cargo_administrativo=='3'){
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/OficializacionPss_gc')?>">Generación de Oficilizacion de Servicio Social</a>
                            </td>
                        </tr>    
                        <?php
                        };
                        ?>  
                        <?php 
                        if($id_cargo_administrativo=='3' 
                            or $id_cargo_administrativo =='1' 
                            or ($id_cargo_administrativo == '6' and ($id_cargo == '1' or $id_cargo == '4' ))
                            or ($tipo == 'Estudiante' and ($tipo_estudiante == '2' or $tipo_estudiante == '3' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
                        ?>  
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/SeguimientoPss_gc')?>">Seguimiento de Servicio Social</a>
                            </td>
                        </tr>  
                        <?php
                        };
                        ?> 
                        <?php 
                        if($id_cargo_administrativo=='3' 
                            or $id_cargo_administrativo =='1' 
                            or ($id_cargo_administrativo == '6' and $id_cargo == '1')
                            or ($tipo == 'Estudiante' and ($tipo_estudiante == '2'  or $tipo_estudiante == '3' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/ResumenAperturaPss_gc')?>">Estudiantes con expediente de SS pero sin SS inscrito</a>
                            </td>
                        </tr>   
                        <?php
                        };
                        ?>  
                        <?php   
                        if($tipo == 'Estudiante' and ($tipo_estudiante == '2' or $tipo_estudiante == '3' or $tipo_estudiante == '6' or $tipo_estudiante == '7')){
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/GenerarCartaRenuncia_gc')?>">Generación de Carta de Renuncia</a>
                            </td>
                        </tr>                                        
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/IngresoDocumentacionPss_gc')?>">Ingreso de Documentación de Servicio Social</a>
                            </td>
                        </tr>     
                        <?php
                        };
                        ?> 
                        <?php 
                        if($id_cargo_administrativo=='3'){
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/CierreServiciosSociales_gc')?>">Cierre de Servicios Sociales</a>
                            </td>
                        </tr> 
                        <?php
                        };
                        ?>    
                        <?php 
                        if($id_cargo_administrativo=='3'){
                        ?>  
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/CierreExpedientePss_gc')?>">Cierre de Expediente de Servicio Social</a>
                            </td>
                        </tr>  
                        <?php
                        };
                        ?> 
                        <?php 
                        if($id_cargo_administrativo=='3'){
                        ?>    
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PSS/Remisiones_gc')?>">Servicios Sociales Remitidos</a>
                            </td>
                        </tr> 
                        <?php
                        };
                        ?>    
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"><span class="glyphicon glyphicon-th">
                </span>PERA</a>
            </h4>
        </div>
        <div id="collapseThree" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <?php 
                        if($id_cargo_administrativo=='2'){
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PERA/CargaArchivoExcel_PERA')?>">Carga de Archivo Excel a PERA</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>
                        <?php   
                        if($id_cargo == '3' or
                            ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
                        ?>
                        <tr>
                            <td>
                        <a href="<?= base_url('index.php/PERA/AsiGen')?>">Asignación de Asesor General del PERA </a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>
                        <?php   
                        if($id_cargo == '3' or $id_cargo == '10' or
                            ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PERA/AsiDoc')?>">Asignación de Docente Asesor a Estudiante</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>
                        <?php   
                        if($id_cargo == '3' or $id_cargo == '10' or
                            ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PERA/DefTip')?>">Definición de tipo de PERA</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>
                        <?php   
                        if($id_cargo == '3' or $id_cargo == '10' or
                            ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PERA/EstEva')?>">Establecimiento de Evaluaciones</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>
                        <?php   
                        if($id_cargo == '3' or $id_cargo == '10' or
                            ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PERA/RegEva')?>">Registro de Evaluaciones</a>
                            </td>
                        </tr> 
                        <?php   
                        };
                        ?>
                        <?php   
                        if($id_cargo == '3' or 
                            ($tipo == 'Estudiante' and 
                                ($tipo_estudiante == '4' or $tipo_estudiante == '5' or $tipo_estudiante == '6' or $tipo_estudiante == '7'))) {
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PERA/RegNot')?>">Registro de Nota del Programa</a>
                            </td>
                        </tr> 
                        <?php   
                        };
                        ?>
          		<?php 
                        if($id_cargo_administrativo=='2'){
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/PERA/CiePer')?>">Cierre del Programa</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?>                  
                    </table>
                </div>
            </div>
        </div>
    </div>    
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><span class="glyphicon glyphicon-th">
                </span>Administrativo</a>
            </h4>
        </div>
        <div id="collapseFour" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <?php 
                        if($id_cargo_administrativo=='1'){
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/ADMINISTRATIVO/CargoAdmin_gc')?>">Cargos Administrativos</a>
                            </td>
                        </tr>  
                        <?php   
                        };
                        ?> 
                        <?php 
                        if($id_cargo_administrativo=='1'){
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/ADMINISTRATIVO/Cargo_gc')?>">Cargos Docentes</a>
                            </td>
                        </tr>  
                        <?php   
                        };
                        ?> 
                        <?php 
                        if($id_cargo_administrativo=='1'){
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/ADMINISTRATIVO/Departamentos_gc')?>">Departamentos</a>
                            </td>
                        </tr>  
                        <?php   
                        };
                        ?> 
                        <?php 
                        if($tipo == 'Docente'){
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/ADMINISTRATIVO/Docentes_gc')?>">Docentes</a>
                            </td>
                        </tr> 
                        <?php   
                        };
                        ?> 
                        <?php 
                        if($id_cargo_administrativo=='1'or 
                            $id_cargo_administrativo == '3' or 
                            $id_cargo_administrativo == '4' or 
                            $tipo == 'Estudiante'){
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/ADMINISTRATIVO/Estudiantes_gc')?>">Estudiantes</a>
                            </td>
                        </tr> 
                        <?php   
                        };
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/ADMINISTRATIVO/Usuarios_gc')?>">Usuarios</a>
                            </td>
                        </tr>
                        <?php 
                        if($id_cargo_administrativo=='1'){
                        ?> 
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/ADMINISTRATIVO/Materias_gc')?>">Materias</a>
                            </td>
                        </tr>   
                        <?php   
                        };
                        ?>     
                        <?php 
                        if($id_cargo_administrativo=='1'){
                        ?>               
                        <tr>
                            <td>
                                <a href="<?= base_url('index.php/ADMINISTRATIVO/ParamGenerales_gc')?>">Parametros Generales</a>
                            </td>
                        </tr>
                        <?php   
                        };
                        ?> 
                    </table>   
                </div> 
            </div>
        </div>
    </div>      
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive"><span class="glyphicon glyphicon-user">
                </span><?=$usuario?> </a>
            </h4>
        </div>
        <div id="collapseFive" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-user"></span><a href="<?= base_url('index.php/ADMINISTRATIVO/Login/Destroy')?>">Cerrar Sesion</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


