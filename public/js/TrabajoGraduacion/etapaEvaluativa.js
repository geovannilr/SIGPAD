function calificarEtapa(idGrupo,idEtapa){
    var tkn = getCurrentTkn();
    var url = getCurrentUrl()+'/calificaEtapa';
    post_to_url(url,{grupo:idGrupo,etapa:idEtapa,_token:tkn},'POST');
}
function aprobarEtapaFrm(idGrupo, idEtapa) {
    getDataDialogoAprobar(idGrupo,idEtapa);
}
function getDataDialogoAprobar(idGrupo,idEtapa) {
    var url = getCurrentUrl()+'/dataAprbEta/'+idGrupo+'/'+idEtapa;
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': getCurrentTkn()}});
    $.ajax({
        type:'GET',
        url: url,
        success:function(data){
            setDialogoAprobar(data);
        },
        error : function(xhr, status) {
            swal("", "Ocurrió un error al procesar la solicitud!", "Error");
        }
    });
}
function setDialogoAprobar(data){
    var estilo = data.success ? "text-dark":"text-danger";
    $("#msgAprb").html("<label class='"+estilo+"'>"+data.msg+"</label>");
    $("#btnAprb").attr("disabled",!data.success);
    $("#modalAprobar").modal();
}
function showInfoAprb(){
    swal($("#infoAprb").html());
}
function confirmAprobarEtapa() {
    swal({
        title:"Atención",
        text:"¿Confirma que desea aprobar la etapa "+$("#nomEtapa").val()+"?",
        icon: "warning",
        buttons: ["Cancelar","Confirmar"],
    }).then((respuesta)=>{
        if(respuesta){
            aprobarEtapa();
        }else{
            $("#btnCancel").click();
        }
    });

}
function aprobarEtapa() {
    $("#formAprobar").submit();
}