function getTribunalData(idGrupo){
    var tblModal = $('#tblTribunal');
    // if(tblModal.length>0){
    //     $("#modalTribunalData").modal();
    // }else{
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'get',
            url:getCurrentUrl()+'/getTribunalData/'+idGrupo,
            success:function(data){
                //console.log(data);
                modalTribunal(data,idGrupo);
            },
            error : function(xhr, status) {
                swal("", "Ocurrió un error al procesar la solicitud!", "Error");
            }
        });
    // }
}
function modalTribunal(data,idGrupo){
    var table, btn, txt, btnprop;
    if(data.state == false){
        table = "<table class='table table-hover table-striped  display' id='tblTribunal'>";
        table += '<thead><th>Nombre</th><th>Rol</th><th>Contacto</th></thead><tbody>';
        var tribunal = data.info;
        for (var obj in tribunal) {
            table += "<tr>"
                +"<td>"+tribunal[obj].name+"</td>"
                +"<td>"+tribunal[obj].nombre_tri_rol+"</td>"
                +"<td>"+tribunal[obj].email+"</td>"
                +"</tr>";
        }
        table += '</tbody></table>';
        txt = "MODIFICAR";
        btnprop = "btn btn-dark";
    }else{
        txt = "CONFIGURAR";
        btnprop = "btn btn-check";
        table = "Todavía no se han configurado los integrantes del tribunal evaluador"
    }
    btn = "<button class='"+btnprop+"' onclick=viewTribunal('"+idGrupo+"')>"+txt+"</button>";
    $('#modalTribunalBody').html(table);
    $('#divBtnViewTrib').html(btn);
    $("#modalTribunalData").modal();
}
function viewTribunal(id){
    url = ""+getCurrentUrl()+"/verTribunal/"+id;
    $(location).attr("href", url);
}
/**Funciones en vista detalle-editar Tribunal Evaluador**/
function prepareModal(id){
    getRolesDisp(id);
}
function getRolesDisp(id){
    var tblModal = $('#tblDcnDisp')
    // if(tblModal.length>0){
    //     $("#modalAgregarTribunal").modal();//VALIDAR!!
    // }else{
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'get',
            url:getCurrentUrl()+'/rolesDisp/'+id,
            success:function(data){
                //console.log(data);
                getDcnDisp(id, data);
            },
            error : function(xhr, status) {
                swal("", "Hubo problemas!", "error");
                //console.log(xhr);
            }
        });
    //}
}
function getDcnDisp(id,dataCbo){
    if(!Array.isArray(dataCbo)||dataCbo.length>0){
        var tblModal = $('#tblDcnDisp');
        // if(tblModal.length>0){
        //     $("#modalAgregarTribunal").modal();
        // }else{
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'get',
                url:getCurrentUrl()+'/dcnDisp/'+id,
                success:function(data){
                    //console.log(data);
                    modalAgregarTribunal(data,dataCbo);
                },
                error : function(xhr, status) {
                    swal("", "Hubo problemas!", "error");
                    //console.log(xhr);
                }
            });
        //}
    }else{
        swal("","El Grupo ya cuenta con un Tribunal Evaluador establecido.","warning");
    }
    return true;
}
function strOpciones(dataCbo) {
    var cad = "";
    for(var obj in dataCbo){
        cad += "<option value='"+dataCbo[obj].id_pdg_tri_rol+"'>"+dataCbo[obj].nombre_tri_rol+"</option>";
    }
    return cad;
}
function modalAgregarTribunal(data,dataCbo){
    var cboOpcs = strOpciones(dataCbo);
    var A,J;
    if(data.length>0){
         btnTxt = "Asignar";
         var table = "<table class='table table-hover table-striped  display' id='tblDcnDisp'>";
         table += '<thead><th>Nombre</th><th>Asignaciones</th><th colspan="2">Rol a asignar</th></thead><tbody>';
         for (var obj in data) {
             A = data[obj].asigned_as_A;
             J = data[obj].asigned_as_J;
             table += "<tr>"
                 +"<td>"+data[obj].name+"</td>"
                 +"<td style='text-align: left;'>"
                 + ((A <= 0 && J <= 0) ? "-" : ((A > 0) ? ("Asesor: " + A + (J > 0 ? ("<br>Jurado: " + J) : "")) : ("Jurado: " + J)))
                 +"</td>"
                 +"<td><select id='select_"+data[obj].id_pdg_dcn+"' class='form-control'>"+cboOpcs+"</select></td>"
                 +"<td><button class='btn btn-dark' onclick=asignarTribunal('"+data[obj].id_pdg_dcn+"',vg_id_pdg_gru)>"+btnTxt+"</button></td>"
                 +"</tr>";
         }
         table += '</tbody></table>';
         $('#modalDetalleBody').html(table);
     }
     $("#modalAgregarTribunal").modal();
}
function asignarTribunal(idDcn,idGru) {
    var idRol = $("#select_"+idDcn).val();
    swal({
        title:"Atención",
        text:"¿Confirma que desea asignar al Docente "+$("#select_"+idDcn+" option:selected").text()+"?",
        icon: "warning",
        buttons: ["Cancelar","Confirmar"],
    }).then((respuesta)=>{
        if(respuesta){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                data:{'dcn':idDcn,'gru':idGru,'rol':idRol},
                url:getCurrentUrl()+'/asignDcnTrib',
                success:function(data){
                    if(data.errorCode == 0){
                        viewTribunal(idGru);
                        $("#modalAgregarTribunal").fadeOut("slow");
                    }else{
                        swal("¡Ups!",data.errorMessage,"warning");
                    }
                },
                error : function(xhr, status) {
                    swal("", "Hubo problemas!", "error");
                }
            });
        }else{
            //
        }
    });
}
function delRelTrib(idRelTrib,rol){
    swal({
        title:"Atención",
        text:"¿Confirma que desea eliminar al Docente "+rol+" de este tribunal?",
        icon: "warning",
        buttons: ["Cancelar","Confirmar"],
    }).then((respuesta)=>{
        if(respuesta){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                data:{'id_pdg_tri_gru':idRelTrib},
                url:getCurrentUrl()+'/delRelTrib',
                success:function(data){
                    if(data.errorCode == 0){
                        viewTribunal(vg_id_pdg_gru);
                    }else{
                        swal("¡Ups!",data.errorMessage,"warning");
                    }
                },
                error : function(xhr, status) {
                    swal("", "Hubo problemas!", "error");
                }
            });
        }else{
            //
        }
    });
}
