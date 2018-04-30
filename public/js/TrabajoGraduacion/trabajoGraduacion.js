$( document ).ready(function() {
   $( "#buscarAlumno" ).click(function() {
   		if ($("#inputBuscar").val() == "") {
   			swal("", "Debe ingresar un carnet para buscar", "info");
   		}else if (alumnos.length == 5){
   			swal("Cantidad de Estudiantes máxima alcanzada", "La cantidad máxima de alumnos para conformar un grupo de trabajo de graduación es 5", "info");
   		}else if (estudianteRepetido($("#inputBuscar").val()) == 1){
   			swal("Estudiante Repetido!", "El estudiante ya se encuentra en tu grupo de trabajo de graduación", "info");
   		}else{
   			addAlumno($("#inputBuscar").val());
   		}
	});
});

//BUSCAMOS EL ALUMMNO PARA AGREGARLO AL GRUPO
var alumnos=[];
function addAlumno(carnetAlumno){
	var card="";
	var input="";
	
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
	              card+='<div class="col-sm-4">';
				  card+='<div class="card border-primary mb-3">';
				  card+='<h5 class="card-header"><b>'+data.msg[0].user+'</b> - '+data.msg[0].name+'</h5>';
				  card+='<div class="card-body">';
				  card+='<h5 class="card-title">Estado de Asignación</h5>';
				  card+='<p class="card-text">Pendiente de envío</p>';
				  card+='<a href="#" class="btn btn-danger">Remover</a>';
				  card+='</div></div></div>'; 
				  $("#estudiantes").append(card);
				  //AGREGAMOS EL ALUMNO AL ARREGLO PARA COMPARAR Y HACER VALIDACIONES
				  alumnos.push(carnetAlumno);
				  //CREAMOS EL INPUT QUE SE ENVIARÁ POR POST COMO ALUMNO
				  $("#estudiantes").append('<input type="hidden" name="estudiantes[]" id="'+carnetAlumno+'"/>');
				  console.log("estudiantes "+ alumnos);
				  swal("", data.errorMessage, "success");
              }else if (data.errorCode == 1){
              	  swal("", data.errorMessage, "error");
              }
              
           }
        });      
}
//FUNCION PARA VERIFICAR SI EL ALUMNO QUE QUEREMOS AGREGAR YA SE ENCUENTRA REPETIDO
function estudianteRepetido(carnet){
	var repetido=0;
	for (var i=0 ; i< alumnos.length ; i++){
		console.log("alumno arreglo "+alumnos[i]+" alumno ingresado "+carnet);
		if (alumnos[i] == carnet) {
			console.log("REPETIDO");
			repetido=1;
		}
	}
	return repetido;
}