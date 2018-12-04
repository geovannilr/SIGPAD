var ip ="http://localhost/SIGPAD/public";
function getHistorialAcademico(idDcn){
	$.ajax({
           type:'POST',
           url:ip+'/getHistorial',
           data:{'docente':idDcn},
           success:function(data){
            console.log(data.length);
             var html = '<table class="table table-striped"><thead><tr><th scope="col">#</th><th scope="col">Cargo</th><th scope="col">´Código</th><th scope="col">Materia</th><th scope="col">Ciclo</th><th scope="col">Año</th> </tr></thead><tbody>';
 
             for (var i = 0;i<data.length;i++) {
           		body="";
             	body+='<tr><th scope="row">'+(i+1)+'</th>';
             	body+='<td>'+data[i]['Cargo']+'</td>';
             	body+='<td>'+data[i]['Codigo']+'</td>';
             	body+='<td>'+data[i]['Materia']+'</td>';
             	body+='<td>'+data[i]['Ciclo']+'</td>';
             	body+='<td>'+data[i]['anio']+'</td></tr>';
             	html+=body;
             }

             html+=' </tbody></table>';
             $("#seccionHistorial").append(html)

           },
    		error : function(xhr, status) {
        		alert("Hubo un problema al momento de obetener los datos de Docente");
        		
    		}
        });      
}

function getExperienciaDocente(idDcn){
	$.ajax({
           type:'POST',
           url:ip+'/getExperiencia',
           data:{'docente':idDcn},
           success:function(data){
            console.log(data.length);
             var html = "";
             for (var i = 0;i<data.length;i++) {
           		html="";
             	html+='<div class="resume-item d-flex flex-column flex-md-row mb-5">';
             	html+='<div class="resume-content mr-auto">';
             	html+='<h3 class="mb-0">'+data[i]['lugar_trabajo_dcn_exp']+'</h3>';
             	html+='<div class="subheading mb-3">'+data[i]['idiomaExper']+'</div>';
             	html+='<div>'+data[i]['descripcionExperiencia']+'</div>';
             	//html+='<p>GPA: 3.23</p>';
             	html+='</div><div class="resume-date text-md-right">';
             	html+='<span class="text-primary">Período '+data[i]['anio_inicio_dcn_exp']+' - '+data[i]['anio_fin_dcn_exp']+'</span> </div></div>';
             	$("#seccionExperiencia").append(html);
             }

           },
    		error : function(xhr, status) {
        		alert("Hubo un problema al momento de obetener los datos de Docente");
        		
    		}
        });      
}

function getCertificacionesDocente(idDcn){
	$.ajax({
           type:'POST',
           url:ip+'/getCertificaciones',
           data:{'docente':idDcn},
           success:function(data){
            console.log(data.length);
             var html = '<table class="table table-striped"><thead><tr><th scope="col">#</th><th scope="col">Nombre</th><th scope="col">Año</th><th scope="col">Institución</th><th scope="col">Idioma</th></tr></thead><tbody>';
 
             for (var i = 0;i<data.length;i++) {
           		body="";
             	body+='<tr><th scope="row">'+(i+1)+'</th>';
             	body+='<td>'+data[i]['nombre_dcn_cer']+'</td>';
             	body+='<td>'+data[i]['anio_expedicion_dcn_cer']+'</td>';
             	body+='<td>'+data[i]['institucion_dcn_cer']+'</td>';
             	body+='<td>'+data[i]['idiomaCert']+'</td>';
             	html+=body;
             }

             html+=' </tbody></table>';
             $("#seccionCertificaciones").append(html)

           },
    		error : function(xhr, status) {
        		alert("Hubo un problema al momento de obetener los datos de Docente");
        		
    		}
        });      
}
	
function getSkillsDocente(idDcn){
	$.ajax({
           type:'POST',
           url:ip+'/getSkills',
           data:{'docente':idDcn},
           success:function(data){
            console.log(data.length);
             var html = '<ul class="fa-ul mb-0">';
             for (var i = 0;i<data.length;i++) {
           		body="";
             	body+='<li><i class="fa-li fa fa-check"></i>'+data[i]['nombre_cat_ski']+' - '+data[i]['Nivel']+'</li>';
             	html+=body;
             }

             html+='</ul>';
             $("#seccionSkills").append(html)

           },
    		error : function(xhr, status) {
        		alert("Hubo un problema al momento de obetener los datos de Docente");
        		
    		}
        });      
}
	
function getListadoDocente(idJornada){
  $.ajax({
           type:'POST',
           url:ip+'/getListadoDocentes',
           data:{'jornada':idJornada},
           success:function(data){
            console.log(data);
             var html = '<ul class="fa-ul mb-0">';
             for (var i = 0;i<data.length;i++) {
              body="";
             body+='<li><i class="fa-li fa fa-check"></i>'+data[i]['primer_nombre']+'</li>' + '<img class="img-circle" style="width: 100px;height:100px;" src= "'+data[i]['dcn_profileFoto']+' "></img></p>';
              html+=body;
             }

             html+='</ul>';
            $("#seccionListado").append(html)

           },
        error : function(xhr, status) {
            alert("Hubo un problema al momento de obetener los datos de Docente");
            
        }
        });      
}

//Administracion de perfil docente

$( document ).ready(function() {
    //CREAR REGISTRO ACADEMICO
    $("#datepicker").datepicker( {
    format: " yyyy", // Notice the Extra space at the beginning
    viewMode: "years", 
    minViewMode: "years"
    }).on('changeDate', function(e){
    $(this).datepicker('hide');

    });

    //CREAR CERTIFICACIONES
    $("#from").datepicker({
        format: 'yyyy',
        autoclose: 1,
        locale:'es',
        viewMode: "years", 
        minViewMode: "years",
        todayHighlight: false,
        //endDate: new Date()
    }).on('changeDate', function (selected) {
        $(this).datepicker('hide');
    });

    //CREAR EXPERIENCIA LABORAL
    $("#fromIni").datepicker({
        format: 'yyyy',
        autoclose: 1,
        locale:'es',
        viewMode: "years", 
        minViewMode: "years",
        todayHighlight: false,
        //endDate: new Date()
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#toFin').datepicker('setStartDate', minDate);
        $("#toFin").val($("#fromIni").val());
        $(this).datepicker('hide');
    });

    $("#toFin").datepicker({
        format: 'yyyy',
        todayHighlight: true,
        locale:'es',viewMode: "years", 
        minViewMode: "years"
    }).on('changeDate', function (selected) {
        $(this).datepicker('hide');
    });

    $
    $('#btnSave').addClass('d-none');
    $('#btnDiscard').addClass('d-none');
    $('#btnChangePicture').addClass('d-none');
    $imgSrc = $('#imgProfile').attr('src');
    function readURL(input) {

      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#imgProfile').attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
      }
    }
    $('#btnChangePicture').on('click', function () {
      /*if (!$('#btnChangePicture').hasClass('changing')) {
          
      }else {
         // change
      }*/
      $('#profilePicture').click();
    });

      $('#profilePicture').on('change', function () {
      readURL(this);
      console.log($('#profilePicture').val());
      $('#btnChangePicture').addClass('changing');
      //$('#btnChangePicture').attr('value', 'Confirm');
      $('#btnDiscard').removeClass('d-none');
    });

    $('#btnDiscard').on('click', function () {
      /*$('#btnChangePicture').removeClass('changing');
      $('#btnChangePicture').attr('value', 'Change');
      $('#btnDiscard').addClass('d-none');
      $('#imgProfile').attr('src', $imgSrc);
      $('#profilePicture').val('');*/
      swal({
              title: "Gestion Docente - Información General",
              text: "Estad seguro que desea descartar los cambios realizados?", 
              icon: "warning",
              buttons: true,
              successMode: true,
          })
          .then((aceptar) => {
            if (aceptar) {
               location.href = getCurrentUrl()+"/DashboardPerfilDocente";
            } else {
              return;
            }
            
          });   
     
           
    });

     $('#btnEdit').on('click', function () {
      $('#btnSave').removeClass('d-none');
      $('#btnDiscard').removeClass('d-none');
      $('#btnEdit').addClass('d-none');

      $('#nombreCompleto').removeAttr('readonly');
      $('#cargoPrincipal').removeAttr('disabled');
      $('#cargoSegundario').removeAttr('disabled');
      $('#descripcion').removeAttr('readonly');
      $('#email').removeAttr('readonly');
      $('#linkedin').removeAttr('readonly');
      $('#fb').removeAttr('readonly');
      $('#tw').removeAttr('readonly');
      $('#git').removeAttr('readonly');
      $('#perfilPrivado').removeAttr('disabled');
       $('#btnChangePicture').removeClass('d-none');

    });

     $('#btnSave').on('click', function () {
      console.log($("#formPerfilDocente").serialize());
        swal({
              title: "Gestion Docente - Información General",
              text: "Estad seguro que desea Guardar los cambios realizados?", 
              icon: "warning",
              buttons: true,
              successMode: true,
          })
          .then((aceptar) => {
            if (aceptar) {
              $("#formPerfilDocente").submit();
            } else {
              return;
            }
            
          });   
    });
  });



