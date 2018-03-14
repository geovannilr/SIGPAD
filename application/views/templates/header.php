<!DOCTYPE html>
<html lang="es">
<head>
    <title>.::SISTEMA INFORMÁTICO PARA LA GESTIÓN Y CONTROL DE LOS PROCESOS ACADÉMICOS-ADMINISTRATIVOS - EISI - FIA - UES::.</title>
    <!--  
    <meta charset="utf-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    -->
    <!-- CSS -->    
    <link href="<?=base_url('assets/css/bootstrap.min.css') ?>  " rel="stylesheet" type="text/css" />
    <!--
    <link href="<?=base_url('assets/css/bootstrap.css') ?>  " rel="stylesheet" type="text/css" />
    <link href="<?=base_url('assets/css/font-awesome.min.css') ?>   " rel="stylesheet" type="text/css" />
    <link href="<?=base_url('assets/css/ionicons.min.css') ?>   " rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('/fancybox/source/jquery.fancybox.css') ?>" type="text/css" media="screen" />
    <link href="<?=base_url('assets/css/bootstrap-datepicker.css') ?>  " rel="stylesheet" type="text/css" />
    
    -->
    <link href="<?=base_url('assets/css/jquery-ui.css') ?>  " rel="stylesheet" type="text/css" />
    <link href="<?=base_url('assets/css/plantilla.css') ?>" rel="stylesheet" type="text/css" >
    
    <!-- JS -->
    <script src="<?= base_url('assets/js/jquery-2.2.1.min.js')?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>" type="text/javascript"></script>

    <!--
    <script src="<?= base_url('assets/js/jquery-2.0.0.min.js') ?>" type="text/javascript"></script>   
    <script src="<?= base_url('assets/js/jquery-ui.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/bootstrap.js') ?>" type="text/javascript"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/bootstrapValidator.js') ?>"></script>
    <link rel="stylesheet" href="<?= base_url('/fancybox/source/jquery.fancybox.css') ?>" type="text/css" media="screen" />
    <script type="text/javascript" src="<?= base_url('/fancybox/source/jquery.fancybox.pack.js') ?>"></script>
    -->
    <!--  bootstrap-datepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js"></script>     

    <!--  bootstrap-datepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js"></script>     

    <!--  fileimput -->
    <link href="<?=base_url('fileinput/css/fileinput.min.css') ?>" rel="stylesheet" type="text/css" >
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="<?= base_url('fileinput/js/fileinput.min.js')?>"></script>

    <script src="<?= base_url('assets/js/jquery-ui.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("form").keypress(function(e) {
        if (e.which == 13) {
            return false;
        }
    });
});
</script>

<!-- Agregado por RMORAN 04/03/2017 para que las cajas de texto se muestren blancas y no grises
y para que el acordenon del boton donde se despliegan los reportes salga con letra blanca -->
<style type="text/css">
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: #fff !important;
    opacity: 1;
}
.dropdown-menu>li>a {
    color: #ffffff !important;
   
}
</style> 


</head>
<body>
    <header>
        <div class="container-fluid">
            <div class="visible-md visible-lg col-md-2">
                <div class="logo">
                    <img src="<?= base_url('assets/img/eisi.jpg') ?>" class="img-responsive" alt="Responsive image"/>
                </div>
            </div>
            <div class="col-md-8 text-center">
                <h2 class="titulo1"><b>SISTEMA INFORMÁTICO PARA LA GESTIÓN Y CONTROL DE LOS PROCESOS ACADÉMICOS-ADMINISTRATIVOS</b></h2>
                <h4 class="titulo2">EISI - FIA - UES</h4>
            </div>
            <div class="visible-md visible-lg col-md-2 text-right">
                <div class="logoe">
                    <img src="<?= base_url('assets/img/minerva.png') ?>" class="img-responsive" alt="Responsive image"/>
                </div>  
            </div>
        </div>
    </header>
