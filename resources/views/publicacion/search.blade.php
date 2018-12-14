@extends('template')

@section('content')

		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>Publicaciones TDG</h5>
	        </li>
	        <li class="breadcrumb-item active">Búsqueda de Publicaciones de Trabajo de graduación</b></li>
		</ol>
		<br>
  		 <div class="row">
            <div class="form-group col-sm-9">
              <input type="text" name="txtBuscarPublicaciones" class="form-control" placeholder="Búsqueda  de Publicaciones" id="inputBuscarPublicaciones">
            </div>
            <div class="form-group col-sm-3">
              <input type="button" value="Buscar" class="btn btn-primary" id="btnBuscarPublicaciones">
            </div>
        </div>
         <div class="row">
         	<p><b>&nbsp;&nbsp;&nbsp;Seleccione el rol por el cúal quiere realizar la búsqueda de publicaciones</b></p><br>
            <div class="form-group col-sm-3">
            	<div class="form-check">
				    <input type="checkbox" class="form-check-input" name="checkAlumno"  id="checkAlumno">
				    <label class="form-check-label" for="checkAlumno">Autor</label>
  				</div>
             	<div class="form-check">
				    <input type="checkbox" class="form-check-input" name="checkDocente"  id="checkDocente">
				    <label class="form-check-label" for="checkDocente">Director</label>
  				</div>
  				<div class="form-check">
				    <input type="checkbox" class="form-check-input" name="checkCoordinador"  id="checkCoordinador">
				    <label class="form-check-label" for="checkCoordinador">Coordinador</label>
  				</div>
  				
            </div>
             <div class="form-group col-sm-3">
  				<div class="form-check">
				    <input type="checkbox" class="form-check-input" name="checkJurado"  id="checkJurado">
				    <label class="form-check-label" for="checkJurado">Observador</label>
  				</div>
  				<div class="form-check">
				    <input type="checkbox" class="form-check-input" name="checkAsesor"  id="checkAsesor">
				    <label class="form-check-label" for="checkAsesor">Asesor</label>
  				</div>
            </div>
            
        </div>
        <br>
        <br>
        <div id="resultadoBusqueda"></div>
		<div id="divDownloadFrm" style="visibility: hidden;">
			{!! Form::open(['route'=>['downloadDocumentoPublicacion'],'method'=>'POST', 'id' => 'downloadFrm', 'target' => '_blank']) !!}
				<input class="form-control" id="idPubArc" name="archivo" type="hidden" value="-1">
			{!! Form:: close() !!}
		</div>
		<script type="text/javascript">
			function downloadDocument(id){
			    if(id!==-1){
			        $("#idPubArc").val(id);
			        $("#downloadFrm").submit();
				}else{
                    swal("","No puedes descargar el archivo todavía","warning");
				}
			}
		</script>
@stop