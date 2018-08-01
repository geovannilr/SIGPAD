$( document ).ready(function() {
  $('.documento').change(function (){ //VALIDAR A LA HORA DE SUBIR UN DOCUMENTO EL TAMAÑO
     var sizeByte = this.files[0].size;
     var siezekiloByte = parseInt(sizeByte / 1024);
     var nombre = $(this).val();
     var extension = nombre.substring(nombre.lastIndexOf('.') + 1).toLowerCase();
     console.log("Extension "+nombre.substring(nombre.lastIndexOf('.') + 1).toLowerCase());
     if(siezekiloByte > 3094){
           swal("", "El tamaño documento no debe ser mayor a 3 MB", "error");
           $(this).val('');
     }else {
          if(!(extension =='docx' || extension =='pdf' || extension =='doc')){
            swal("", "Solo se permiten documentos de formato .pdf, .docx, .doc", "error");
            $(this).val('');
          }

     }
   });
  $('.documentoPublicacion').change(function (){ //VALIDAR A LA HORA DE SUBIR UN DOCUMENTO EL TAMAÑO
     var sizeByte = this.files[0].size;
     var siezekiloByte = parseInt(sizeByte / 1024);
     var nombre = $(this).val();
     var extension = nombre.substring(nombre.lastIndexOf('.') + 1).toLowerCase();
     console.log("Extension "+nombre.substring(nombre.lastIndexOf('.') + 1).toLowerCase());
     if(siezekiloByte > 10240){
           swal("", "El tamaño documento no debe ser mayor a 10 MB", "error");
           $(this).val('');
     }else {
          if(!(extension =='docx' || extension =='pdf' || extension =='doc')){
            swal("", "Solo se permiten documentos de formato .pdf, .docx, .doc", "error");
            $(this).val('');
          }

     }
   });
    $("#formPrePerfil").submit(function( event ) {
      $("#loader").removeAttr('style');
    });
    $("#formDocumento").submit(function( event ) {
      $("#loader").removeAttr('style');
    });
   $( "#buscarAlumno" ).click(function() {
   		if ($("#inputBuscar").val() == "") {
   			swal("", "Debe ingresar un carnet para buscar", "info");
   		}else if (alumnos.length == 5){
   			swal("Cantidad de Estudiantes máxima alcanzada", "La cantidad máxima de alumnos para conformar un grupo de trabajo de graduación es 5", "info");
   		}else if (estudianteRepetido($("#inputBuscar").val()) == 1){
   			swal("Estudiante Repetido!", "El estudiante ya se encuentra en tu grupo de trabajo de graduación", "info");
   		}else{
   			//addAlumno($("#inputBuscar").val(),1);
   			verificarGrupo($("#inputBuscar").val(),1);
   		}
	});
   $("#btnConfirmar").click(function() {
   	//console.log(this.dataset.id);
   		alertConfirmarGrupo(this.dataset.id,1);
   	});
   $("#btnDenegar").click(function() {
   		alertConfirmarGrupo(this.dataset.id,2);
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
           url:'http://'+getUrl()+'/SIGPAD/public/getAlumno',
           data:{'carnet':carnet},
           success:function(data){
              //console.log(data);
              if (data.errorCode == 0) {
	              card+='<div class="col-sm-4" id="card'+data.msg[0].carnet_gen_est+'">';
				  card+='<div class="card border-primary mb-3">';
				  if (tipo == 1){
				  	card+='<h5 class="card-header"><b>'+data.msg[0].carnet_gen_est.toUpperCase()+'</b> - '+data.msg[0].nombre_gen_est+'</h5>';
				  }else{
				  	card+='<h5 class="card-header"><b>'+data.msg[0].carnet_gen_est.toUpperCase()+'</b> - '+data.msg[0].nombre_gen_est+' <span class="badge badge-info">LIDER</span> </h5>';
				  }
				  
				  card+='<div class="card-body">';
				  card+='<h5 class="card-title">Estado de Asignación</h5>';
				  card+='<p class="card-text">Pendiente de envío</p><br>';
				  if (tipo==1){
				  	card+='<a onclick="removerAlumno('+data.msg[0].carnet_gen_est+');" class="btn btn-danger"><i class="fa fa-remove"></i></a>';
				  }else{
				  	
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
              
           },
    		error : function(xhr, status) {
        		swal("", "Hubo un problema al momento de agregar el estudiante", "error");
        		$("#buscarAlumno").removeAttr('disabled');
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

function verificarGrupo(carnetAlumno,tipo){ // FUNCIONPARA VERIFICAR SI TIENE  GRUPO EL ALUMNO
	console.log("idAlumno "+carnetAlumno);
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
           url:'http://'+getUrl()+'/SIGPAD/public/verificarGrupo',
           data:{'carnet':carnet},
           success:function(data){
              //console.log(data);
              if (data.errorCode == 0) {
	            swal("", "El Alumno ya pertenece a otro grupo de trabajo de graduación", "info");
	            $("#inputBuscar").val("");
              }else if (data.errorCode == 1){
              	addAlumno(carnet,tipo);
              }
              
           },
    		error : function(xhr, status) {
        		swal("", "Hubo un problema con el servicio", "error");
    		}
        });      
}

function confirmarGrupo(idAlumno,flag){ // FUNCION PARA QUE EL ALUMNO ACEPTE PERTENECER A UN GRUPO TE TRABAJO DE GRADUACION
	//FLAG 1 ACEPTAR, FLAG 2 RECHAZAR
	//AGARRAMOS TOKEN DE SESION
	console.log("idAlumno "+idAlumno);
	var aceptar;
	if (flag == 1 ) {
		aceptar = 1;
	}else{
		aceptar =0;
	}
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
        $.ajax({
           type:'POST',
           url:'http://'+getUrl()+'/SIGPAD/public/confirmarGrupo',
           data:{'id':idAlumno,'aceptar':aceptar},
           success:function(data){
              console.log(data);
              if (data.errorCode == 0) {
	            swal({
		            title: "",
		            text: data.errorMessage, 
		            icon: "success",
		            confirmButtonClass: "btn-info",
		            successMode: true,
	        	})
	        	.then((aceptar) => {
		          if (aceptar) {
		           	location.reload();
		          } else {
		            
		          }
	        	});
		           //	swal("", data.errorMessage, "success");
		           //	location.reload();
              }else if (data.errorCode == 1){
              	swal("", data.errorMessage, "error");
              	console.log(data.msg);
              }
              
           },
    		error : function(xhr, status) {
        		swal("", "Hubo un problema al tratar de confirmar Grupo de trabajo de graduación", "error");
    		}
        });      
}
function alertConfirmarGrupo(idAlumno,flag){
	 var titulo;
	 var mensaje;
	 	if (flag ==1){
	 		titulo ="Confirmar Grupo";
	 		mensaje="Estas seguro que quieres formar parte de este grupo de trabajo de graduación?";
	 	}else{
	 		titulo ="Rechazar Grupo";
	 		mensaje="Estas seguro que quieres rechazar este grupo de trabajo de graduación?";
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
           	confirmarGrupo(idAlumno,flag);
          } else {
            //swal("Your imaginary file is safe!");
          }
        });
}
function getGrupo(idGrupo){ // Traer el detalle del grupo
	//AGARRAMOS TOKEN DE SESION
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
        $.ajax({
           type:'get',
           url:'http://'+getUrl()+'/SIGPAD/public/grupo/'+idGrupo,
           success:function(data){
            
              $("#modalDetalleBody").html(data.htmlCode);
              $("#divBoton").html(data.btnHtmlCode);
//EJRG begin
              $("#divBtnEditarGrupo").html(data.btnEditHtmlCode);
// EJRG end
              //$("#divBoton").append('<input type="hidden" name="idGrupo" value='+data.idGrupo);
             // $("#divBoron").append('<button type="submit" class="btn btn-primary">Aprobar</button>');
              $("#detalleGrupo").modal();
           },
    		error : function(xhr, status) {
        		swal("", "Hubo un problema al obtener el detalle del grupo de trabajo de graduación", "error");
    		}
        });      
}
function validarArchivoPerfil(){

}
function getDisponibles(rootUrl,anio){
    var tblModal = $('#tblEstSinGrupo');
    if(tblModal.length>0){
        $("#modalAgregarAlumno").modal();
    }else{
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'get',
            url:rootUrl+'/estSinGrupo/'+anio,
            success:function(data){
                console.log(data);
                modalAgregarAlumno(data);
                /*            $("#modalDetalleBody").html(data.htmlCode);
                            $("#divBoton").html(data.btnHtmlCode);
                //EJRG begin
                            $("#divBtnEditarGrupo").html(data.btnEditHtmlCode);
                // EJRG end
                            //$("#divBoton").append('<input type="hidden" name="idGrupo" value='+data.idGrupo);
                            // $("#divBoron").append('<button type="submit" class="btn btn-primary">Aprobar</button>');
                            $("#detalleGrupo").modal();*/
            },
            error : function(xhr, status) {
                swal("", "Hubo problemas!", "error");
            }
        });
    }
    return true;
}
function modalAgregarAlumno(data){
	if(data.length>0){
        var table = "<table class='table table-hover table-striped  display' id='tblEstSinGrupo'>";
        table += '<thead><th>Carnet</th><th>Nombre</th><th>Agregar</th></thead><tbody>';
        for (var obj in data) {
            table += "<tr>"
                +"<td>"+data[obj].carnet_gen_est+"</td>"
                +"<td>"+data[obj].nombre_gen_est+"</td>"
                +"<td><button class='btn btn-dark' onclick=agregarAlumno('"+data[obj].id_gen_est+"',vg_id_pdg_gru)><i class='fa fa-check'></i></button></td>"
                +"</tr>";
        }
        table += '</tbody></table>';
        $('#modalDetalleBody').html(table);
	}
    $("#modalAgregarAlumno").modal();
}

function agregarAlumno(idEst,idGru){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type:'POST',
        url: vg_url+'/addAlumno',
		data: {'id':idEst,'grupo':idGru},
        success:function(data){
            if(data.errorCode == 0){
            	redirectTo(vg_url+'/verGrupo','POST','idGrupo',idGru);
			}else{
            	console.log(data.errorMessage);
			}
        },
        error : function(xhr, status) {
            swal("", "Problemas al procesar su solicitud!", "error");
        }
    });
}

function  redirectTo(url,method,param,data) {
	var token = $('meta[name="csrf-token"]').attr('content');
    var form = $('<form action="' + url + '" method="'+ method +'">' +
        '<input type="hidden" name="'+ param +'" value="' + data + '"></input>'+
    	'<input type="hidden" name="_token" value="' + token + '"></input>'+ '</form>');
    $('body').append(form);
    $(form).submit();
}