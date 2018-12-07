var ip =$('meta[name="current-url"]').attr('content');
//console.log(ip);
function getHistorialAcademico(idDcn){
	$.ajax({
           type:'POST',
           url:ip+'/getHistorial',
           data:{'docente':idDcn},
           success:function(data){
            //console.log(data.length);
             var html = '<table class="table table-striped"><thead><tr><th scope="col">#</th><th scope="col">Cargo</th><th scope="col">Código</th><th scope="col">Materia</th><th scope="col">Ciclo</th><th scope="col">Año</th> </tr></thead><tbody>';
 
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
            //console.log(data.length);
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
        		alert("Hubo un problema al momento de obtener los datos de Docente");
        		
    		}
        });      
}

function getCertificacionesDocente(idDcn){
	$.ajax({
           type:'POST',
           url:ip+'/getCertificaciones',
           data:{'docente':idDcn},
           success:function(data){
            //console.log(data.length);
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
            //console.log(data.length);
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
            html+='<span class="text-primary">';
            html+=docente["display_name"];
            html+='</span>';
            $("#nombreDocente").append(html);
            $("#descripcionDocente").append(docente["descripcionDocente"]);
            $("#correoDocente").append(docente["email"]);
            $("#cargoDocente").append(docente["nombre_cargo"]);
            $("#profileFoto").attr("src",ip+"/Uploads/PerfilDocente/"+docente["dcn_profileFoto"]);
            $("#linkLinkedind_").attr("href",docente["link_linke"]);
            $("#linkGit_").attr("href",docente["link_git"]);
            $("#linkTw_").attr("href",docente["link_tw"]);
            $("#linkFb_").attr("href",docente["link_fb"]);


            
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
           success:function(data)
           {
                //console.log(data);
                 var html = '<table class="table"><tbody><tr>';
                 var html2 = '<table class="table"><tbody><tr>';
                 var contador1=1;
                 var contador2=1;
                 for (var i = 0;i<data.length;i++) {
                 var perfilPrivado = data[i]['perfilPrivado'];
                 var urlPerfil = ip+'/perfilDocente/'+data[i]['id_pdg_dcn'];

                 
                 if(data[i]['tipoJornada']==1) 
                 {
                      body="";
                       if (parseInt(perfilPrivado)==1) {
                            body+='<td style="border: hidden">'+
                                '<blockquote>'+
                               ' <div class="row">'+
                                          '<div class="col-sm-3 text-center">'+
                                                    '<img class="img-circle" src="'+ip+'/Uploads/PerfilDocente/'+data[i]['dcn_profileFoto']+' " style="width: 80px;height:80px;" > '+
                                                    
                                            '</div>'+
                                            '<div class="col-sm-9">'+
                                              '<p style="color:#DFC15E; ">'+data[i]['display_name']+'</p>'+
                                              '<small>'+data[i]['nombre_cargo']+'</small>'+
                                            '</div>'+
                                            '</div>'+
                                        '</blockquote>'+
                                      '</div>'+

                                    '</td>  '

                        }else{
                              body+='<td style="border: hidden">'+
                                '<blockquote>'+
                                '<a  href="'+ip+'/perfilDocente/'+data[i]['id_pdg_dcn']+'"  data-target="#myModal" target="myModal">'+ // data-toggle="modal" data-target=".bd-example-modal-lg" 
                               ' <div class="row">'+
                                          '<div class="col-sm-3 text-center">'+
                                              
                                                    '<img class="img-circle" src="'+ip+'/Uploads/PerfilDocente/'+data[i]['dcn_profileFoto']+' " style="width: 80px;height:80px;" > '+
                                                    
                                            '</div>'+
                                            '<div class="col-sm-9">'+
                                              '<p style="color:#DFC15E; ">'+data[i]['display_name']+'</p>'+
                                              '<small>'+data[i]['nombre_cargo']+'</small>'+
                                            '</div>'+
                                            '</div>'+
                                            '</a>'+
                                        '</blockquote>'+
                                      '</div>'+

                                    '</td>  '

                        }
                  //body+='<li><i class="fa-li fa fa-check"></i>'+data[i]['primer_nombre']+'</li>' + '<img class="img-circle" style="width: 100px;height:100px;" src= "'+data[i]['dcn_profileFoto']+' "></img>';
                        
                                    if((contador1%3)==0){

                                      if(contador1==0){
                                        contador1=contador1+1;
                                      } else{
                                        body+='</tr><tr>';
                                        contador1=contador1+1;
                                      }
                                      
                                    }
                                    else{
                                      contador1++;
                                    }
                                html+=body;

                  }
                  else
                  {

                        body2="";
                        if (parseInt(perfilPrivado)==1) {
                          body2+='<td style="border: hidden">'+
                                
                                '<blockquote>'+
                               ' <div class="row">'+
                                          '<div class="col-sm-3 text-center">'+
                                              
                                                    '<img class="img-circle" src="'+ip+'/Uploads/PerfilDocente/'+data[i]['dcn_profileFoto']+' " style="width: 80px;height:80px;" > '+
                                                    
                                            '</div>'+
                                            '<div class="col-sm-9">'+
                                              '<p style="color:#DFC15E; ">'+data[i]['display_name']+'</p>'+
                                              '<small>'+data[i]['nombre_cargo']+'</small>'+
                                            '</div>'+
                                            '</div>'+
                                        '</blockquote>'+
                                      '</div>'+

                                    '</td>  '

                                    if((contador2%3)==0){

                                      if(contador2==0){
                                          contador2++;
                                      } else{
                                        contador2++;
                                        body2+='</tr><tr>';
                                      }
                                      
                                    }
                                    else{
                                      contador2++;
                                    }
                                html2+=body2;
                        }else{
                          body2+='<td style="border: hidden">'+
                                
                                '<blockquote>'+
                                 '<a  href="'+ip+'/perfilDocente/'+data[i]['id_pdg_dcn']+'"  data-target="#myModal" target="myModal">'+ // data-toggle="modal" data-target=".bd-example-modal-lg" 
                               ' <div class="row">'+
                                          '<div class="col-sm-3 text-center">'+
                                              
                                                    '<img class="img-circle" src="'+ip+'/Uploads/PerfilDocente/'+data[i]['dcn_profileFoto']+' " style="width: 80px;height:80px;" > '+
                                                    
                                            '</div>'+
                                            '<div class="col-sm-9">'+
                                              '<p style="color:#DFC15E; ">'+data[i]['display_name']+'</p>'+
                                              '<small>'+data[i]['nombre_cargo']+'</small>'+
                                            '</div>'+
                                            '</div>'+
                                            '</a>'+
                                        '</blockquote>'+
                                      '</div>'+

                                    '</td>  '

                                    if((contador2%3)==0){

                                      if(contador2==0){
                                          contador2++;
                                      } else{
                                        contador2++;
                                        body2+='</tr><tr>';
                                      }
                                      
                                    }
                                    else{
                                      contador2++;
                                    }
                                html2+=body2;
                        }
                  //body+='<li><i class="fa-li fa fa-check"></i>'+data[i]['primer_nombre']+'</li>' + '<img class="img-circle" style="width: 100px;height:100px;" src= "'+data[i]['dcn_profileFoto']+' "></img>';
                        
                  }
                 }


                //  html+=html2;
                $("#seccionListado").append(html);
                $("#seccionListado2").append(html2);
           }, 
        error : function(xhr, status) {
            console.log("Hubo un problema al momento de obetener los datos de Docente");
            
        }
        });      

   // $.ajax({
   //         type:'POST',
   //         url:ip+'/getListadoDocentes',
   //         data:{'jornada':2},
   //         success:function(data)
   //         {
   //              console.log(data);
   //               var html = '<table class="table"><tbody><tr>';
   //               for (var i = 1;i<data.length+1;i++) {
   //                body="";
   //                //body+='<li><i class="fa-li fa fa-check"></i>'+data[i]['primer_nombre']+'</li>' + '<img class="img-circle" style="width: 100px;height:100px;" src= "'+data[i]['dcn_profileFoto']+' "></img>';
   //               body+='<td style="border: hidden">'+

   //               '<blockquote>'+
   //                '<a  href="http://localhost/SIGPAD/public/perfilDocente/'+data[i-1]['id_pdg_dcn']+'"  data-target="#myModal" target="myModal">'+ // data-toggle="modal" data-target=".bd-example-modal-lg" 
   //               ' <div class="row">'+
   //                          '<div class="col-sm-3 text-center">'+
                                
   //                                    '<img class="img-circle" src="'+data[i-1]['dcn_profileFoto']+' " style="width: 80px;height:80px;" > '+
                                      
   //                            '</div>'+
   //                            '<div class="col-sm-9">'+
   //                              '<p style="color:#DFC15E; ">'+data[i-1]['primer_nombre']+' '+data[i-1]['segundo_nombre'] +' '+data[i-1]['primer_apellido']+' '+data[i-1]['segundo_apellido']+'</p>'+
   //                              '<small>'+data[i-1]['nombre_cargo']+'</small>'+
   //                            '</div>'+
   //                            '</div>'+
   //                            '</a>'+
   //                        '</blockquote>'+
   //                      '</div>'+

   //                    '</td>  '

   //                    if((i%3)==0){

   //                      if(i==0){

   //                      } else{
   //                        body+='</tr><tr>';
   //                      }
                        
   //                    }
   //                html+=body;
   //               }


   //               html+='</ul>';
   //              $("#seccionListado2").append(html);

   //         }, 
   //      error : function(xhr, status) {
   //          console("Hubo un problema al momento de obetener los datos de Docente");
            
   //      }
   //      });      
}

// function getListadoDocente(idJornada){
//   $.ajax({
//            type:'POST',
//            url:ip+'/getListadoDocentes',
//            data:{'jornada':idJornada},
//            success:function(data)
//            {
//                 console.log(data);
//                  var html = '<ul class="fa-ul mb-0">';
//                  for (var i = 0;i<data.length;i++) {
//                   body="";
//                   //body+='<li><i class="fa-li fa fa-check"></i>'+data[i]['primer_nombre']+'</li>' + '<img class="img-circle" style="width: 100px;height:100px;" src= "'+data[i]['dcn_profileFoto']+' "></img>';
//                  body+='<blockquote>'+
//                   '<a  href="http://localhost/SIGPAD/public/perfilDocente/'+data[i]['id_pdg_dcn']+'"  data-target="#myModal" target="myModal">'+ // data-toggle="modal" data-target=".bd-example-modal-lg" 
//                  ' <div class="row">'+
//                             '<div class="col-sm-1 text-center">'+
                                
//                                       '<img class="img-circle" src="'+data[i]['dcn_profileFoto']+' " style="width: 80px;height:80px;" > '+
                                      
//                               '</div>'+
//                               '<div class="col-sm-9">'+
//                                 '<p style="color:#DFC15E; ">'+data[i]['primer_nombre']+' '+data[i]['segundo_nombre'] +' '+data[i]['primer_apellido']+' '+data[i]['segundo_apellido']+'</p>'+
//                                 '<small>'+data[i]['nombre_cargo']+'</small>'+
//                               '</div>'+
//                               '</div>'+
//                               '</a>'+
//                           '</blockquote>'+
//                         '</div>'
//                   html+=body;
//                  }


//                  html+='</ul>';
//                 $("#seccionListado").append(html);

//            }, 
//         error : function(xhr, status) {
//             console("Hubo un problema al momento de obetener los datos de Docente");
            
//         }
//         });      
//}

	
