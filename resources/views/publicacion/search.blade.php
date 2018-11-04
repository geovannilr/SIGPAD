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
            <div class="form-group col-sm-3">
            	<div class="form-check">
				    <input type="checkbox" class="form-check-input" name="checkAlumno"  id="checkAlumno">
				    <label class="form-check-label" for="checkAlumno">Autor</label>
  				</div>
             	<div class="form-check">
				    <input type="checkbox" class="form-check-input" name="checkDocente"  id="checkDocente">
				    <label class="form-check-label" for="checkDocente">Docente Director</label>
  				</div>
  				
            </div>
             <div class="form-group col-sm-3">
  				<div class="form-check">
				    <input type="checkbox" class="form-check-input" name="checkJurado"  id="checkJurado">
				    <label class="form-check-label" for="checkJurado">Jurado</label>
  				</div>
  				<div class="form-check">
				    <input type="checkbox" class="form-check-input" name="checkColaborador"  id="checkColaborador">
				    <label class="form-check-label" for="checkColaborador">Colaborador</label>
  				</div>
            </div>
            
        </div>
        <br>
        <br>
        <div id="resultadoBusqueda"></div>
@stop