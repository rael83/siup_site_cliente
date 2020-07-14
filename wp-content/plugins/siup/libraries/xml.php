<?php
############ DS DIGITAL ###################

class Xml
{
	private static $listaAuto = NULL;
	################################################################################################################
	public function SalvarAutos(&$xmlstr = false)
	{
		if(empty($xmlstr))
			return false;
		$xml = new SimpleXMLElement($xmlstr);
		$this->GetConfiguracoes($xml->configuracoes);
		$this->SalvarVeiculos($xml->veiculos);
		$this->ApagarVeiculos();
		$this->Atualizar();
		return true;
	}
	################################################################################################################
	public function GetConfiguracoes($configuracoes = false)
	{
		if(empty($configuracoes))
			return false;
		$this->SalvarCliente($configuracoes->cliente);
		$this->SalvarFiltro($configuracoes->configuracao->filtros);
		$this->SalvarAtributos($configuracoes->configuracao->atributos);
		return true;
	}
	################################################################################################################
	public function SalvarCliente($cliente = false)
	{
		if(empty($cliente))
			return false;
		$dados = Componente::objectToArray($cliente);
		Componente::SalvarOpcao("COB_nome", $dados['cliente'], 'no',true);
		Componente::SalvarOpcao("COB_endereco", $dados['endereco'], 'no',true);
		return true;
	}
	################################################################################################################
	public function SalvarFiltro($filtros = false)
	{
		if(empty($filtros))
			return false;
		$dados = Componente::objectToArray($filtros);
		$filtro = $dados['filtro'];
		foreach ($filtro as $posicao=>$items)
		{
			foreach ($items as $nome=>$propriedade)
			{
				if(empty($propriedade))
				{
					$filtro[$posicao][$nome] = "";
				}
			}
		}
		Componente::SalvarOpcao("COB_Filtro", serialize($filtro), 'no',true);
		return true;
	}
	################################################################################################################
	public function SalvarAtributos($atributos = false)
	{
		if(empty($atributos))
			return false;
		$dados = Componente::objectToArray($atributos);
		$atributo = $dados['atributo'];
		foreach ($atributo as $posicao=>$items)
		{
			foreach ($items as $nome=>$propriedade)
			{
				if(empty($propriedade))
				{
					$atributo[$posicao][$nome] = "";
				}
			}
		}
		Componente::SalvarOpcao("COB_atributo", serialize($atributo), 'no',true);
		$auto = Componente::GetInstancia("veiculos");
		$auto->VerificarCampo($atributo);
		$auto->SetDefault();
		return true;
	}
	################################################################################################################
	public function SalvarVeiculos($veiculos = false)
	{
		if(empty($veiculos))
			return false;
		foreach ($veiculos->veiculo as $key => $auto)
		{
			$this->SalvarVeiculo($auto);
		}
		return true;
	}
	################################################################################################################
	public function SalvarVeiculo($veiculo = false)
	{
		if(empty($veiculo))
			return false;
		$dados = Componente::objectToArray($veiculo);
		foreach ($dados as $posicao=>$items)
		{
			if(empty($items))
			{
				$dados[$posicao] = "";
			}
		}
		$fotos = false;
		if(!empty($dados['fotos']['foto']))
		{
			$fotos = $dados['fotos']['foto'];
			unset($dados['fotos']);
		}
		$idveiculo = $this->AtualizarVeiculo($dados);
		if(empty($idveiculo))
			return false;
		if(is_array($fotos))
			$this->SalvarFotos($idveiculo, $fotos);
		return true;
	}
	################################################################################################################
	public function AtualizarVeiculo($dados = false)
	{
		if(empty($dados))
			return false;
		$obj = Componente::GetInstancia("veiculos");
		if(empty($obj))
			return false;
		if(empty($dados['idauto']))
			return false;
		self::AddListaAutos($dados['idauto']);
		$obj = $obj->FiltroObjeto("idauto = '{$dados['idauto']}'");
		if(empty($obj))
			$obj = Componente::GetInstancia("veiculos");
		$obj->Carregar($dados);
		$id = $obj->Salvar();
		if(!empty($obj->GetID()))
		{
			$id = $obj->GetID();
		}
		return $id;
	}
	################################################################################################################
	public function SalvarFotos($idveiculo = 0, $fotos = false)
	{
		if(empty($idveiculo))
			return false;
		if(!is_array($fotos))
			return false;
		$obj = Componente::GetInstancia("fotos");
		$lista = "";
		foreach ($fotos as $key=>$foto)
		{
			$obj->idfotos = 0;
			$obj->idveiculos = $idveiculo;
			$obj->foto = $foto;
			$filtro = " idveiculos = '{$idveiculo}' AND foto = '{$foto}'";
			if($obj->Load($filtro))
			{
				$lista .= ",'{$obj->idfotos}'";
				continue;
			}
			$id = $obj->Salvar();
			$lista .= ",'{$id}'";
		}
		if(!empty($lista))
		{
			$lista = substr($lista, 1);
			$objs = $obj->FiltroObjetos("idveiculos = '{$idveiculo}' AND idfotos NOT IN({$lista})");
			if($objs)
			{
				foreach ($objs as $key => $obj)
				{
					$obj->Apagar();
				}
			}
		}
		return true;
	}
	################################################################################################################
	public function Atualizar()
	{
		$obj = Componente::GetInstancia("atualizacoes");
		$obj->idusers = get_current_user_id();
		$obj->atualizadoem = date("Y-m-d H:i:s");
		$obj->ip = Componente::GetUserIP();
		$obj->Salvar();
		return true;
	}
	################################################################################################################
	public function ApagarVeiculos()
	{
		if(self::$listaAuto == NULL)
			return;
		$obj = Componente::GetInstancia("veiculos");
		$filtro = self::$listaAuto;
		$filtro = " idauto NOT IN({$filtro }) ";
		$objs = $obj->FiltroObjetos($filtro );
		if($objs)
		{
			foreach ($objs as $key => $obj)
			{
				$obj->ApagarAll();
			}
		}
		return true;
	}
	################################################################################################################
	public static function AddListaAutos($idauto = 0)
	{
		if(empty($idauto))
			return;
		if(self::$listaAuto == NULL)
			self::$listaAuto = "{$idauto}";
		else
			self::$listaAuto .= ", {$idauto}";
		return;
	}
}