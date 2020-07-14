function Excecao(ex) {
    try {
        var mes = "Nome:" + ex.name;
        mes += "\nMessagem:" + ex.message;
        if (typeof ex.number != "undefined")
            mes += "\nLinha:" + ex.number;
        if (typeof ex.lineNumber != "undefined")
            mes += "\nLinha:" + ex.lineNumber;
        if (typeof ex.fileName != "undefined")
            mes += "\nArquivo:" + ex.fileName;
        if (typeof ex.description != "undefined")
            mes += "\nDesciçao:" + ex.description;
        if (typeof ex.stack != "undefined")
            mes += "\nDesciçao:" + ex.stack;
        alert(mes);
        console.log(ex);
        return false;
    } catch (err) {
        var mes = "Nome:" + err.name;
        mes += "\nMessagem:" + err.message;
        if (typeof err.number != "undefined")
            mes += "\nLinha:" + err.number;
        if (typeof err.lineNumber != "undefined")
            mes += "\nLinha:" + err.lineNumber;
        if (typeof err.fileName != "undefined")
            mes += "\nArquivo:" + err.fileName;
        if (typeof err.description != "undefined")
            mes += "\nDesciçao:" + err.description;
        if (typeof err.stack != "undefined")
            mes += "\nDesciçao:" + err.stack;
        alert(mes);
        console.log(err);
        return false;
    }
}
String.prototype.replaceAll = String.prototype.replaceAll || function(needle, replacement)
{
    return this.split(needle).join(replacement);
};
String.prototype.reverse=function()
{
    return this.split("").reverse().join("");
};
function strip_tags(str)
{
    str = str.toString();
    return str.replace(/<\/?[^>]+>/gi, '');
}
function ObjetoReplace(obj,caixa, caixa2)
{
    try
    {
        var tipo = TypeOf(obj);
        var chave = null;
        if(tipo == "undefined")
            return caixa;
        if(Vazio(caixa2))
            caixa2 = "";
        if(tipo == "object")
        {
            for(var chave in obj )
            {
                tipo = TypeOf(obj[chave]);
                if(tipo == "undefined")
                {
                    caixa = caixa.replaceAll("{"+chave+"}", '');
                    continue;
                }
                if(tipo == "object")
                {
                    caixa = ObjetoReplace(obj[chave],caixa);
                    continue;
                }
                if(tipo == "array")
                {
                    var lista = obj[chave];
                    var aux = "";
                    for(var keydados in lista )
                    {
                        tipo = TypeOf(lista[keydados]);
                        if(tipo == "undefined")
                            continue;
                        if(tipo == "object")
                        {
                            aux += ObjetoReplace(lista[keydados],caixa2);
                            continue;
                        }
                        if(tipo == "array")
                        {
                            aux += ObjetoReplace(lista[keydados],caixa2);
                            continue;
                        }
                        if(Vazio(aux))
                            aux = lista[keydados];
                        else
                            aux = ", " + lista[keydados];
                    }
                    caixa = caixa.replaceAll("{"+chave+"}", aux);
                    continue
                }
                caixa = caixa.replaceAll("{"+chave+"}", obj[chave]);
            }
            return caixa;
        }
        if(tipo == "array")
        {
            var lista = "";
            for(chave in obj )
                lista += ObjetoReplace(obj[chave],caixa);
            return lista;
        }
    }
    catch (ex)
    {
        return ex;
    }
}
function TypeOf(obj)
{
    if (obj == null)
        return "undefined";
    var tipo = typeof(obj);
    if (tipo == "object")
    {
        if((obj.context)&&(obj.selector))
            return "jquery";
        if (obj.length)
            return "array";
        else
            return "object";
    }
    else
    {
        if (tipo == null)
            return "undefined";
        else
            return typeof(obj);
    }
}
function ValidarCNPJ(arguments) {
    var cgc = arguments.value;
    var n1, n2, n3, n4, n5, n6, n7, n8, n9, n10, n11, n12, n13, n14;
    var d1, d2;
    var digitado, calculado;
    cgc = cgc.replace(/([^0-9])/g, '');
    if (cgc.length < 14) {
        arguments.IsValid = false;
        return arguments.IsValid;
    }

    n1 = cgc.substring(0, 1);
    n2 = cgc.substring(1, 2);
    n3 = cgc.substring(2, 3);
    n4 = cgc.substring(3, 4);
    n5 = cgc.substring(4, 5);
    n6 = cgc.substring(5, 6);
    n7 = cgc.substring(6, 7);
    n8 = cgc.substring(7, 8);
    n9 = cgc.substring(8, 9);
    n10 = cgc.substring(9, 10);
    n11 = cgc.substring(10, 11);
    n12 = cgc.substring(11, 12);
    n13 = cgc.substring(12, 13);
    n14 = cgc.substring(13, 14);

    d1 = n12 * 2 + n11 * 3 + n10 * 4 + n9 * 5 + n8 * 6 + n7 * 7 + n6 * 8 + n5 * 9 + n4 * 2 + n3 * 3 + n2 * 4 + n1 * 5;
    d1 = 11 - (d1 % 11);
    if (d1 >= 10)
        d1 = 0;
    d2 = d1 * 2 + n12 * 3 + n11 * 4 + n10 * 5 + n9 * 6 + n8 * 7 + n7 * 8 + n6 * 9 + n5 * 2 + n4 * 3 + n3 * 4 + n2 * 5 + n1 * 6;
    d2 = 11 - (d2 % 11);
    if (d2 >= 10)
        d2 = 0;
    calculado = d1 + d2;
    digitado = n13 * 1 + n14 * 1;
    if (calculado == digitado)
        arguments.IsValid = true;
    else
        arguments.IsValid = false;
    return arguments.IsValid;
}
function ValidaCPF(args) {
    var s = args.value;
    s = s.replace(/[.]/g, '');
    s = s.replace('-', '');

    if (isNaN(s)) {
        return args.IsValid = false;
    }
    var result = true
    for (i = 1; i < s.length; i++) {
        result = result && (s.charAt(i - 1) == s.charAt(i));
    }

    var i;
    var c = s.substr(0, 9);
    var dv = s.substr(9, 2);
    var d1 = 0;
    for (i = 0; i < 9; i++) {
        d1 += c.charAt(i) * (10 - i);
    }

    if (d1 == 0) {
        return args.IsValid = false;
    }

    d1 = 11 - (d1 % 11);
    if (d1 > 9) d1 = 0;
    if (dv.charAt(0) != d1) {
        return args.IsValid = false;
    }

    d1 *= 2;
    for (i = 0; i < 9; i++) {
        d1 += c.charAt(i) * (11 - i);
    }

    d1 = 11 - (d1 % 11);
    if (d1 > 9) d1 = 0;
    if (dv.charAt(1) != d1) {
        return args.IsValid = false;
    }
    return args.IsValid = (!result);
}
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
function createCookie(name, value, days)
{
    if (days)
    {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else
        var expires = "";
	window.localStorage.removeItem(name);
    window.localStorage.setItem(name, value);
    document.cookie = name + "=" + value + expires + "; path=/";
}
function readCookie(name)
{
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++)
    {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0)
            return c.substring(nameEQ.length, c.length);
    }
	var valor = window.localStorage.getItem(name);
	if(!Vazio(valor))
		return valor;
    return null;
}
function eraseCookie(name)
{
	var date = new Date();
    date.setTime(date.getTime() + (-2 * 24 * 60 * 60 * 1000));
    var expires = "; expires=" + date.toGMTString();
    document.cookie = name + "="+ expires + "; path=/";
	window.localStorage.removeItem(name);
}
function RetirarAcento(texto)
{
    var chrEspeciais = new Array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë",
        "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö",
        "ú", "ù", "û", "ü", "ç",
        "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë",
        "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö",
        "Ú", "Ù", "Û", "Ü", "Ç");
    var chrNormais = new Array("a", "a", "a", "a", "a", "e", "e", "e", "e",
        "i", "i", "i", "i", "o", "o", "o", "o", "o",
        "u", "u", "u", "u", "c",
        "A", "A", "A", "A", "A", "E", "E", "E", "E",
        "I", "I", "I", "I", "O", "O", "O", "O", "O",
        "U", "U", "U", "U", "C");
    for (index in chrEspeciais)
    {
        texto = texto.replace(chrEspeciais[index], chrNormais[index]);
    }

    return texto;
}
function RetirarBranco(texto)
{
    if (arguments.length == 2)
    {
        var caracter = arguments[1];
    }
    else
    {
        var caracter = "";
    }
    texto = texto.replace(/[ ]/g, caracter);
    return texto;
}
function RetirarAcentoeBranco(texto)
{
    texto = RetirarAcento(texto);
    texto = RetirarBranco(texto);
    return texto;
}
function Vazio(valor)
{
    try
    {
        var tipo = TypeOf(valor);
        if (tipo == "undefined")
            return true;
        if ((tipo == "number") || (tipo == "boolean"))
        {
            if (Boolean(valor))
                return false;
        }
        if (tipo == "string")
        {
            valor = valor.trim();
            if ((valor != "0") && (valor.length > 0))
                return false;
        }
        if(tipo == "object")
        {
            return false;
        }
        if(tipo == "array")
        {
            return false;
        }
        if(tipo == "function")
        {
            return false;
        }
        if(tipo == "jquery")
        {
            if ((valor !== false) && (valor.length > 0))
                return false;
        }
        return true;
    }
    catch (ex)
    {
        return Excecao(ex);
    }
}
function BuscaCep(_cep)
{
        var getJSON = function (url, sucesso, erro){
                var httpRequest = new XMLHttpRequest();
                httpRequest.open("GET", url, true);
                httpRequest.responseType = "json";
                httpRequest.addEventListener("readystatechange", function (event) {
                if (httpRequest.readyState == 4) {
                    if (httpRequest.status == 200) {
                        if (sucesso) sucesso(httpRequest.response);
                        } else {
                            if (erro) erro(httpRequest.status, httpRequest.statusText);
                    }
                }
            });

            httpRequest.send();
        }

        //var url = "https://panfletododia.com.br/api/buscarcep.php?cep=" + _cep;
        var url = "https://panfletododia.com.br/api/buscarcep.php?cep=30855200";

        //para chamar o método, faça o seguinte
        getJSON(url, function (data) {
            if(data.resultado == "1"){
                console.log("cep: "+data.cep);
                console.log("cidade: "+data.cidade);
                console.log("bairro: "+data.bairro);
                console.log("logradouro: "+data.tipo_logradouro+" "+data.logradouro);

                document.getElementById("rua").value = data.tipo_logradouro+" "+data.logradouro;
                document.getElementById("cidade").value = data.cidade;
                document.getElementById("bairro").value = data.bairro;
                document.getElementById("estado").value = data.uf;
                document.getElementById("rua").focus();
            } else{
                console.log("cep nao existe");
                document.getElementById("rua").value = "";
                document.getElementById("cidade").value = "";
                document.getElementById("bairro").value = "";
                document.getElementById("estado").value = "";
                $("#rua, #cidade, #bairro, #estado").removeAttr( "readonly" );
                alert("CEP não encontrado!");
            }
        }, function (errorCode, errorText) {
            console.log('Código: ' + errorCode);
            console.log('Mensagem de erro: ' + errorText);
        });
}

function LerDominio()
{
    var url = window.location.origin;
    var host = window.location.host;
    if(is_localhost())
    {
        var parte = window.location.pathname.split('/');
        url += "/"+parte[1];
    }
    return url;
}
function GetDominio(link)
{
    var url = LerDominio()+"/" + link;
    return url;
}
function GetURL(link)
{
    var url = GetDominio("wp-admin/" + link);
    return url;
}
function buscaCep(_cep){
    var getJSON = function (url, sucesso, erro){
            var httpRequest = new XMLHttpRequest();
            httpRequest.open("GET", url, true);
            httpRequest.responseType = "json";
            httpRequest.addEventListener("readystatechange", function (event) {
            if (httpRequest.readyState == 4) {
                if (httpRequest.status == 200) {
                    if (sucesso) sucesso(httpRequest.response);
                    } else {
                        if (erro) erro(httpRequest.status, httpRequest.statusText);
                }
            }
        });

        httpRequest.send();
    }

    var url = GetURL("/api/buscarcep.php?cep=") + _cep;
    //var url = "https://panfletododia.com.br/buscarcep.php?cep=30855200";


    //para chamar o método, faça o seguinte
    getJSON(url, function (data) {
        if(data.resultado == "1"){
           console.log("cep: ", data);
             /*console.log("cidade: "+data.cidade);
            console.log("bairro: "+data.bairro);
            console.log("logradouro: "+data.tipo_logradouro+" "+data.logradouro);*/

            document.getElementById("rua").value = data.tipo_logradouro+" "+data.logradouro;
            document.getElementById("cidade").value = data.cidade;
            document.getElementById("bairro").value = data.bairro;
            document.getElementById("estado").value = data.estado;
            document.getElementById("pais").value = data.pais;

            document.getElementById("idcidade").value = data.idcidade;
            document.getElementById("idbairro").value = data.idbairro;
            document.getElementById("idestado").value = data.idestado;
            document.getElementById("idpais").value = data.idpais;


            document.getElementById("rua").focus();
        } else{
            //console.log("cep nao existe");
            document.getElementById("rua").value = "";
            document.getElementById("cidade").value = "";
            document.getElementById("bairro").value = "";
            document.getElementById("estado").value = "";
            document.getElementById("pais").value = "";

            document.getElementById("idcidade").value = 0;
            document.getElementById("idbairro").value = 0;
            document.getElementById("idestado").value = 0;
            document.getElementById("idpais").value = 0;

            alert("CEP não encontrado!");
        }
    }, function (errorCode, errorText) {
        console.log('Código: ' + errorCode);
        console.log('Mensagem de erro: ' + errorText);
    });
}
function EditarLink(link, obj)
{
    obj = $(obj);
    var id = obj.val();
    if(Vazio(id))
        return;
    var url = GetURL(link) +'?'+obj.get(0).id+'='+id;
    window.open(url);
}
function PrintErro(texto,obj)
{
    var tipo = TypeOf(texto);
    if(Vazio(texto))
    {
        texto = "Ocorreu um erro desconhecido.";
    }
    else if(tipo == "undefined")
    {
        texto = "Ocorreu um erro desconhecido.";
    }
    else if(tipo == "function")
    {
        texto = "Ocorreu um erro desconhecido.";
    }
    else if(tipo == "function")
    {
        texto = "Ocorreu um erro desconhecido.";
    }
    else if(tipo == "object")
    {
        if(!Vazio(texto.toString()))
            texto = texto.toString();
        else
        {
            var aux = texto;
            var lista = "";
            var icone = "";
            if(aux.length > 1)
                icone = "* ";
            for(x in aux)
                lista += icone+aux[x];
            texto = lista;
        }
    }
    else if(tipo == "boolean")
    {
        if(texto)
            texto = "vedadeiro";
        else
            texto = "Falso";
    }
    if(!Vazio(obj))
    {
        var html = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Erro!</h4>'+texto+'</div>';
        $(obj).html(html);
    }
    else
        alert(texto,"error","Erro:");
}
function PrintSucesso(obj,texto)
{
    var tipo = TypeOf(texto);
    var lista = "";
    if(tipo == "undefined")
        lista = "Ocorreu um erro desconhecido.";
    else if(tipo == "array")
    {
        for(var x in texto)
            lista += texto[x] + "<br/>";
    }
    else if(tipo == "object")
    {
        lista += "{<br/>";
        for(var x in texto)
            lista += x +": "+texto[x] + "<br/>";
        lista += "}";
    }
    else if(tipo == "boolean")
    {
        if(texto)
            lista = "True";
        else
            lista = "False";
    }
    else
        lista = texto;
    var html = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Sucesso!</h4>'+texto+'</div>';
    $(obj).html(html);
}
function Redirecionar(link)
{
    if((link.search("http://") >= 0)||(link.search("https://") >= 0))
        var url = link;
    else
        var url = GetURL(link);
    window.location = url;
}
function Redirecione(link, delay )
{
    if((link.search("http://") >= 0)||(link.search("https://") >= 0))
        var url = link;
    else
        var url = GetDominio(link);
    if(Vazio(delay))
        window.location = url;
    else
    {
        delay = parseInt(delay) * 1000;
        setTimeout(function () {
            window.location = url;
        }, delay);
    }
}
function is_localhost()
{
    //return false;
    var myUrlPattern = '.local';
    if (window.location.hostname === "localhost" || window.location.hostname === "wash-pc" || location.hostname === "127.0.0.1" || window.location.hostname.indexOf(myUrlPattern) >= 0  || window.location.protocol == "file:")
    {
        return true;
    }
    return false;
}

var SPMaskBehavior = function (val)
    {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    };
var spOptions = {
        onKeyPress: function(val, e, field, options)
        {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
        }
    };
function formatFoneBR(obj)
{
    var $ = jQuery.noConflict();
    $(obj).mask(SPMaskBehavior, spOptions);
}
// ADD THE char TO LEFT/RIGHT OF STRING
String.prototype.pad = function(len, char, left){
    char = char || 0;
    left = left || 0;
    var value = this.toString();
    while(value.length < len){
        var r = [
            char+value,
            value+char,
        ];
        value = r[left];
    }
    return value;
}

// ADD THE char TO LEFT OF STRING
String.prototype.lpad = function(len, char){
    return this.pad(len, char, 0);
}

// ADD THE char TO RIGHT OF STRING
String.prototype.rpad = function(len, char){
    return this.pad(len, char, 1);
}

// date.toDate('dd/mm/yyyy hh:ii:ss');
// CONVERT DATE TO STRING BY FOLLOW THE MASK PASSED
Date.prototype.toDate = function(mask){
    var value = {
        D : this.getDate().toString().lpad(2),
        M : (+this.getMonth()+1).toString().lpad(2),
        Y : this.getFullYear().toString().lpad(4),

        H : this.getHours().toString().lpad(2),
        I : this.getMinutes().toString().lpad(2),
        S : this.getSeconds().toString().lpad(2),
    }

    for(var i in value){
        var r = new RegExp(i, 'ig');
        var len = (mask.match(r) || []).length;
        if(len > 0){
            r = new RegExp(i+'+', 'ig');
            var replace = value[i];
            mask = mask.replace(r, replace);
        }
    }
    return mask;
}
function formatDecimal(obj, pais, isDecimal)
{
    var $ = jQuery.noConflict();
    var decOptions = {
        onKeyPress: function(val, e, field, options) {
            num = field.val().replace(/\D/g, '');
            num = parseFloat(num);
            if(num==e.key)
                field.val(num);
        },
        reverse: true,
        clearIfNotMatch: true
    };
    if(TypeOf(isDecimal) == "undefined")
        isDecimal = true;
    if(TypeOf(pais) == "undefined")
        pais = "pt-br";
    if(pais == "pt-br")
    {
        if(Vazio(isDecimal))
            $(obj).mask("###.###.###", decOptions);
        else
            $(obj).mask("###.###.###,00", decOptions);
        return;
    }
    if(pais == "en")
    {
        if(Vazio(isDecimal))
            $(obj).mask("###,###,###", decOptions);
        else
            $(obj).mask("###,###,###.00", decOptions);
        return;
    }
    if(pais == "fr")
    {
        if(Vazio(isDecimal))
            $(obj).mask("###.###.###", decOptions);
        else
            $(obj).mask("###.###.###,00", decOptions);
        return;
    }
    if(pais == "ar")
    {
        if(Vazio(isDecimal))
            $(obj).mask("###.###.###", decOptions);
        else
            $(obj).mask("###.###.###,00", decOptions);
        return;
    }
    if(pais == "de")
    {
        if(Vazio(isDecimal))
            $(obj).mask("###,###,###", decOptions);
        else
            $(obj).mask("###,###,###.00", decOptions);
        return;
    }
    if(pais == "it")
    {
        if(Vazio(isDecimal))
            $(obj).mask("###.###.###", decOptions);
        else
            $(obj).mask("###.###.###,00", decOptions);
        return;
    }
    if(pais == "es")
    {
        if(Vazio(isDecimal))
            $(obj).mask("###.###.###", decOptions);
        else
            $(obj).mask("###.###.###,00", decOptions);
        return;
    }
    if(Vazio(isDecimal))
        $(obj).mask("###.###.###", decOptions);
    else
        $(obj).mask("###.###.###,00", decOptions);
}
function formatMoney(n, c, d, t)
{
    c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}
function GetNumero(texto)
{
    if(Vazio(texto))
        return 0;
    texto = texto.replace(/([^0-9])/g,'');
    var num = parseInt(texto);
    return num;
}
function SetLabelUnidade(unidade, idcategoria)
{
    var $ = jQuery.noConflict();
    var text;
    switch (idcategoria)
    {
        case '13': //bolo
            text = "Informe o preço do seu produto ou serviço a partir de um bolo para 50 pessoas.";
            $("#labelapartirde").html(text);
            return;
            break;
       case '7': //Salão de festas infantil
            text = "Informe o preço do seu produto ou serviço a partir de um espaço para 100 pessoas.";
            $("#labelapartirde").html(text);
            return;
            break;
        case '18':
        case '16': //Foto Filmagem:
            text = "Informe o preço do seu produto ou serviço.";
            $("#labelapartirde").html(text);
            return;
            break;

    }
    switch (unidade)
    {
        case 'Por unidade':
            text = "Informe o preço do seu produto ou serviço a partir de uma unidade.";
            break;
        case 'Por pessoa':
            text = "Informe o preço do seu produto ou serviço a partir do custo por pessoas.";
            break;
        case 'a dúzia':
            text = "Informe o preço do seu produto ou serviço a partir de uma dúzia.";
            break;
        case 'o cento':
            text = "Informe o preço do seu produto ou serviço a partir de um cento.";
            break;
        case 'Por kilo':
            text = "Informe o preço do seu produto ou serviço a partir do custo por kilo.";
            break;
        case 'Por hora':
            text = "Informe o preço do seu produto ou serviço a partir do custo por hora.";
            break;
        case 'Por evento':
            text = "Informe o preço do seu produto ou serviço a partir do custo por evento.";
            break;
        default:
            text = "Informe o preço do seu produto ou serviço a partir de uma unidade.";
    }
    $("#labelapartirde").html(text);
}
function eMoney(valor)
{
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL', minimumFractionDigits: 2 }).format(valor);
}
function jMoney(valor)
{
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL', minimumFractionDigits: 2 }).format(valor);
}
function initMap() {
    if(Vazio(document.getElementById('map')))
        return;
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -19.915976, lng: -43.940187},
        zoom: 12
    });
    map.addListener('click', function(event) {
        addMarker(event.latLng);
    });
}
function AdicionarNovo(url)
{
    window.location = url;
}