function calificarEtapa(idGrupo,idEtapa){
    var tkn = getCurrentTkn();
    var url = getCurrentUrl()+'/calificaEtapa';
    console.log(idGrupo+' etapa:'+idEtapa+' tkn:'+tkn);
    post_to_url(url,{grupo:idGrupo,etapa:idEtapa,_token:tkn},'POST');
}