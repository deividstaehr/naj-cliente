//---------------------- Functions Gerais -----------------------//

/**
 * Functions da NAJ
 * 
 * @author Roberto Oswaldo Klann
 * @author William Goebel
 * @since  09/01/2020
 */

/**
 * Obtêm um objeto com os valores de todas as constantes globais do sistema naj adv web
 * @returns {undefined}
 */
function getConsts(){
    let constantesGlobais = {
        "baseURL" : baseURL,  
        "baseUrlApiBoletos" : baseUrlApiBoletos,
        "baseURLCpanel" : baseURLCpanel,
        "appAlias" : appAlias,    
        "appUrl" : appUrl,  
        "nomeUsuarioLogado" : nomeUsuarioLogado,
        "tipoUsuarioLogado" : tipoUsuarioLogado,
        "idUsuarioLogado" : idUsuarioLogado,
        "nomeEmpresa" : nomeEmpresa
    };
    return constantesGlobais;
}

/**
 * Console Log
 * @param {mix} conteudo
 */
function log(conteudo){
    console.log(conteudo);
}

/**
 * Alert
 * @param {mix} conteudo
 */
function alt(conteudo){
    alert(conteudo);
}

/**
 * Confirm
 * @param {mix} conteudo
 */
function conf(conteudo){
    confirm(conteudo);
}
/**
 * Adiciona classe css no elemento.
 * 
 * @param {string} classe 
 * @param {string} idElement 
 */
function addClassCss(classe, idElement) {
    $(idElement).addClass(classe);
}

/**
 * Remove classe css no elemento.
 * 
 * @param {string} classe 
 * @param {string} idElement 
 */
function removeClassCss(classe, idElement) {
    $(idElement).removeClass(classe);
}

/**
 * Limpa valores dos campos do formulário
 * 
 * @param {string} formulario Identificador formulario
 */
function limpaFormulario(formulario) {
    //Seta os campos com valores vazios
    $(`${formulario} input[type="text"]`).val("");
    $(`${formulario} input[type="number"]`).val("");
    $(`${formulario} input[type="tel"]`).val("");
    $(`${formulario} input[type="email"]`).val("");
    $(`${formulario} select`).val("");
    $(`${formulario} textarea`).val("");
    //Remove check dos campos checkbox
    removeCheckeds(formulario);
    //Remove attr "disabled" dos campos
    removeAttrForAllInputsForm(formulario);
}

/**
 * Remove a marcação dos checkeboxs
 * 
 * @param {string} formulario
 */
function removeCheckeds(formulario){
    let checkboxs = $(`${formulario} input[type="checkbox"]`);
    for(let i = 0; i < checkboxs.length; i++){
        checkboxs[i].checked = false;
    }
}

/**
 * Redireciona para uma rota
 * 
 * @param {string} rota
 */
function redirectPage(rota) {
    window.location.href = `/${appAlias}/${rota}`;
}

/**
 * Verifica se a rotina é index
 * 
 * @param {string} rotina Nome da rotina
 * @returns {bool}
 */
function isIndex(rotina) {
    return window.location.href.split(`${rotina}/`).length == 1;
}

/**
 * Verifica se rotina de manutenção é do tipo create
 * 
 * @returns {bool}
 */
function isCreate() {
    return window.location.href.indexOf('create') != -1;
}

/**
 * Verifica se rotina de manutenção é do tipo edit
 * 
 * @returns {bool}
 */
function isEdit() {
    return window.location.href.indexOf('edit') != -1;
}

/**
 * Verifica se a rotina passada por parâmetro é a rotina corrente
 * 
 * @param {string} Rota Base Ex: monitoramento/diario
 * @returns {bool}
 */
function isRotaBase(rotaBase){
    //Verifica se a rora base está contida na url corrente
    let result = window.location.href.search(rotaBase);
    if(result >= 0){
        return true;
    }else{
        return false;
    }
    return 
}

/**
 * Permite que somente caracteres númericos sejam inseridos nos campos
 *  
 * @param {object} evt Evento
 */
function onlynumber(evt) {
    let theEvent = evt || window.event,
        key      = theEvent.keyCode || theEvent.which;

    key = String.fromCharCode(key);
    let regex = /^[0-9.]+$/;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }
}

/**
 * Verifica se os campos obrigatórios do formulario foram preenchidos
 * 
 * @param {string} form_name Nome do formulário
 * @returns {bool}
 */
function validaForm(form_name = null){
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    //Verifica se o nome do form foi informado
    if(form_name){
        //Obtêm o form com o nome informado
        form = forms[form_name];
    }else{
        //Obtêm o primeiro form na çista de forms
        form = forms[0];
    }
    // Extari form
    if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        form.classList.add('was-validated');
        return false;
    }else{
        return true;
    }
}

/**
 * Carrega os options de um campo select.
 * Os vetores devem conter o id e a descrição em cada registro
 * e podem também conter um terceiro atributo opcional
 * 
 * @param {string}       rota          rota que retorna os registros
 * @param {string|array} id            Id do campo select ou um array dos ids dos campos select
 * @param {bool}         retornaHtml   Retorna html dos options
 * @param {string}       attr          Atributo opcional "data" por default
 * @param {bool}         todos         Define se apresenta a opção todos
 * @param {bool}         idSelecionado       Define se apresenta a opção todos
 * @returns {String}
 */
async function carregaOptionsSelect(rota, id, retornaHtml = false, attr = "data", todos = false, idSelecionado = false){
    //Primeiramente remove todos os options do campo select
    if(Array.isArray(id)){
        for(let a = 0; a < id.length; a++){
            removeOptionsSelect(id[a]);
        }
    }else{
        removeOptionsSelect(id);
    }
    let response = null;
    let options;
    try{
        naj      = new Naj(baseURL + rota, null);
        response = await naj.getData(`${baseURL}` + rota);
    }catch(e){
        NajAlert.toastError('Erro ao obter dados, contate o suporte!');
        console.log(response);
    }
    if(todos == true){
        options = '<option value="" selected="">--Todos--</option>';
    }else{
        let selected = !idSelecionado ? `selected=""` : ``;
        options = `<option value="" disabled="" ${selected}>--Selecionar--</option>`;
    }
    if(retornaHtml){
        for(let b= 0; b < response.length; b++) {
            data = Object.values(response[b]);
            //Verifica se 'data' tem mais que dois atributos
            if(data.length > 2){
                options += `<option ${attr}="${data[2]}" value="${data[0]}">${data[1]}</option>`; 
            }else{
                options += `<option value="${data[0]}">${data[1]}</option>`; 
            }
        }
        return options
    }else{
        if(Array.isArray(id)){
            for(let c = 0; c < id.length; c++){
                option           = document.createElement('option');
                option.value     = '';
                option.innerHTML = '--Selecionar--';
                option.setAttribute("disabled","");
                if(!idSelecionado){
                    option.setAttribute("selected","");
                }
                let campo_id = id[c];
                $(`#${campo_id}`).append(option);
            }
        }else{
            option           = document.createElement('option');
            option.value     = '';
            option.innerHTML = '--Selecionar--';
            option.setAttribute("disabled","");
            if(!idSelecionado){
                option.setAttribute("selected","");
            }
            $('#' + id).append(option);
        }
        for(let d = 0; d < response.length; d++) {
            if(Array.isArray(id)){
                for(let e = 0; e < id.length; e++){
                    data = Object.values(response[d]);
                    option           = document.createElement('option');
                    option.value     = data[0];
                    option.innerHTML = data[1];
                    //Seta o option como selecionado
                    if(idSelecionado == data[0]){
                        option.setAttribute("selected","");
                    }
                    //Verifica se contêm o terceiro atributo
                    if(data.length > 2){
                        option.setAttribute(attr, data[2])
                    }
                    let campo_id = id[e];
                    $(`#${campo_id}`).append(option);
                }
            }else{
                data = Object.values(response[d]);
                option           = document.createElement('option');
                option.value     = data[0];
                option.innerHTML = data[1];
                //Seta o option como selecionado
                if(idSelecionado == data[0]){
                    option.setAttribute("selected","");
                }
                //Verifica se contêm o terceiro atributo
                if(data.length > 2){
                    option.setAttribute(attr, data[2])
                }
                $('#' + id).append(option);
            }
        } 
    } 
}

/**
 * Remove todos os options do campo select
 * 
 * @param {string} id Id do campo select 
 * @returns {string}
 */
function removeOptionsSelect(id){
    $('#' + id).html('');
}

/**
 * Obtêm cookie
 * 
 * @param string cname Nome do cookie
 * @returns {string}
 */
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

/**
 * Formata numeros inteiros para dinheiro
 * 
 * @type Intl.NumberFormat
 * exemplo chamada formatter.format(1000) // "$1,000.00"
 */
const formatter = new Intl.NumberFormat('pt-BR'/*en-US, it-IT */, {
  style: 'currency',
  currency: 'BRL', /*USD, EUR*/
  minimumFractionDigits: 2
})

/**
 * Carrega elemento por ID
 * 
 * @param {number} id
 * @returns {element}
 */
function byId(id) {
    return document.getElementById(id);
}

/**
 * Converte uma string de dinheiro por float
 * 
 * @param {string} money
 * @returns {float}
 */
function convertMoneyToFloat(money){
    if(money.length == 1){
        money = "0.0" + money;
        return parseFloat(money);
    }else if(money.length == 2){
        money = "0." + money;
        return parseFloat(money);
    }
    do {
        money = money.replace(".","");
    }
    while(money.indexOf(".") > 0);
    money = parseFloat(money.replace(",","."));
    return money;
}

/**
 * Converte inteiro para money 
 * 
 * @param {integer} value
 * @param {bool}    cifrao
 * @returns {string}
 */
function convertIntToMoney(value, cifrao = false) {
    let result = null;
    result = formatter.format(value);
    if(!cifrao){
        result = result.replace('R$','').substr(1, value.length + 1);
    }
    return result;
}

/**
 * Ativa o bloqueio da tela
 * 
 * @param {string} elemento
 */
function loaderOn(elemento = null) {
    var block_ele = elemento ? $(elemento) : $('.page-wrapper');
    $(block_ele).block({
        message: '<i class="fas fa-spin fa-sync text-white"></i>',
        fadeIn: 1000,
        //timeout: 2000, //unblock after 2 seconds
        overlayCSS: {
            backgroundColor: '#000',
            opacity: 0.5,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'transparent'
        },
        onBlock: function() {
            //Insira uma função aqui para ser executada no bloqueio da tela caso seja necessário"
        }
    });
}

/**
 * Desativa o bloqueio da tela
 * 
 * @param {string} elemento É o elemento que se deseja bloquear
 */

function loaderOff(elemento = null){
    var block_ele = elemento ? $(elemento) : $('.page-wrapper');
        $(block_ele).unblock();
}

/**
 * Obtêm os valores dos campos checkbox selecionandos
 * 
 * @param {string} formulario
 * @param {string} inputName
 * @param {bool}   toString
 * @returns {string}
 */
function getValuesChekeds(formulario, inputName, toString = false){
    let checkboxs = $(`${formulario} input[name="${inputName}"]`);
    let values = [];
    for(var i = 0; i < checkboxs.length; i++) {
        if(checkboxs[i].checked == true){
            values.push(checkboxs[i].value); 
        }
    }
    if(values.length == 0){
        return 0;
    }
    if(toString){
        return values.toString();
    }
    return values;
}

/**
 * Converte um array em string
 * 
 * @param {array} vetor
 * @param {string} separador ", " por default
 * @returns {string}
 */
function arrayToString(vetor, separador = ", "){
    let retorno = "";
    for(i = 0; i < vetor.length; i++){
        if(i < vetor.length - 1){
            retorno += vetor[i] + separador;
        } else {
            retorno += vetor[i];
        }
    }
    return retorno.toString();
}

/**
 * Converte string em array
 * @param {string} str 
 * @param {string} separador
 * @returns {array}
 */
function stringToArray(str, separador = ", "){
    if(str != null){
        return str.split(separador);
    } else {
        return null;
    }
}

/**
 * Remove os espaços em branco de uma string
 *  
 * @param {string} srt
 * @returns {string}
 */
function removeWhiteSpace(srt){
    return srt.replace(/\s/g, '');
}

/**
 * Verifica pelo nome se um campo checkbox foi maracado
 * 
 * @param {string} form Identificador do formulário
 * @param {string} name Nome do Campo
 * @returns {Boolean}
 */
function isCheckedByName(form, name) {
    var checked = $(`${form} input[name=${name}]:checked`).length;
    if (checked == 0) {
        return false;
    } else {
        return true;
    }
}

/**
 * Verifica pelo id se um campo checkbox foi maracado
 * 
 * @param {string} form Identificador do formulário
 * @param {string} name Id do campo
 * @returns {Boolean}
 */
function isCheckedByID(form, id) {
    var checked = $(`${form} input[id=${id}]:checked`).length;
    if (checked == 0) {
        return false;
    } else {
        return true;
    }
}

/**
 * Retorna a primeira palavra de uma frase
 * 
 * @param {string} str
 * @returns {string}
 */
function getFirstWordOfPhrase(str){
    let pos = str.search(" ");
    return str.substring(0, pos);
}

/**
 * Adiciona atributo em todos os campos do formulário
 * 
 * @param {string} formulario Identificador formulário
 * @param {string} attr       Nome atributo "disabled" por default
 * @param {string} val        Valor atributo "true" por default
 */
function addAttrForAllInputsForm(formulario, attr = "disabled", val = true) {
    let inputs    = $(formulario + ' input'),
        selects   = $(formulario + ' select'),
        textAreas = $(formulario + ' textarea');

    for(let i = 0; i < inputs.length; i++) {
        inputs[i].setAttribute(attr, val);
    }

    for(let i = 0; i < selects.length; i++) {
        selects[i].setAttribute(attr, val);
    }

    for(let i = 0; i < textAreas.length; i++) {
        textAreas[i].Attribute(attr, val);
    }
}

/**
 * Remove atributo em todos os campos do formulário
 * 
 * @param {string} formulario Identificador formulário
 * @param {string} attr       Nome atributo "disabled" por default
 */
function removeAttrForAllInputsForm(formulario, attr = "disabled") {
    let inputs    = $(formulario + ' input'),
        selects   = $(formulario + ' select'),
        textAreas = $(formulario + ' textarea');

    for(let i = 0; i < inputs.length; i++) {
        inputs[i].removeAttribute(attr);
    }

    for(let i = 0; i < selects.length; i++) {
        selects[i].removeAttribute(attr);
    }

    for(let i = 0; i < textAreas.length; i++) {
        textAreas[i].removeAttribute(attr);
    }
}

/**
 * Fastest method to replace all instances of a character in a string [duplicate]
 *
 * @param {string} str1
 * @param {string} str2
 * @param {bool} ignore
 * @returns {String.prototype@call;replace}
 */
String.prototype.replaceAll = function(str1, str2, ignore){
    return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
} 

/**
 * Capitaliza string
 * @param {string} string
 * @returns {string}
 */
String.prototype.capitalize = function() 
{
    return this.charAt(0).toUpperCase() + this.slice(1).toLowerCase();
}
//---------------------- Functions de Date Time -----------------------//

/**
 * Formata a data do padrão ISO Date "Y-m-d" para o padrão Short Date "d/m/Y" por default
 * ou formata a data do padrão ISO Date "d/m/Y" para o padrão Short Date "Y-m-d" se o parâmetro "USA" for false
 * 
 * @param {string}  data
 * @param {bool}    USA  Define se usa padão americano
 * @returns {string}
 */
function formatDate(dateStr, USA = true)
{
  if(dateStr.length < 10) return "";  
  if(USA){
      dArr = dateStr.split("-");  // ex input "2010-01-18"
      return dArr[2]+ "/" +dArr[1]+ "/" +dArr[0]; //ex out: "18/01/2010"
  } else {
      dArr = dateStr.split("/");  // ex input "18/01/2010"
      return dArr[2]+ "-" +dArr[1]+ "-" +dArr[0]; //ex out: "2010-01-18"
  }
  
}

/**
 * There are 4 ways to create a new date object:
 * date Date new Date()
 * date Date new Date(year, month, day, hours, minutes, seconds, milliseconds)
 * date Date new Date(milliseconds)
 * date Date new Date(date string)
 * 
 * @param {object} Date
 * @returns {object}
 */
function getDateProperties(date = null){
    if(!date){
        date = new Date();
    }
    object = {};
    object.year              = date.getFullYear() ;
    object.month             = ((date.getMonth() + 1) < 10) ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1);
    object.day               = (date.getDate() < 10) ? '0' + date.getDate(): date.getDate();
    object.fullDate          = `${object.year}-${object.month}-${object.day}`;
    object.fullDateSlash     = formatDate(`${object.year}-${object.month}-${object.day}`);
    object.milliseconds      = date.getTime();
    object.hour              = (date.getHours() < 10) ? `0${date.getHours()}` : date.getHours();
    object.minutes           = (date.getMinutes() < 10) ? `0${date.getMinutes()}` : date.getMinutes();
    object.seconds           = (date.getSeconds() < 10) ? `0${date.getSeconds()}` : date.getSeconds();
    object.fullTime          = `${object.hour}:${object.minutes}:${object.seconds}`;
    object.fullDateTimeSlash = object.fullDateSlash + ' ' + object.fullTime;
    object.getYear = function(){
        return this.year;
    };
    object.getMonth = function(){
        return this.month;
    };
    object.getDay = function(){
        return this.day;
    };
    object.getFullDate = function(){
        return this.fullDate;
    };
    object.getFullDateSlash = function(){
        return this.fullDateSlash;
    };
    object.getMilliseconds = function(){
        return this.milliseconds;
    };
    object.getHour = function(){
        return this.hour;
    };
    object.getMinutes = function(){
        return this.minutes;
    };
    object.getSeconds = function(){
        return this.seconds;
    };
    object.getFullTime = function(){
        return this.fullTime;
    };
    object.getFullDateTime = function(){
        return this.fullDateTime;
    };
    object.getFullDateTimeSlash = function(){
        return this.fullDateTimeSlash;
    };
    
    return object;
}

/**
 * Retorna a data atual. Ex: 2020-01-01.
 * 
 * @returns {string}
 */
function getDataAtual() {
    return getDateProperties().fullDate;
}

/**
 * Retorna a hora atual. Ex: 10:55:23
 * 
 * @returns {string}
 */
function getHoraAtual() {
    return getDateProperties().fullTime;
}

/**
 * Retorna a hora atual. Ex: 10:55:23
 * 
 * @returns {string}
 */
function getDataHoraAtual() {
    return `${getDataAtual()} ${getHoraAtual()}`
}

