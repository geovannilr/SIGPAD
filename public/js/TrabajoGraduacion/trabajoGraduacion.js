$( document ).ready(function() {
   $( "#buscarAlumno" ).click(function() {
  		addAlumno($("#inputBuscar").val());
	});
});

//BUSCAMOS EL ALUMMNO PARA AGREGARLO AL GRUPO
function addAlumno(carnetAlumno){
	var card="";
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
              }else if (data.errorCode == 1){
              		swal("", data.errorMessage, "error");
              }
              
           }
        });      
}