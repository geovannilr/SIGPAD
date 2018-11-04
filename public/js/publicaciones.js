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
  var jurado = 0;
  var colaborador = 0;
  if ($('#checkAlumno').is(":checked")){
     autor = 1;
  }
  if ($('#checkDocente').is(":checked")){
     docenteDirector = 1;
  }
  if ($('#checkJurado').is(":checked")){
     jurado = 1;
  }
  if ($('#checkColaborador').is(":checked")){
     colaborador = 1;
  }
       $.ajax({
           type:'POST',
           url:getCurrentUrl()+'/buscarPublicaciones',
           data:{
            'texto':texto,
            'autor':autor,
            'docenteDirector':docenteDirector
           },
           success:function(data){
              console.log(data);
              $("#resultadoBusqueda").empty();
              $("#resultadoBusqueda").html(data);
             
           },
    		error : function(xhr, status) {
        		swal("", "Hubo un problema con el servicio", "error");
    		}
        });     
}