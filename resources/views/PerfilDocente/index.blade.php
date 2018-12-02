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
<style type="text/css">
	body{
    padding-top: 68px;
    padding-bottom: 50px;
}
        .image-container {
            position: relative;
        }

        .image {
            opacity: 1;
            display: block;
            width: 100%;
            height: auto;
            transition: .5s ease;
            backface-visibility: hidden;
        }

        .middle {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }

        .image-container:hover .image {
            opacity: 0.3;
        }

        .image-container:hover .middle {
            opacity: 1;
        }
</style>
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
   			mensaje="Estas seguro que quiere eliminar este registro?";
   			if(this.id == "ACAD"){
   				titulo ="Eliminar Registro de historial Académico";
      			
   			}else if(this.id == "LAB"){
   				titulo ="Eliminar Registro de experiencia Laboral";
   			}else if (this.id == "CERT"){
   				titulo ="Eliminar Registro de Certificaciones";
   			}else{
   				titulo ="Eliminar Registro de Habilidades";
   			}
      		
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
{!! Form:: open(['route'=>'actualizarPerfilDocente','method'=>'POST','id'=>'formPerfilDocente','files'=>'true','enctype'=>'multipart/form-data']) !!}
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
  	<br>
  	<br>
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <div class="card-title mb-4">
                            <div class="d-flex justify-content-start">
                                <div class="image-container">
                                    <img src="{{$info[0]->dcn_profileFoto}}" id="imgProfile" style="width: 150px; height: 150px" class="img-thumbnail" />
                                    <div class="middle">
                                        <input type="button" class="btn btn-secondary" id="btnChangePicture" value="Cambiar" />
                                        <input type="file" style="display: none;" id="profilePicture" name="fotoPerfil" />
                                    </div>
                                </div>
                                <div class="userData ml-3">
                                    <h2 class="d-block" style="font-size: 1.5rem; font-weight: bold">
                                    	<a href="javascript:void(0);" class="text-danger">
                                    	{{$info[0]->primer_nombre." ".$info[0]->segundo_nombre." ".$info[0]->primer_apellido." ".$info[0]->segundo_apellido}}
                                    	</a>
                                    </h2>
                                    <h6 class="d-block">{{$info[0]->nombre_cargo}}</h6>
                                    <h6 class="d-block">{{$info[0]->email}}</h6>
                                
                                </div>
                                <div class="ml-auto">
                                    
                                    <a class="btn" id="btnEdit" style="background-color:  #102359;color: white" href="#"><i class="fa fa-pencil"></i></a>
                                    <a class="btn d-done" id="btnSave" style="background-color:  #102359;color: white" href="#"><i class="fa fa-save"></i></a>
                                    <a class="btn d-none btn-danger"  id="btnDiscard"  href="#"><i class="fa fa-ban"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        	 <p>
                                 {{$info[0]->descripcionDocente}}
                             </p>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active text-danger" id="basicInfo-tab" data-toggle="tab" href="#basicInfo" role="tab" aria-controls="basicInfo" aria-selected="true">Basica</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-danger" id="connectedServices-tab" data-toggle="tab" href="#connectedServices" role="tab" aria-controls="connectedServices" aria-selected="false">Redes</a>
                                    </li>
                                </ul>
                                <div class="tab-content ml-1" id="myTabContent">
                                    <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basicInfo-tab">
                                        

                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Nombre</label>
                                            </div>
                                            <div class="col-md-8 col-6">
                                             	<input type="text" name="nombre" id="nombreCompleto" class="form-control" value="{{$info[0]->primer_nombre." ".$info[0]->segundo_nombre." ".$info[0]->primer_apellido." ".$info[0]->segundo_apellido}}" readonly>
                                            </div>
                                        </div>
                                        <hr />

                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Cargo 1</label>
                                            </div>
                                            <div class="col-md-8 col-6">
                                                <select name="cargoPrincipal" class="form-control" id="cargoPrincipal" disabled>
                                                	<option value="0">Seleccione un cargo principal</option>
                                                	{!!$bodySelectPrincipal!!}
                                                	
                                                </select>
                                            </div>
                                        </div>
                                        <hr />
                                        
                                        
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Cargo 2</label>
                                            </div>
                                            <div class="col-md-8 col-6">
                                                <select name="cargoSegundario" class="form-control" id="cargoSegundario" disabled>
                                                	<option value="0">Seleccione un cargo secundario</option>
                                                	{!!$bodySelectSecundario!!}
                                                	
                                                </select>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Descripcion</label>
                                            </div>
                                            <div class="col-md-8 col-6">
                                                <textarea class="form-control" name="descripcion" id="descripcion" readonly cols="5" rows="7">
                              						{{$info[0]->descripcionDocente}}
                                                </textarea>
                                            </div>
                                        </div>
                                        <hr />
                                        
                                        <hr />

                                    </div>
                                    <div class="tab-pane fade" id="connectedServices" role="tabpanel" aria-labelledby="ConnectedServices-tab">
                                    	<div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                               <i class="fa fa-linkedin fa-w-9 fa-2x text-danger"></i>
                                            </div>
                                            <div class="col-md-8 col-6">
                                                <input type="text" name="linkedin" id="linkedin" class="form-control" value="{{$info[0]->link_linke}}" readonly>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <i class="fa fa-facebook-square fa-w-9 fa-2x text-danger"></i>
                                               
                                            </div>
                                            <div class="col-md-8 col-6">
                                             	<input type="text" name="fb" id="fb" class="form-control" value="{{$info[0]->link_fb}}" readonly>
                                            </div>
                                        </div>
                                        <hr />

                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                               <i class="fa fa-twitter fa-w-9 fa-2x text-danger"></i>
                                            </div>
                                            <div class="col-md-8 col-6">
                                                <input type="text" name="tw" id="tw" class="form-control" value="{{$info[0]->link_tw}}" readonly>
                                            </div>
                                        </div>
                                        <hr />
                                        
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <i class="fa fa-github-square fa-w-9 fa-2x text-danger"></i>
                                                
                                            </div>
                                             <div class="col-md-8 col-6">
                                                <input type="text" name="git" id="git" class="form-control" value="{{$info[0]->link_git}}" readonly>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
        <br>
    
  </div>

  <div class="tab-pane fade" id="academica" role="tabpanel" aria-labelledby="academica-tab">
	<br>
	<div class="row">
	  <div class="col-sm-3"></div>
	  <div class="col-sm-3"></div>
	  <div class="col-sm-3"></div>
	  @can('perfilDocente.create')
	    <div class="col-sm-3">
	      <a class="btn " href="{{route('academico.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo Registro</a>
	    </div>
	  @endcan
  	</div>
  	<br>
 	<div class="table-responsive">
  			<table class="table table-hover table-striped">

  				<thead class="bg-danger text-white">
  					<th>Cargo</th>
					<th>Código</th>
					<th>Materia</th>
					<th>Ciclo</th>
					<th>Año</th>
					<th>Acciones</th>
					
					
  				</thead>
  				<tbody>
  				@if(empty($academica[0]->id_dcn_his))
  					<tr><td colspan="6">NO SE ENCONTRARON REGISTROS DE HISTORIAL ACADEMICO</td></tr>
  				@else
	  				@foreach($academica as $aca)
	  						<tr>
		  						<td>{{ $aca->Cargo }}</td>	
								<td>{{ $aca->Codigo }}</td>
								<td>{{ $aca->Materia}}</td>
								<td>{{ $aca->Ciclo}}</td>
								<td>{{ $aca->anio}}</td>
								@can('perfilDocente.edit','perfilDocenteDestroy')
									<td>
										{!! Form::open(['route'=>['academico.destroy',$aca->id_dcn_his],'method'=>'DELETE','class' => 'deleteButton','id'=>'ACAD']) !!}
									 			@can('perfilDocente.edit')
									 				<a class="btn " style="background-color:  #102359;color: white" href="{{route('academico.edit',$aca->id_dcn_his)}}"><i class="fa fa-pencil"></i></a>
									 			@endcan	
									 			@can('perfilDocente.destroy')
													<button type="submit" class="btn btn-danger" ><i class="fa fa-trash"></i></button>
												@endcan
										{!! Form:: close() !!}
									</td>
								@endcan
							</tr>				
					@endforeach 
				@endif
				</tbody>
			</table>
	   </div>
  </div>

  <div class="tab-pane fade" id="laboral" role="tabpanel" aria-labelledby="contact-tab">
  	<br>
  	<div class="row">
	  <div class="col-sm-3"></div>
	  <div class="col-sm-3"></div>
	  <div class="col-sm-3"></div>
	  @can('perfilDocente.create')
	    <div class="col-sm-3">
	      <a class="btn " href="{{route('laboral.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo Registro</a>
	    </div>
	  @endcan
  	</div>
  	<br>
 	<div class="table-responsive">
  			<table class="table table-hover table-striped">

  				<thead class="bg-danger text-white">
  					<th>Lugar de Trabajo</th>
					<th>Idioma</th>
					<th>Descripción</th>
					<th>Año Inicio</th>
					<th>Año Fin</th>
					<th>Acciones</th>
  				</thead>
  				<tbody>
  				@if(empty($laboral[0]->id_dcn_exp))
  					<tr><td colspan="6">NO SE ENCONTRARON REGISTROS DE EXPERIENCIA LABORAL</td></tr>
  				@else
	  				@foreach($laboral as $labo)
	  						<tr>
	  						<td>{{ $labo->lugar_trabajo_dcn_exp }}</td>	
							<td>{{ $labo->idiomaExper }}</td>
							<td>{{ $labo->descripcionExperiencia}}</td>
							<td>{{ $labo->anio_inicio_dcn_exp}}</td>
							<td>{{ $labo->anio_fin_dcn_exp}}</td>
							@can('perfilDocente.edit','perfilDocenteDestroy')
									<td>
										<fieldset>
											{!! Form::open(['route'=>['laboral.destroy',$labo->id_dcn_exp],'method'=>'DELETE','class' => 'deleteButton','id'=>'LAB']) !!}
									 			@can('perfilDocente.edit')
									 				<a class="btn " style="background-color:  #102359;color: white" href="{{route('laboral.edit',$labo->id_dcn_exp)}}"><i class="fa fa-pencil"></i></a>
									 			@endcan	
									 			@can('perfilDocente.destroy')
													<button type="submit" class="btn btn-danger" ><i class="fa fa-trash"></i></button>
												@endcan
										{!! Form:: close() !!}
										</fieldset>
										
									</td>
							@endcan
							
							</tr>				
					@endforeach 
				@endif	
				</tbody>
			</table>
	   </div>
  </div>

  <div class="tab-pane fade" id="certificaciones" role="tabpanel" aria-labelledby="certificaciones-tab">
  	<br>
  	<div class="row">
	  <div class="col-sm-3"></div>
	  <div class="col-sm-3"></div>
	  <div class="col-sm-3"></div>
	  @can('perfilDocente.create')
	    <div class="col-sm-3">
	      <a class="btn " href="{{route('certificacion.create')}}" style="background-color: #DF1D20; color: white"><i class="fa fa-plus"></i> Nuevo Registro</a>
	    </div>
	  @endcan
  	</div>
  	<br>
 	<div class="table-responsive">
  			<table class="table table-hover table-striped">

  				<thead class="bg-danger text-white">
  					<th>Nombre</th>
					<th>Año</th>
					<th>Institución</th>
					<th>Idioma</th>
					<th>Acciones</th>
  				</thead>
  				<tbody>
  				@if(empty($certificaciones[0]->nombre_dcn_cer))
  				<tr><td colspan="5">NO SE ENCONTRARON REGISTROS DE CERTIFICACIONES</td></tr>
  				@else
  					@foreach($certificaciones as $certificacion)
  						<tr>
  						<td>{{ $certificacion->nombre_dcn_cer }}</td>	
						<td>{{ $certificacion->anio_expedicion_dcn_cer }}</td>
						<td>{{ $certificacion->institucion_dcn_cer}}</td>
						<td>{{ $certificacion->idiomaCert}}</td>
						@can('perfilDocente.edit','perfilDocenteDestroy')
									<td>
										<fieldset>
											{!! Form::open(['route'=>['certificacion.destroy',$certificacion->id_dcn_cer],'method'=>'DELETE','class' => 'deleteButton','id'=>'CERT']) !!}
									 			@can('perfilDocente.edit')
									 				<a class="btn " style="background-color:  #102359;color: white" href="{{route('certificacion.edit',$certificacion->id_dcn_cer)}}"><i class="fa fa-pencil"></i></a>
									 			@endcan	
									 			@can('perfilDocente.destroy')
													<button type="submit" class="btn btn-danger" ><i class="fa fa-trash"></i></button>
												@endcan
										{!! Form:: close() !!}
										</fieldset>
										
									</td>
							@endcan
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
{!! Form:: close() !!}

  		
@stop