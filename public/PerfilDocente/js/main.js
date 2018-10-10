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


function getInformacionDocente(idDcn){
  $.ajax({
           type:'POST',
           url:ip+'/getGeneralInfo',
           data:{'docente':idDcn},
           success:function(data){
            var docente = data[0];
            var html = "";
            html+=docente["primer_nombre"]+" ";
            html+='<span class="text-primary">';
            html+=docente["primer_apellido"];
            html+='</span>';
            $("#nombreDocente").append(html);
            $("#descripcionDocente").append(docente["descripcionDocente"]);
            $("#correoDocente").append(docente["email"]);
            $("#cargoDocente").append(docente["nombre_cargo"]);

            
           },
        error : function(xhr, status) {
            alert("Hubo un problema al momento de obetener los datos de Docente");
            
        }
        });      
}
	

