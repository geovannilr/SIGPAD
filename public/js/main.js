function getUrl(){
  return $('meta[name="app-url"]').attr('content');
}

/***
 * Función utilizada para obtener la ruta al recurso actual dónde es invocada.
 * @returns {jQuery}
 */
function getCurrentUrl(){
    return $('meta[name="current-url"]').attr('content');
}

/***
 *Función utilizada para obtener tk*n de ssi*n.
 * @returns {jQuery}
 */
function getCurrentTkn(){
    return $('meta[name="csrf-token"]').attr('content');
}

/***
 * Función utilizada para realizar un form submit clásico de un POST.
 * @param path = string de ruta
 * @param params = objeto de parámetros
 * @param method = método para la petición, por defecto POST
 */
function post_to_url(path, params, method) {
    method = method || "post";

    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}

/***
 * Función utilizada para realizar un redondeo visual a dos decimales.
 * @param num = número a redondear
 * @returns {string} = cadena de texto en formato '*.99'
 */
function customRound(num){
    return parseFloat((typeof num === "undefined" || isNaN(num) || num === "") ? 0 : num).toFixed(2);
}

/***
 * Función para obtener un valor de nota válido, rango [0,10].
 * @param nota = númerp a validar
 * @returns {string} = cadena de texto en formato '*.99'
 */
function getValidNotaDecimal(nota) {
    if(isNaN(nota)){
        nota = customRound(nota);
    } else {
        nota = customRound(nota);
        if (nota >= 10){
            nota = customRound(10);
        } else if (nota <= 0){
            nota = customRound(0);
        }
    }
    return nota;
}