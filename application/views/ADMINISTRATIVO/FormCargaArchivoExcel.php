<!-- *******Contenido de la página **********************-->
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">                
            <div class="panel panel-primary">
                <div class="panel-heading text-center">
                    <b>CARGA DE ESTUDIANTES PARA PDG Y PSS MEDIANTE ARCHIVO EXCEL</b>
                </div>
                <div class="panel-body">
                	<p class="text-danger"><?php echo $mensaje;?></p>
                    
					<?php echo form_open_multipart('ADMINISTRATIVO/CargaArchivoExcel/CargaArchivo');?>
						<div class="form-group">
        					<label class="col-md-6 control-label" for="userfile">Seleccione el Archivo de Excel</label>  
	        				<div class="col-md-4">
	        					<input type="file" name="userfile" size="20" />
	        				</div>
      					</div>

						

						<br /><br />
			      		<div class="form-group">
        					<label class="col-md-4 control-label" for="aceptar"></label>
    						<div class="col-md-8">
          						<input class="btn btn-primary" type="submit" value="Subir Archivo" />
          
        					</div>
      					</div>
						

					</form>
                </div>
            </div>
        </div>
        

    </div>
</div>
<!-- *******Fin de Contenido de la página****************-->



<h2></h2>

