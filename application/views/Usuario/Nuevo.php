<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
	$usuario_data = array(
        'name'  => 'username',
        'id'  => 'username',
        'value'  => '',
        'size'  => '50',
        'style'  => 'width:50%',
    );
    $password_data = array(
        'name'  => 'password',
        'id'  => 'password',
        'value'  => '',
        'size'  => '50',
        'style'  => 'width:50%',
    );
    $formClass = array(
        'class' => 'form-horizontal',
        'role' =>  'form',
        'autocomplete'=>'on',
        'id'=> 'Form_Usuario_Nuevo'
    );
    $hidden = array(
        'base_url' => BASE_URL);
/**
 *     foreach ($cuerpo->result() as $row){
 *         $cuerpo1 = $row->cuerpo;
 *     }
 */

?>
<!--
<!DOCTYPE HTML>
 
<html>
<head>
<link href="<?=base_url('assets/css/bootstrap.min.css') ?>	" rel="stylesheet" type="text/css" />

</head>
<body>
 -->

<!-- *******Contenido de la página **********************-->
<div class="col-md-12">
    <div class="row">
        <div class="col-md-6">                
            <div class="panel panel-primary">
                <div class="panel-heading text-center">
                    <b>Nuevo Usuario <?=$this->session->userdata('usuario')?></b>
                </div>
                <div class="panel-body">
                    <?=form_open('',$formClass, $hidden)?>
                    
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Usuario</label>
                        <div class="col-sm-8">
                            <?=form_input($usuario_data)?>
                        </div>           
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-8">
                            <?=form_password($password_data)?>
                        </div>           
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <input type="submit" id="Guardar" value="Guardar" class="btn btn-primary submit"/>
                            <input type="submit" value="Actualizar_escondido" class="btn btn-warning submit" id="update"/>
                            <input type="button" id="Cancelar" value="Cancelar" class="btn btn-danger Cancelar" /> 
                        </div> 
                    </div>                                             
                    <?=form_close();?>    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading text-center">
                    <b>Usuario</b>
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-condensed " id="tableTipouser">
                        <thead> 
                            <tr>
                                <th>UID</th>
                                <th>Login</th>
                                <th>Activo</th>
                                <th>Opciones</th>  
                            </tr>
                        </thead>  
                        <tbody>
                            
                            <?php foreach ($usuarios as $row){?>
                            <tr>
                                <td ><?php echo $row->uid; ?></td>
                                <td ><?php echo $row->login; ?></td>
                                <td ><?php echo $row->activo; ?></td>
                                <td >
                                <button type='button' data-uid="<?=$row->uid?>" data-login="<?=$row->login?>" data-toggle="tooltip" data-placement="top" title="Modificar Usuario" class='btn btn-success SelUsuario'><i class="glyphicon glyphicon-pencil"></i></button>
                                </td>
                            </tr> 
                        <?php 
                        } 
                        ?>
                        </tbody>
                    </table>                            
                </div>
            </div>
         </div>
    </div>
</div>
<!-- *******Fin de Contenido de la página****************-->




<script>
$(document).ready(function(){
    $("#update").hide(); // Oculta el boton Actualizar
    $("#Form_Usuario_Nuevo").on("submit",function(event){
        event.preventDefault(); //The event.preventDefault() method stops the default action of an element from happening.
        
        var username = $("#username").val();
        var password = $("#password").val();
        //alert($("[name = 'base_url']").val());
        var url;
        url=$("input[name = 'base_url']").val()+'/Usuario/Crear';
        $.ajax({    
            url:url,
			type:'post',
            data:'username='+username+'&password='+password,
            success:function(result){
                    switch(result){
                        case "0":
                            alert("No se insertó");
                            break;
                        case "1":
                            alert("Realizado con éxito");
                            //$("input[type=text]").val(""); // esto es para limpiar campos
                            location.reload();
                            break;
                    }//switch
                },
            error:function(result){
                    alert("Error");
                }
            
        });//$.ajax
        
    });//$("#Form_Usuario_Nuevo").on("submit",function(event){});
    //MODIFICAR UN USUARIO

    $(".SelUsuario").on("click",function(event){ // . Busca por clase
            //
			event.preventDefault();
            $("#update").val("Actualizar");  
            $("#update").show(1000);//Muestra el boton de Actualizar
            $("#Guardar").hide(1000); //Oculta el boton Guardar
            $("#username").val($(this).attr("data-login"));
    });//$(".SelUsuario").on("click",function(event){});
    
    $("#Cancelar").on("click",function(event){ // # Busca por ID
            //REGRESA TODO COMO ESTABA AL INICIO
			event.preventDefault();
            $("#Guardar").val("Guardar");
            $("input[type=text]").val("");
            $("input[type=password]").val("");
    });//$("Cancelar").on("click",function(event){});
});//$(document).ready(function(){});
</script>
<!--
</body>
</html>
-->