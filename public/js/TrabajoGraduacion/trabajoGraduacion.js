$( document ).ready(function() {
   $( "#buscarAlumno" ).click(function() {
   		if ($("#inputBuscar").val() == "") {
   			swal("", "Debe ingresar un carnet para buscar", "info");
   		}else if (alumnos.length == 5){
   			swal("Cantidad de Estudiantes máxima alcanzada", "La cantidad máxima de alumnos para conformar un grupo de trabajo de graduación es 5", "info");
   		}else if (estudianteRepetido($("#inputBuscar").val()) == 1){
   			swal("Estudiante Repetido!", "El estudiante ya se encuentra en tu grupo de trabajo de graduación", "info");
   		}else{
   			addAlumno($("#inputBuscar").val(),1);
   		}
	});
});

//BUSCAMOS EL ALUMMNO PARA AGREGARLO AL GRUPO
var alumnos=[];
function addAlumno(carnetAlumno,tipo){
	var card="";
	var input="";
	$("#buscarAlumno").attr('disabled', 'disabled');
	//AGARRAMOS TOKEN DE SESION
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	//SETEAMOS LOS DATOS A ENVIAR VIA POST
	var carnet = carnetAlumno;
        $.ajax({
           type:'POST',
           url:'http://localhost/SIGPAD/public/getAlumno',
           data:{'carnet':carnet},
           success:function(data){
              //console.log(data);

              if (data.errorCode == 0) {
	              card+='<div class="col-sm-4" id="card'+data.msg[0].carnet_estudiante+'">';
				  card+='<div class="card border-primary mb-3">';
				  if (tipo == 1){
				  	card+='<h5 class="card-header"><b>'+data.msg[0].carnet_estudiante.toUpperCase()+'</b> - '+data.msg[0].nombre_estudiante+'</h5>';
				  }else{
				  	card+='<h5 class="card-header"><b>'+data.msg[0].carnet_estudiante.toUpperCase()+'</b> - '+data.msg[0].nombre_estudiante+' <span class="badge badge-info">LIDER</span> </h5>';
				  }
				  
				  card+='<div class="card-body">';
				  card+='<h5 class="card-title">Estado de Asignación</h5>';
				  card+='<p class="card-text">Pendiente de envío</p><br>';
				  if (tipo==1){
				  	card+='<a onclick="removerAlumno('+data.msg[0].carnet_estudiante+');" class="btn btn-danger"><i class="fa fa-remove"></i></a>';
				  }else{
				  	card+='<i class="fa fa-remove"></i>';
				  }
				  card+='</div></div></div>'; 
				  $("#estudiantes").append(card);
				  //AGREGAMOS EL ALUMNO AL ARREGLO PARA COMPARAR Y HACER VALIDACIONES
				  alumnos.push(carnetAlumno);
				  //CREAMOS EL INPUT QUE SE ENVIARÁ POR POST COMO ALUMNO
				  $("#estudiantes").append('<input type="hidden" name="estudiantes[]" id="'+carnetAlumno+'" value="'+carnetAlumno+'"/>');
				  //console.log("estudiantes "+ alumnos);
				  if (tipo==1){
				  	swal("", data.errorMessage, "success");
				  }
				  $("#buscarAlumno").removeAttr('disabled');
              }else if (data.errorCode == 1){
              	  swal("", data.errorMessage, "error");
              	  $("#buscarAlumno").removeAttr('disabled');
              }
              
           }
        });      
}
//FUNCION PARA VERIFICAR SI EL ALUMNO QUE QUEREMOS AGREGAR YA SE ENCUENTRA REPETIDO
function estudianteRepetido(carnet){
	var repetido=0;
	for (var i=0 ; i< alumnos.length ; i++){
		//console.log("alumno arreglo "+alumnos[i]+" alumno ingresado "+carnet);
		if (alumnos[i] == carnet) {
			console.log("REPETIDO");
			repetido=1;
		}
	}
	return repetido;
}
function removerAlumno(elemento){
	console.log(elemento.id);
	console.log(alumnos);
	for (var i=0 ; i< alumnos.length ; i++){
		if (alumnos[i]==elemento.id) {
			var indice = alumnos.indexOf(elemento.id); // obtenemos el indice
			console.log("Indice"+ indice);
			if (indice > -1) {
    			alumnos.splice(indice, 1);
			}
			
			$("#card"+elemento.id).remove();
			$("#"+elemento.id).remove();
			swal("", "Alumno removido", "info");
			console.log("Alumnos despues de remover"+ alumnos);
		}
	}
}