$( document ).ready(function() {
 
  $( "#btnBuscarPublicaciones" ).click(function() {
    buscarPublicaciones();
  });
});

function buscarPublicaciones(){ 
	
	//AGARRAMOS TOKEN DE SESION
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	//SETEAMOS LOS DATOS A ENVIAR VIA POST
  var texto = $("#inputBuscarPublicaciones").val();
  var autor = 0;
  var docenteDirector = 0;
  var observador = 0;
  var asesor = 0;
  var coordinador = 0;
  if ($('#checkAlumno').is(":checked")){
     autor = 1;
  }
  if ($('#checkDocente').is(":checked")){
     docenteDirector = 1;
  }
  if ($('#checkJurado').is(":checked")){
     observador = 1;
  }
  if ($('#checkAsesor').is(":checked")){
     asesor = 1;
  }
  if ($('#checkCoordinador').is(":checked")){
     coordinador = 1;
  }
    if (texto=="") {
      swal("Búsqueda", "Por favor ingrese el parámetro de búsqueda", "warning");
    }else if(docenteDirector==0 && autor==0 && observador==0 && coordinador==0){
      swal("Búsqueda", "Por favor seleccione una o más roles de búsqueda", "warning");
    }else{
       $.ajax({
           type:'POST',
           url:getCurrentUrl()+'/buscarPublicaciones',
           data:{
            'texto':texto,
            'autor':autor,
            'docenteDirector':docenteDirector,
            'observador':observador,
            'asesor':asesor,
            'coordinador':coordinador
           },
           success:function(data){
              //console.log(data);
              $("#resultadoBusqueda").empty();
              $("#resultadoBusqueda").html(data);
             
           },
        error : function(xhr, status) {
            swal("", "Hubo un problema con el servicio", "error");
        }
        });    
    }
        
}