<?php 
	$editoradd = $this->uri->segment(4, 0);
    //echo $editoradd;
    if($editoradd !== 'add' and $editoradd !== 'edit' and $editoradd !== 'read'){
		$this->load->view("templates/header");

?>
<div style='height:20px;'></div>  
<div class="container-fluid"><!-- conteiner principal -->
    <div class="row"><!-- row principal -->
    		<div class="col-sm-2 col-md-2"> <!-- 3 de 12 unidades Boostrap para menu lateral izquierdo -->
				<?php
					
					$this->load->view("templates/menu"); 
					
				?>
	        </div>
	        <div class="col-sm-10 col-md-10"><!-- 9 de 12 unidades Boostrap para Contenido Principal -->
            	<div class="container-fluid">				
				<?php 	
					$this->load->view($contenido,$output);
				?>
            	</div>
        	</div>
    </div> <!-- row principal -->
</div><!-- conteiner principal -->
<?php

		$this->load->view("templates/footer");
	}
	else{
		$this->load->view($contenido,$output);
	}	
?>


