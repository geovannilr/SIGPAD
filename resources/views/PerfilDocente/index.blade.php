@extends('template')

@section('content')
@if(Session::has('message'))
  		<script type="text/javascript">
  			$( document ).ready(function() {
    			swal("", "{{Session::get('message')}}", "success");
			});
  		</script>		
@endif
@if(Session::has('error'))
  		<script type="text/javascript">
  			$( document ).ready(function() {
    			swal("", "{{Session::get('error')}}", "error");
			});
  		</script>		
@endif
<script type="text/javascript">
	$( document ).ready(function() {
    	$("#academicaTable").DataTable({
    	responsive: true,	
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                title: 'Listado de Experiencia Académica'
            },
            {
                extend: 'pdfHtml5',
                title: 'Listado de Experiencia Académica'
            },
             {
                extend: 'csvHtml5',
                title: 'Listado de Experiencia Académica'
            },
            {
                extend: 'print',
                title: 'Listado de Experiencia Académica'
            }


        ],
         responsive: {
            details: {
                type: 'column'
            }
        },
        order: [ 1, 'desc' ],

    	});

		$(".deleteButton").submit(function( event ) {
			event.preventDefault();
    		var titulo;
   			var mensaje;
      		titulo ="Eliminar Publicación de Trabajo de graduación";
      		mensaje="Estas seguro que quiere eliminar esta publicación de trabajo de graduación?";
	        swal({
	            title: titulo,
	            text: mensaje, 
	            icon: "warning",
	            buttons: true,
	            successMode: true,
	        })
	        .then((aceptar) => {
	          if (aceptar) {
	            this.submit();
	          } else {
	            return;
	          }
	        });		
		});
	});
</script>
		<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a> Perfil Docente</h5>
	        </li>
				 <li class="breadcrumb-item active">Dashboard </li>
		</ol>
		<br>
		<ul class="nav nav-tabs" id="myTab" role="tablist">
		  <li class="nav-item">
		    <a class="nav-link active text-danger" id="home-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link text-danger" id="profile-tab" data-toggle="tab" href="#academica" role="tab" aria-controls="academica" aria-selected="false">Experiencia Académica</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link text-danger" id="contact-tab" data-toggle="tab" href="#laboral" role="tab" aria-controls="laboral" aria-selected="false">Experiencia Laboral</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link text-danger" id="contact-tab" data-toggle="tab" href="#certificaciones" role="tab" aria-controls="certificaciones" aria-selected="false">Certificaciones</a>
		  </li>
		   <li class="nav-item">
		    <a class="nav-link text-danger" id="contact-tab" data-toggle="tab" href="#habilidades" role="tab" aria-controls="habilidades" aria-selected="false">Habilidades</a>
		  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
	GENERAL
  </div>

  <div class="tab-pane fade" id="academica" role="tabpanel" aria-labelledby="academica-tab">
	<br>
 	<div class="table-responsive">
  			<table class="table table-hover table-striped">

  				<thead class="bg-danger text-white">
  					<th>Cargo</th>
					<th>Código</th>
					<th>Materia</th>
					<th>Ciclo</th>
					<th>Año</th>
					
  				</thead>
  				<tbody>
  				@foreach($academica as $aca)
  						<tr>
  						<td>{{ $aca->Cargo }}</td>	
						<td>{{ $aca->Codigo }}</td>
						<td>{{ $aca->Materia}}</td>
						<td>{{ $aca->Ciclo}}</td>
						<td>{{ $aca->anio}}</td>
						</tr>				
				@endforeach 
				</tbody>
			</table>
	   </div>
  </div>

  <div class="tab-pane fade" id="laboral" role="tabpanel" aria-labelledby="contact-tab">
  	<br>
 	<div class="table-responsive">
  			<table class="table table-hover table-striped">

  				<thead class="bg-danger text-white">
  					<th>Lugar de Trabajo</th>
					<th>Idioma</th>
					<th>Descripción</th>
					<th>Año Inicio</th>
					<th>Año Fin</th>
					
  				</thead>
  				<tbody>
  				@foreach($laboral as $labo)
  						<tr>
  						<td>{{ $labo->lugar_trabajo_dcn_exp }}</td>	
						<td>{{ $labo->idiomaExper }}</td>
						<td>{{ $labo->descripcionExperiencia}}</td>
						<td>{{ $labo->anio_inicio_dcn_exp}}</td>
						<td>{{ $labo->anio_fin_dcn_exp}}</td>
						</tr>				
				@endforeach 
				</tbody>
			</table>
	   </div>
  </div>

  <div class="tab-pane fade" id="certificaciones" role="tabpanel" aria-labelledby="certificaciones-tab">
  	<br>
 	<div class="table-responsive">
  			<table class="table table-hover table-striped">

  				<thead class="bg-danger text-white">
  					<th>Nombre</th>
					<th>Año</th>
					<th>Institución</th>
					<th>Idioma</th>
  				</thead>
  				<tbody>
  				@if(empty($certificaciones[0]->nombre_dcn_cer))
  				<tr><td colspan="4">NO HAY CERTIFICACIONES REGISTRADAS</td></tr>
  				@else
  					@foreach($certificaciones as $certificacion)
  						<tr>
  						<td>{{ $certificacion->nombre_dcn_cer }}</td>	
						<td>{{ $certificacion->anio_expedicion_dcn_cer }}</td>
						<td>{{ $certificacion->institucion_dcn_cer}}</td>
						<td>{{ $certificacion->idiomaCert}}</td>
						
						</tr>				
					@endforeach 
  				@endif	
				</tbody>
			</table>
	   </div>
  </div>
  <div class="tab-pane fade" id="habilidades" role="tabpanel" aria-labelledby="habilidades-tab">
  	<br>
 	<div class="table-responsive">
  			<table class="table table-hover table-striped">

  				<thead class="bg-danger text-white">
  					<th>Nombre</th>
					<th>Nivel</th>
  				</thead>
  				<tbody>
  				@if(empty($habilidades[0]->nombre_cat_ski))
  				<tr><td colspan="4">NO HAY HABILIDADES REGISTRADAS</td></tr>
  				@else
  					@foreach($habilidades as $habilidad)
  						<tr>
  						<td>{{ $habilidad->nombre_cat_ski }}</td>	
						<td>{{ $habilidad->Nivel }}</td>
						</tr>				
					@endforeach 
  				@endif	
				</tbody>
			</table>
	   </div>
  </div>
</div>

  		
@stop