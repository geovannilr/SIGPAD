	
	<aside class="right-side strech">
	
                <div class="panel-body">
                    <?php $formClass=array('class'=>'form-horizontal','role'=>'form','autocomplete'=>'on','id' =>'FrmCargarArchivo');?>
					<?php $hidden = array('base_url' => BASE_URL);?>
  					<?=form_open('',$formClass,$hidden )?>
					<fieldset>
<<<<<<< .mine
					<legend>Carga de Instituciones</legend
                 
                       
                        <div class="col-sm-5">
||||||| .r74
					<legend>Carga de Instituciones</legend>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">NIT</label>
                        <div class="col-sm-5">
                            <input id="nit" name="nit" type="text" class="form-control input-md" required="NIT Obligatorio" >
                        </div>           
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Adjuntar Archivo</label>
                        <div class="col-sm-5">
=======
					<legend>Carga de Instituciones</legend>
                    
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Adjuntar Archivo</label>
                        <div class="col-sm-5">
>>>>>>> .r78
                            <p>
                              <input id="file_save" name="file_save" class="input-file" type="file">
                            </p>
                           
                        </div>

                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <input type="submit" id="Ingresar" value="Ingresar" class="btn btn-primary submit"/>
							<!--<input type="button" id="Contacto" value="Contacto" class="btn btn-primary submit" onclick="location='../../controllers/PSS/Contacto.php'"/>-->
							<!--<input type="submit" id="Modalidad" value="Modalidad" class="btn btn-primary submit"/>
							<input type="button" id="Cancelar" value="Limpiar" class="btn btn-warning" /> -->
                            <input type="button" id="Cancelar" value="Cancelar" class="btn btn-danger Cancelar" /> 
                        </div> 
                    </div>    
					 <?=form_close();?>       
					</fieldset>    
					</aside>      

<script>
$(document).ready(function(){
    $("#FrmInstitucion").on("submit",function(event){
        event.preventDefault(); //The event.preventDefault() method stops the default action of an element from happening.
        alert("antes de la declaracion de variabeñls");
        var nit = $("#nit").val();
        var nombre = $("#nombre").val();
        var rubro = $("#rubro").val();
        var tipo = $("#tipo").val();
        var direccion = $("#direccion").val();
        var telefono = $("#telefono").val();
        var email = $("#email").val();
		var estado = $("#estado").val();
        var url;
        //url='PDG/Perfil/Create';
        url=$("input[name = 'base_url']").val()+'/PSS/Institucion/Create';
        $.ajax({
            url:url,
            type:'post',
            data:'nit='+nit+'&nombre='+nombre+'&rubro='+rubro+'&tipo='+tipo+'&direccion='+direccion+'&direccion='+direccion+'&telefono='+telefono+'&email='+email+'&estado='+estado,
            success:function(result){
                    switch(result.trim()){
                        case "0":
                            alert(result);
                            alert("No se insertó");
                            break;
                        case "1":
                            alert(result);
                            alert("Realizado con éxito");
                            //$("input[type=text]").val(""); // esto es para limpiar campos
                            location.reload();
                            break;
                    }//switch
                },
            error:function(result){
                    alert("Error");
                    alert(result);
                }
            
        });//$.ajax
    });//$("#Frmperfil").on("submit",function(event){});
        

});//$(document).ready(function(){});
</script>                           
                    
				 