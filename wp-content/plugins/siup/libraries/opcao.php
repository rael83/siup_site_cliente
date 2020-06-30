<?php
class Opcao extends Meumodelo
{
    const PREFIX = PLUGIN_PREFIX."_";
	################################################################################################################
	public function __construct($dados = false)
	{
		$this->Tabela = "options";
		$this->PrimaryKey = "option_id";
		$this->Carregar($dados);
	}
	################################################################################################################
	public function GetOpcoes()
	{
		$lista = false;
		#region criar um variavel de opção dentro do plugin
		$titulo = __("Chave de integração com siup.");
		$descricao = __("Informe a chave de integração com siup.");
		$icone = "ion ion-android-search";
        $lista[] = $this->CriarOpcao("chaveintegracao", $titulo, "text", $descricao, $icone,"");
        
        $titulo = __("token de integração com siup.");
		$descricao = __("Informe a token de integração com siup.");
		$icone = "ion ion-android-search";
        $lista[] = $this->CriarOpcao("tokenintegracao", $titulo, "text", $descricao, $icone,"");
        
        $titulo = __("ID do usuário responsável para receber as demandas dos pedidos.");
		$descricao = __("Informe o ID do usuário responsável do siup para  receber as demandas dos pedidos.");
		$icone = "ion ion-android-search";
        $lista[] = $this->CriarOpcao("idresponsavelsiup", $titulo, "number", $descricao, $icone,0);

        $titulo = __("status do plugin siup.");
		$descricao = __("Informe o status do plugin siup.");
		$icone = "ion ion-android-search";
        $lista[] = $this->CriarOpcao("statussiup", $titulo, "number", $descricao, $icone,"ativado");

		#endregion
		
		return $lista;
	}
	################################################################################################################
	public function CriarOpcao($nome = "", $titulo = "", $tipo = "text", $descricao = "", $icone = "fa fa-chain", $default = "", $opcoes = false )
	{
		return array("chave"=>$nome, "nome"=>self::PREFIX.$nome, "tipo"=>$tipo, "icone"=>$icone, "titulo"=>$titulo, "descricao"=>$descricao, "default"=>$default, "opcoes"=>$opcoes);
	}
	################################################################################################################
	public function ListaAtributo($chave = 'nome')
	{
        $records = $this->GetOpcoes();
		return array_column($records, $chave);
	}
	################################################################################################################
	public function GetAtributos($chave = '')
	{
		$records = $this->GetOpcoes();
		$key = array_search($chave, array_column($records, 'chave'));
		if($key === false)
			return false;
		return $records[$key];
	}
    ################################################################################################################
    public function GerarForm()
    {
        $lista = $this->GetOpcoes();
        if($lista)
        {
            foreach($lista as $item)
            {
                $this->MontaCompo($item);
            }
        }
    }
    ################################################################################################################
    public function MontaCompo($dados = false)
    {
        if(is_array($dados))
        {
            if(!empty($dados['icone']))
            {
                $dados['icone'] = "<i class=\"{$dados['icone']}\"></i>";
            }
            $id = $dados['chave'];
            $valor = get_option($dados['nome'], $dados['default']);
            switch($dados['tipo'])
            {
                case "text":
                case "text":
                case "color":
                case "date":
                case "datetime-local":
                case "email":
                case "month":
                case "number":
                case "range":
                case "search":
                case "tel":
                case "time":
                case "url":
                case "week":
                    $dados['campo'] = "<input id=\"{$id}\" name=\"{$id}\" type=\"{$dados['tipo']}\" value=\"{$valor}\" class=\"form-control\">";
                    break;
                case "textarea":
                    if(!empty($dados['icone'])) {

                        $dados['titulo'] .= " {$dados['icone']}";
                        $dados['icone'] = "";
                    }
                    $dados['campo'] = "<textarea id=\"{$id}\" name=\"{$id}\" class=\"form-control\" style=\"height: 250px !important;\">{$valor}</textarea>";
                    break;
                case "select":
                    $lista = "<option value=\"\">-- Selecionar --</option>";
                    if(!empty($dados['icone']))
                        $stylo = " style=\"padding-left: 26px;\"";
                    else
                        $stylo = "";
                    if(is_array($dados['opcoes']))
                    {
                        foreach($dados['opcoes'] as $key=>$item)
                        {
                            $checked = "";
                            if(is_numeric($key))
                            {
                                if($item == $valor)
                                    $checked = " selected";
                                $lista .= "<option{$checked} value=\"{$item}\">{$item}</option>";
                            }
                            else
                            {
                                if($key == $valor)
                                    $checked = " selected";
                                $lista .= "<option{$checked} value=\"{$key}\">{$item}</option>";
                            }
                        }
                    }
                    $dados['campo'] = "<select id=\"{$id}\" name=\"{$id}\" class=\"form-control\"{$stylo}>{$lista}</select>";
                    break;
                case "checkbox":
                case "radio":
                    $lista = "";
                    $dados['icone'] = "";
                    if(is_array($dados['opcoes']))
                    {
                        $stylo = " style=\"padding: 0px !important; margin: 3px 5px 0px 0px;\"";
                        foreach($dados['opcoes'] as $key=>$item)
                        {
                            $checked = "";
                            if(is_numeric($key))
                            {
                                if($item == $valor)
                                    $checked = " checked";
                                $lista .= "<input type=\"{$dados['tipo']}\" id=\"{$id}\" name=\"{$id}\" value=\"{$item}\"{$stylo}> {$item}<br>";
                            }
                            else
                            {
                                if($key == $valor)
                                    $checked = " checked";
                                $lista .= "<input type=\"{$dados['tipo']}\" id=\"{$id}\" name=\"{$id}\" value=\"{$key}\"{$stylo}> {$item}<br>";
                            }
                        }
                    }
                    $dados['campo'] = $lista;
                    break;
                default:
                    $dados['campo'] = "<input id=\"{$id}\" name=\"{$id}\" type=\"text\" value=\"{$valor}\" class=\"form-control\">";
            }
            echo Componente::LerViews("configuracaoopcao", $dados);
        }
    }
    ################################################################################################################
    public function GetPrefix($dados = false)
    {
        return self::PREFIX;
    }
	################################################################################################################
	public function GetNomeOpcao($nome = false, $default = "")
	{
		if(empty($nome))
			return $default;
		return self::PREFIX.$nome;
	}
    ################################################################################################################
    public function GetOpcao($nome = false, $default = "")
    {
        if(empty($nome))
            return $default;
        return get_option(self::PREFIX.$nome, $default);
    }
	################################################################################################################
	public function Instalacao($atualizar = true, $autoload = "no")
	{
		$opcao = Componente::GetInstancia('opcao');
		$lista = $opcao->GetOpcoes();
		if($lista)
		{
			foreach ($lista as $key=>$item)
				Componente::SalvarOpcao($item['nome'], $item['default'], $autoload, $atualizar);
		}
		return;
	}
	################################################################################################################
	public function SalvarOpcao($nome = "", $valor = "")
	{
		if(empty($nome))
			return false;
		$item = $this->GetAtributos($nome);
		if(!empty($item))
		{
			if(empty($valor))
				$valor = $item['default'];
			Componente::SalvarOpcao($item['nome'], $valor);
		}
		return true;
	}
}
?>