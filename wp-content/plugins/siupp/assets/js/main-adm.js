function SetInfo(data, redirecionar)
{
	var $ = jQuery.noConflict();
	if(Vazio(data))
	{
		SetErro("Erro ao passa o paramento.");
		return;
	}
	if(Vazio(data.dados))
	{
		SetErro("Erro na estrutura dos dados.");
		return;
	}
	if(Vazio(data.dados.mensagem))
	{
		SetErro("Erro na operação.");
		return;
	}
	else
		lista = data.dados.mensagem;
	var html = '<i class="ion ion-checkmark"></i> '+lista;
	$("#HOSTmensagem span").html(html);
	$("#HOSTmensagem").removeClass().addClass( "sucesso" );
    window.scrollTo(0, 0);
    if(!Vazio(redirecionar))
    {
        alert(data.dados.mensagem);
        Redirecionar(redirecionar);
    }
}
function SetMessagem(mensagem)
{
    var $ = jQuery.noConflict();
    if(Vazio(mensagem))
    {
        SetErro("Erro na operação.");
        return;
    }
    else
        lista = mensagem;
    var html = '<i class="ion ion-checkmark"></i> '+lista;
    $("#HOSTmensagem span").html(html);
    $("#HOSTmensagem").removeClass().addClass( "sucesso" );
}
function SetErro(erros)
{
	var $ = jQuery.noConflict();
	var tipo = TypeOf(erros);
	if(tipo == "undefined")
		lista = "Ocorreu um erro desconhecido";
	else if(tipo == "string")
		lista = erros;
	else if(tipo == "array")
	{
		lista = "";
		if(erros.length == 1)
		{
			lista +=  erros[0];
		}
		else
		{
			for (i = 0; i < erros.length; i++)
			{
				lista += "&bull; " + erros[i] + "<br>";
			}
		}
	}
	else
		lista = "Ocorreu um erro desconhecido";

	var html = '<i class="fa fa-exclamation-circle"></i> '+lista;
	$("#HOSTmensagem span").html(html);
	$("#HOSTmensagem").removeClass().addClass( "erro" );
    window.scrollTo(0, 0);
}
function FecharMSN(obj)
{
	var $ = jQuery.noConflict();
	$(obj).parent().removeClass();
}
function ExibePainel(obj,nome)
{
	var $ = jQuery.noConflict();
    var display = $(nome).css("display")
    if(display == "none")
    {
        $(nome).slideDown( "slow" );
        $(obj).removeClass().addClass("fa fa-chevron-down");
    }
    else
    {
        $(nome).slideUp( "slow" );
        $(obj).removeClass().addClass("fa fa-chevron-up");
    }
}