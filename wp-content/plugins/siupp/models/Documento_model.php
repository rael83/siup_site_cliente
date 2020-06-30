<?php
/***********************************************************************
 * Module:  /models/Documento_model.php
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	12/05/2020 20:11:00
 * Purpose: Definição da Classe Documento_model
 * Objeto:  $documento = Competencia::GetInstancia("documento");
 ***********************************************************************/
if (!class_exists('Documento_model'))
{
	class Documento_model extends Meumodelo
	{
		#region AREA PARA IMPLEMENTAÇÃO CABEÇARIO.
		################################################################################################################
		public function __construct( $dados = false)
		{
			try
			{
				$this->Tabela = "documento";
				$this->PrimaryKey = "iddocumento";
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
				$filtro .= " AND buscar LIKE '%{$buscar}%'";
			}
			
			$iddocumento = Componente::GetFiltro("iddocumento");
			if(!empty($iddocumento))
			{
				$filtro .= " AND iddocumento = '{$iddocumento}'";
			}
			$idpedido = Componente::GetFiltro("idpedido");
			if(!empty($idpedido))
			{
				$filtro .= " AND idpedido = '{$idpedido}'";
			}
			$documento = Componente::GetFiltro("documento");
			if(!empty($documento))
			{
				$filtro .= " AND documento = '{$documento}'";
			}
			$data = Componente::GetFiltro("data");
			if(!empty($data))
			{
				$filtro .= " AND data = '{$data}'";
			}
			if(!empty($filtro))
			{
				$filtro  = substr($filtro, 4);
			}
			if($semOrder)
			{
				return $filtro;
			}
			$ordem = array('iddocumento', 'idpedido', 'documento', 'data', 'iddocumento');
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
			$obj = Componente::GetInstancia("documento");
			if(empty($obj))
				return $retorno;
			foreach ($lista as $key=>$linha)
			{
				$obj->SalvarListaDocumento($linha);
			}
			return true;
		}
		################################################################################################################
		public function SalvarListaDocumento($dados = false)
		{
			if(empty($dados))
				return;
			$documento = self::GetDadosChave($dados, array('documento','documento'));
			if(empty($documento))
				return;
			$filtro = "documento = '{$documento}'";
			$obj = $this->FiltroObjeto($filtro);
			if(empty($obj))
			{
				$obj = $this->GetInstancia();
				$obj->documento = $documento;
			}
			$obj->Ajustar(true);
			$obj->Salvar();
			return;
		}
		################################################################################################################
		public function ExportarDocumento()
		{
			$posicao = Get("posicao", 0);
			$total = Get("total", 0);
			$file = Get("file", "");
			$limite = 500;
			$filtro = $this->Filtro(true);
			$obj = Componente::GetInstancia('documento');			
			if(empty($total))
			{
				$sqlTotal = $this->GetSqlTotalLista();
				$obj = $this->TotalRegistro($filtro, $sqlTotal, true);
				if(empty($total))
				{
					$dados['sucesso'] = false;
					$dados['erro'] = __("Nenhum documento foi encontrado no momento.", SIUP_LANG);
				}
				else
				{
					$dados['sucesso'] = true;
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de documento está processando.", SIUP_LANG);
					$dados['url'] = "";
					$dados['finalizado'] = false;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				return $dados;
			}
			$filtro .= " ORDER BY iddocumento ASC";
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
					$dados['titulo'] = __("Exportação de documento", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de documento foi finalizada.<br/>O download se iniciará em instantes", SIUP_LANG);
					$dados['url'] = site_url("baixardocumento/{$file}");
					$dados['finalizado'] = true;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				else
				{
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de documento está processando.", SIUP_LANG);
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
				$dados['erro'] = __("Nenhum documento foi encontrado.");
			}
			return $dados;
		}
		################################################################################################################
		public function GetNomeFile()
		{
			$retorno = "exportacaodocumento_".date("Y-m-d_H-i-s").".xls";
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
			$campos = array("ID"=>"iddocumento","documento"=>"documento");
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
		public function GerarOpcoesIdpedido($value = "0", $texto = "", $default = "0")
		{
			if(empty($texto))
				$texto = __("-- Selecione --");
			$primeiro = array("valor"=>$default,"texto"=>$texto);
			$tabela = $this->GetTabela();
			$sql = "SELECT idpedido AS 'id', pedido AS 'texto' FROM {$tabela} ORDER BY texto ASC";
			return Componente::GeraOpcoesSql($value, $sql, "id", "texto", $primeiro);
		}



		################################################################################################################
		public static function URL()
		{
			return network_home_url()."/arq/documento/";
		}
		################################################################################################################
		public function CheckDocumento($arquivo = false , $caminho = "", $completo = true)
		{
			try
			{
				if(empty($arquivo))
				{
					if(empty($this->documento))
						return false;
					$arquivo = $this->documento;
				}
				if(!$completo)
				{
					if(empty($caminho))
					{
						$caminho = $this->Dominio();
					}
				}
				return $this->FileExiste( $arquivo, $caminho, $completo);
			}
			catch (Exception $e)
			{
				throw new Exception($e);
				return false;
			}
		}
		#endregion FIM AREA PARA IMPLEMENTAÇÃO ADICIONAIS.
	}
}
?>