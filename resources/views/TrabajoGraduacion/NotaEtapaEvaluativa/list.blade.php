@extends('template')

@section('content')
    @if(empty($notas)||empty($criterios))
    @else
        <style>
            .notaInput {
                text-align: right;
            }
        </style>
        <script type="text/javascript">
            var notas = <?php echo json_encode($notas);?>;
            var criterios = <?php echo json_encode($criterios);?>;
            var bngrupal = (parseInt({{$etapa->notagrupal_cat_eta_eva}})==1);
            $(function () {
                if(notas.length>0 && criterios.length>0){
                    paintTblNotas();
                    uiInitial();
                }
            });
            function armarTablaNotasIndividual(){
                var html = "", aux = "", i = 0, j = 0, carnets = [], first = false, factorPondera = 0, totalEtapa = 0, notaCriterio = 0;

                html = "<table class='table table-hover' id='listTable'>";

                html += "<thead><th>CARNET</th><th>NOMBRE</th>";
                for(i = 0; i < criterios.length; i++){
                    html += "<th style='text-align: center;'>"+criterios[i].nombreCriterio+"<br/>"
                        + (criterios[i].ponderaCriterio*criterios[i].ponderaAspecto)/100+"%</th>";
                }
                html += "<th style='text-align: center;'>TOTAL<br/>ETAPA</th></thead><tbody>";

                carnets = getDistinctCarnets(notas);
                for(i = 0; i < carnets.length;i++){
                    aux = "<tr id='row_"+carnets[i]+"'>";
                    aux += "<td>"+carnets[i]+"</td>";
                    first = true;
                    totalEtapa =0;
                    for (j = 0; j < notas.length; j++){
                        if(carnets[i]===notas[j].carnetEstudiante){
                            factorPondera = (notas[j].ponderaCriterio*notas[j].ponderaAspecto)/10000;
                            notaCriterio = customRound(notas[j].notaCriterio);
                            aux += (first?"<td>"+notas[j].nombreEstudiante+"</td>":"");
                            aux += "<td style='text-align: center;'>";
                            aux += "<input type='hidden' id='factorPondera' value='"+factorPondera+"' />";
                            aux += "<input class='form-control notaInput txtCritClass' "
                                +  "type='number' id='txtCrit_"+notas[j].idNota+"' value='"+notaCriterio+"' pattern='^d+(?:.d{1,2})?$'  "
                                +  "min='0' max='10' step='0.01' disabled onblur='handleBlurCalculaTotalRow(this)' />";
                            aux += "</td>";
                            first = false;
                            totalEtapa += notaCriterio*factorPondera;
                        }
                    }
                    aux += "<td><input class='form-control notaInput txtFinalClass' "
                        +  "type='number' id='txtTotal_"+carnets[i]+"' value='"+customRound(totalEtapa)+"' pattern='^\d+(?:\.\d{1,2})?$'  "
                        +  "min='0' max='10' step='0.01' disabled onblur='handleBlurCalculaCriteriosRow(this)' /></td>";
                    aux += "</tr>";
                    html+= aux;
                }
                html += "</tbody></table>";

                return html;
            }

            function getDistinctCarnets(notas) {
                var carnets = [];
                for(var i = 0; i < notas.length; i++){
                    var carnet = notas[i].carnetEstudiante;
                    if(carnets.indexOf(carnet)<0)
                        carnets.push(carnet);
                }
                return carnets;
            }

            function configurarBtns(opcion){
                if(opcion<=0){
                    $('#divToggleBtn').hide();
                    $('#btnCancelar').hide();
                    $('#btnGuardarNotas').hide();
                    $('#btnCalificar').show();
                    $('#btnSubir').show();
                }else{
                    $('#divToggleBtn').show();
                    $('#btnCancelar').show();
                    $('#btnGuardarNotas').show();
                    $('#btnCalificar').hide();
                    $('#btnSubir').hide();
                }
            }

            /***
             * Función Callback ejecutada cuando el elemento pierde el foco en el evento blur().
             * Manda a ejecutar el cálculo de los criterios por fila.
             */
            function handleBlurCalculaCriteriosRow(arg){
                arg.value = getValidNotaDecimal(arg.value);
                var nota = arg.value;
                var row = arg.parentNode.parentNode;
                calculaCriteriosRow(row,nota);
            }
            /***
             * Función Callback ejecutada cuando el elemento pierde el foco en el evento blur()
             * Manda a ejecutar el cálculo de la nota final para una fila.
             */
            function handleBlurCalculaTotalRow(arg){
                var row = arg.parentNode.parentNode;
                calculaTotalRow(row);
            }

            /***
             * Función que realiza el cálculo de la nota distribuida en los elementos de la fila modificada.
             * @param row : fila a modificar.
             * @param nota : valor a utilizar para el cálculo.
             */
            function calculaCriteriosRow(row,nota){
                for (var j = 2, col; col = row.cells[j]; j++) {
                    if(j===(row.cells.length-1)) break;
                    col.lastChild.value = nota;
                }
                return true;
            }

            /***
             * Función que realiza el cálculo del total de nota para una fila.
             * @param row : fila con la uqe se realiza el cálculo.
             */
            function calculaTotalRow(row){
                var nota = 0;

                for (var j = 2, col; col = row.cells[j]; j++) {
                    if(j===(row.cells.length-1)) break;
                    var factor = col.firstChild.value;
                    var notaCr = getValidNotaDecimal(col.lastChild.value);
                    col.lastChild.value = notaCr;
                    nota += factor*notaCr;
                }
                row.cells[row.cells.length-1].lastChild.value = customRound(nota);
            }

            function habilitarToggleCalificacion(){
                configurarBtns(1);
                toggleCasillas("total");
            }
            function confirmGuardarNotas(){
                swal({
                    title:"Atención",
                    text:"¿Confirma que desea guardar las notas asignadas?",
                    icon: "warning",
                    buttons: ["Cancelar","Confirmar"],
                }).then((respuesta)=>{
                    if(respuesta){
                        if(bngrupal)
                            guardarNotasEtapa();
                        else
                            guardarNotas();
                    }else{
                        cancelarGuardarNotas();
                    }
                });
            }
            function guardarNotas() {
                uiInitial();
                var criteriosNotas = {};
                var cells = $("#divTblNotas td");
                for(var n = 0; n < cells.length; n++){
                    var td = cells[n];
                    if(td.children.length===2) {
                        var key = td.lastChild.id.substr(8);
                        var value = td.lastChild.value;
                        criteriosNotas[key] = value;
                    }
                }
                var data = { notas: criteriosNotas, idGru : $("#idGru").val(), idEtaEva: $("#idEtaEva").val() };
                var url = "{{route('updateNotas')}}";
                ajaxSaveNotas(data,url);
            }
            function ajaxSaveNotas(data,url){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN':  getCurrentTkn()
                    }
                });
                $.ajax({
                    type: 'POST',
                    url : url,
                    data : data,
                    success: function (data) {
                        swal({
                            title:data.errorCode == 0 ? "¡Éxito!" : "¡Ups!",
                            text: data.errorMessage,
                            icon: data.errorCode == 0 ? "success" : "warning",
                            button: "Aceptar",
                        }).then((respuesta)=>{
                            calificarEtapa($("#idGru").val(),$("#idEtaEva").val());
                        });
                    },
                    error: function (xhr, status) {
                        swal("", "Su solicitud no pudo ser procesada!", "error");
                    }
                });
            }
            function cancelarGuardarNotas() {
                paintTblNotas();
                uiInitial();
            }
            function toggleCasillas(tipo) {
                if(tipo==="criterio"){
                    toggleCriterios(true);
                    toggleTotales(false);
                }else if (tipo==="total"){
                    toggleCriterios(false);
                    toggleTotales(true);
                }else if (tipo==="off"){
                    toggleTotales(false);
                    toggleCriterios(false);
                }
            }
            function toggleCriterios(state) {
                $(".txtCritClass").prop("disabled",!state);
            }
            function toggleTotales(state) {
                $(".txtFinalClass").prop("disabled",!state);
            }
            function paintTblNotas() {
                $('#divTblNotas').html("");
                var tbl = bngrupal ? armarTablaNotasGrupal() : armarTablaNotasIndividual();
                $('#divTblNotas').html(tbl);
            }
            function uiInitial() {
                configurarBtns(0);
                toggleCasillas('off');
            }
            function armarTablaNotasGrupal(){
                var html = "", totalN = 0, totalP = 0;
                html = "<table class='table table-hover' id='listTable'>";
                html += "<thead><th>ASPECTO</th><th>PORCENTAJE</th><th>NOTA</th><thead><tbody>";
                for(var i = 0; i < criterios.length; i++){
                    var factorPondera = (criterios[i].ponderaCriterio*criterios[i].ponderaAspecto)/100;
                    totalN += ((factorPondera*criterios[i].notaCriterio)/100);
                    totalP += factorPondera;
                    html += "<tr>"
                        + "<td>" + criterios[i].nombreCriterio + "</td>"
                        + "<td>" + customRound(factorPondera) + "%</td>"
                        + "<td><input class='form-control notaInput txtCritClass' type='number' id='txtCrit_"
                        + criterios[i].idCriterio+"' value='"+ customRound(criterios[i].notaCriterio)+"' pattern='^d+(?:.d{1,2})?$' "
                        + "min='0' max='10' step='0.01' disabled onblur='handleBlurCalculaTotalCol(this)' />"
                        + "<input type='hidden' id='factPond_" + criterios[i].idCriterio + "' value='"+factorPondera+"' />"
                        + "</td>"
                        + "</tr>";
                }
                html += "<tr>"
                    + "<td><b>TOTAL ETAPA</b></td>"
                    + "<td><b>" + customRound(totalP) + "%</b></td>"
                    + "<td><input class='form-control notaInput txtFinalClass' type='number' id='txtTotal' "
                    + "value='"+customRound(totalN)+"' pattern='^\d+(?:\.\d{1,2})?$'  "
                    + "min='0' max='10' step='0.01' disabled onblur='handleBlurCalculaCriteriosCol(this)' /></td>"
                    + "</tr>";
                html += "</tbody></table>";
                $('#divTblNotas').html(html);
            }
            function handleBlurCalculaCriteriosCol(arg){
                arg.value = getValidNotaDecimal(arg.value);
                var nota = arg.value;
                calculaCriteriosCol(nota);
            }
            function calculaCriteriosCol(nota){
                for(var i = 0; i < criterios.length; i++){
                    $("#txtCrit_"+criterios[i].idCriterio).val(nota);
                }
                return true;
            }
            function handleBlurCalculaTotalCol(arg){
                arg.value = getValidNotaDecimal(arg.value);
                calculaTotalCol();
            }
            function calculaTotalCol(){
                var nota = 0;
                for(var i = 0; i < criterios.length; i++){
                    var currNota = $("#txtCrit_"+criterios[i].idCriterio).val();
                    var currFact = $("#factPond_"+criterios[i].idCriterio).val();
                    nota += currNota*currFact/100;
                }
                $("#txtTotal").val(customRound(nota));
            }
            function guardarNotasEtapa(){
                uiInitial();
                var criteriosNotas = {};
                for(var i = 0; i < criterios.length; i++){
                    var key = criterios[i].idCriterio;
                    var currNota = $("#txtCrit_"+key).val();
                    criteriosNotas[key] = currNota;
                }
                var data = { notas: criteriosNotas, idGru : $("#idGru").val(), idEtaEva: $("#idEtaEva").val() };
                var url = "{{route('updateNotasEtapa')}}";
                ajaxSaveNotas(data,url);
            }
        </script>
    @endif
    <ol class="breadcrumb" style="text-align: center; margin-top: 1em">
        <li class="breadcrumb-item ">
            <h5><a href="{{ route('detalleEtapa',[$etapa->id_cat_eta_eva,$grupo->id_pdg_gru]) }}" style="margin-left: 0em">
                    <i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i>
                </a>Notas {{$etapa->nombre_cat_eta_eva}}
            </h5>
        </li>
        <li class="breadcrumb-item active">GRUPO {{$grupo->numero_pdg_gru}}</li>
    </ol>
    @if(empty($notas)||empty($criterios))
        <div class="row">
            &nbsp;&nbsp;<span>No es posible calificar esta etapa, verifique que se han cumplido los requerimientos de etapas anteriores.</span>
        </div>
    @else
        {!!Html::script('js/TrabajoGraduacion/etapaEvaluativa.js')!!}
        <div class="row">
            <div class="col-sm-3">
                <div class="btn-group btn-group-toggle" data-toggle="buttons" id="divToggleBtn">
                    <label class="btn btn-secondary " onclick="toggleCasillas('criterio');">
                        <input type="radio" name="options" id="option1" autocomplete="off"> CRITERIO
                    </label>
                    <label class="btn btn-secondary active" onclick="toggleCasillas('total');">
                        <input type="radio" name="options" id="option2" autocomplete="off"> TOTAL
                    </label>
                </div>
            </div>
            <div class="col-sm-3"></div>
            <div class="col-sm-3"></div>
            <div class="col-sm-3" style="text-align: right">
                <button id="btnCalificar" class="btn btn-primary" title="Calificar manualmente" onclick="habilitarToggleCalificacion();">
                    CALIFICAR&nbsp;<i class="fa fa-pencil"></i>
                </button>
                @if($subida&&$etapa->notagrupal_cat_eta_eva!=1)
                    <button id="btnSubir" class="btn btn-success" title="Subir consolidado" onclick="location.href='{{route('createNotas',$etapa->id_cat_eta_eva)}}'">
                        SUBIR&nbsp;<i class="fa fa-file"></i>
                    </button>
                @endif
                <button id="btnGuardarNotas" class="btn btn-danger" title="Guardar" onclick="confirmGuardarNotas();">
                    GUARDAR&nbsp;<i class="fa fa-pencil"></i>
                </button>
                <button id="btnCancelar" class="btn btn-secondary" title="Cancelar" onclick="cancelarGuardarNotas();">
                    CANCELAR&nbsp;<i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <br>
        <div id="divTblNotas" >
            ...
        </div>
        <input type="hidden" id="idGru" value="{{$grupo->id_pdg_gru}}"/>
        <input type="hidden" id="idEtaEva" value="{{$etapa->id_cat_eta_eva}}"/>
    @endif
@stop