<?php
/***********************************************************************
 * Module:  /models/Equipe_model.php
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	04/06/2020 02:27:34
 * Purpose: Definição da Classe Equipe_model
 * Objeto:  $equipe = Competencia::GetInstancia("equipe");
 ***********************************************************************/
if (!class_exists('Equipe_model'))
{
	class Equipe_model extends Meumodelo
	{
		#region AREA PARA IMPLEMENTAÇÃO CABEÇARIO.
		################################################################################################################
		public function __construct( $dados = false)
		{
			try
			{
				$this->Tabela = "equipe";
				$this->PrimaryKey = "idequipe";
				$this->Prefix = "";
				parent::__construct($dados);
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
			}
		}
		################################################################################################################
		public function Ajustar($salvar = false)
		{
			if($salvar)
			{

			}
			else
			{

			}
		}
		#endregion FIM DA AREA PARA IMPLEMENTAÇÃO CABEÇARIO.
		#region AREA PARA IMPLEMENTAÇÃO CORPO.
		################################################################################################################
		public function GetSqlLista()
		{
			$retorno = "";
			try
			{
				$tabela = $this->GetTabela();
				return "SELECT * FROM {$tabela} ";
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function GetSqlTotalLista()
		{
			$retorno = "";
			try
			{
				$tabela = $this->GetTabela();
				return "SELECT COUNT(*) AS CONT FROM {$tabela} ";
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		#######################################################################################################
		public function Filtro($semOrder = false)
		{
			$filtro = "";
			$buscar = Componente::GetFiltro("buscar");
			if(!empty($buscar))
			{
				$filtro .= " AND nome LIKE '%{$buscar}%'";
			}
			
			$cargo = Componente::GetFiltro("cargo");
			if(!empty($cargo))
			{
				$filtro .= " AND cargo = '{$cargo}'";
			}
			$status = Componente::GetFiltro("status");
			if(!empty($status))
			{
				$filtro .= " AND status = '{$status}'";
			}
			$cadastradoem = Componente::GetFiltro("cadastradoem");
			if(!empty($cadastradoem))
			{
				$filtro .= " AND cadastradoem = '{$cadastradoem}'";
			}
			if(!empty($filtro))
			{
				$filtro  = substr($filtro, 4);
			}
			if($semOrder)
			{
				return $filtro;
			}
			$ordem = array('idequipe', 'iduser', 'Nome', 'cargo', 'descricao', 'foto', 'status', 'ip', 'cadastradoem', 'idequipe');
			$start = Componente::Request("start", 0);
			$length = Componente::Request("length", 10);
			$order = Componente::Request("order", 0,0);
			if(!empty($order['column']))
				$coluna = $order['column'];
			else
				$coluna = 0;
			if(!empty($order['dir']))
				$dir = $order['dir'];
			else
				$dir = 'asc';
			if(!empty($ordem[$coluna]))
			{
				$order = $ordem[$coluna];
				$filtro .= " ORDER BY {$order} {$dir}";
			}
			if($length >= 0)
			{
				$filtro .= " LIMIT {$start}, {$length}";
			}
			return $filtro;
		}
		################################################################################################################
		public function Importar($posicao = 0, $limite = 0, $file = "")
		{
			$retorno = false;
			
			if(empty($file))
				return $retorno;
			
			$lista = $this->LerRows($posicao, $limite, $file);
			if(empty($lista))
				return $retorno;
			$obj = Componente::GetInstancia("equipe");
			if(empty($obj))
				return $retorno;
			foreach ($lista as $key=>$linha)
			{
				$obj->SalvarListaEquipe($linha);
			}
			return true;
		}
		################################################################################################################
		public function SalvarListaEquipe($dados = false)
		{
			if(empty($dados))
				return;
			$equipe = self::GetDadosChave($dados, array('equipe','equipe'));
			if(empty($equipe))
				return;
			$filtro = "equipe = '{$equipe}'";
			$obj = $this->FiltroObjeto($filtro);
			if(empty($obj))
			{
				$obj = $this->GetInstancia();
				$obj->equipe = $equipe;
			}
			$obj->Ajustar(true);
			$obj->Salvar();
			return;
		}
		################################################################################################################
		public function ExportarEquipe()
		{
			$posicao = Componente::Request("posicao", 0);
			$total = Componente::Request("total", 0);
			$file = Componente::Request("file", "");
			$limite = 500;
			$filtro = $this->Filtro(true);
			$obj = Componente::GetInstancia('equipe');			
			if(empty($total))
			{
				$sqlTotal = $this->GetSqlTotalLista();
				$obj = $this->TotalRegistro($filtro, $sqlTotal, true);
				if(empty($total))
				{
					$dados['sucesso'] = false;
					$dados['erro'] = __("Nenhum equipe foi encontrado no momento.", SIUP_LANG);
				}
				else
				{
					$dados['sucesso'] = true;
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de equipe está processando.", SIUP_LANG);
					$dados['url'] = "";
					$dados['finalizado'] = false;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				return $dados;
			}
			$filtro .= " ORDER BY idequipe ASC";
			$sql = $obj->GetSqlLista();
			$objs = $obj->FiltroObjetos($filtro, $sql);
			if($objs)
			{
				if(empty($file))
					$file = $obj->GetNomeFile();
				$data = array(
					"file"=> $file,
					"lista"=>$objs,
					"posicao"=>$posicao,
					"total"=>$total,
					"html"=>true,
					"campos"=>$obj->GetNomesCampos(),
					"download"=>false,
					"maiusculo"=>false,
					"pasta"=>$obj->GetCaminho()
				);
				Componente::Excel($data);
				$posicao += $limite;
				$dados['sucesso'] = true;
				if($posicao >= $total)
				{
					$dados['titulo'] = __("Exportação de equipe", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de equipe foi finalizada.<br/>O download se iniciará em instantes", SIUP_LANG);
					$dados['url'] = site_url("baixarequipe/{$file}");
					$dados['finalizado'] = true;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				else
				{
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de equipe está processando.", SIUP_LANG);
					$dados['url'] = "";
					$dados['finalizado'] = false;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
			}
			else
			{
				$dados['sucesso'] = false;
				$dados['erro'] = __("Nenhum equipe foi encontrado.");
			}
			return $dados;
		}
		################################################################################################################
		public function GetNomeFile()
		{
			$retorno = "exportacaoequipe_".date("Y-m-d_H-i-s").".xls";
			try
			{
				return $retorno;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function GetNomesCampos()
		{
			$campos = array("ID"=>"idequipe","equipe"=>"equipe");
			return $campos;
		}
		################################################################################################################
		public function GetCaminho($file = "")
		{
			return $this->SetDominio($file);
		}
		################################################################################################################
		public function __destruct()
		{
			unset($this->dados, $this->Tabela, $this->PrimaryKey);
		}
		#endregion FIM DA AREA PARA IMPLEMENTAÇÃO CORPO.
		#region AREA PARA IMPLEMENTAÇÃO ADICIONAIS.
		################################################################################################################
		public function GerarOpcoesIduser($value = "0", $texto = "", $default = "0")
		{
			if(empty($texto))
				$texto = __("-- Selecione --");
			$primeiro = array("valor"=>$default,"texto"=>$texto);
			$tabela = $this->GetTabela();
			$sql = "SELECT iduser AS 'id', user AS 'texto' FROM {$tabela} ORDER BY texto ASC";
			return Componente::GeraOpcoesSql($value, $sql, "id", "texto", $primeiro);
		}		################################################################################################################
		public function GerarOpcoesStatus($value = "Ativo", $texto = "", $default = "Ativo")
		{
			if(empty($texto))
				$texto = __("-- Selecione --");
			$tabela = $this->GetTabela();
			$primeiro = array("valor"=>$default,"texto"=>$texto);
			return $this->GeraOpcoesEnum($value, $tabela, "status", $primeiro);
		}




		#endregion FIM AREA PARA IMPLEMENTAÇÃO ADICIONAIS.
	}
}
?>