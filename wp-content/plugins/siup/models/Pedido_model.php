<?php
/***********************************************************************
 * Module:  /models/Pedido_model.php
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	15/06/2020 02:58:38
 * Purpose: Definição da Classe Pedido_model
 * Objeto:  $pedido = Competencia::GetInstancia("pedido");
 ***********************************************************************/
if (!class_exists('Pedido_model'))
{
	class Pedido_model extends Meumodelo
	{
		#region AREA PARA IMPLEMENTAÇÃO CABEÇARIO.
		################################################################################################################
		public function __construct( $dados = false)
		{
			try
			{
				$this->Tabela = "pedido";
				$this->PrimaryKey = "idpedido";
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
				if(Componente::emptyData($this->datapedido))
					$this->datapedido = date ("Y-m-d H:i:s");
				else
					$this->datapedido = date ("Y-m-d H:i:s",Componente::TimeData($this->datapedido));
				if(empty($this->ip))
					$this->ip = Componente::GetUserIP();
			}
			else
			{
				if(Componente::emptyData($this->datapedido))
					$this->datapedido = "";
				else
					$this->datapedido = date ("d/m/Y H:i:s", Componente::TimeData($this->datapedido));
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
			
			$idpedido = Componente::GetFiltro("idpedido");
			if(!empty($idpedido))
			{
				$filtro .= " AND idpedido = '{$idpedido}'";
			}
			$idtarefa = Componente::GetFiltro("idtarefa");
			if(!empty($idtarefa))
			{
				$filtro .= " AND idtarefa = '{$idtarefa}'";
			}
			$idcliente = Componente::GetFiltro("idcliente");
			if(!empty($idcliente))
			{
				$filtro .= " AND idcliente = '{$idcliente}'";
			}
			$idarea = Componente::GetFiltro("idarea");
			if(!empty($idarea))
			{
				$filtro .= " AND idarea = '{$idarea}'";
			}
			$descricao = Componente::GetFiltro("descricao");
			if(!empty($descricao))
			{
				$filtro .= " AND descricao = '{$descricao}'";
			}
			$status = Componente::GetFiltro("status");
			if(!empty($status))
			{
				$filtro .= " AND status = '{$status}'";
			}
			$ip = Componente::GetFiltro("ip");
			if(!empty($ip))
			{
				$filtro .= " AND ip = '{$ip}'";
			}
			$datapedido = Componente::GetFiltro("datapedido");
			if(!empty($datapedido))
			{
				$filtro .= " AND datapedido = '{$datapedido}'";
			}
			if(!empty($filtro))
			{
				$filtro  = substr($filtro, 4);
			}
			if($semOrder)
			{
				return $filtro;
			}
			$ordem = array('idpedido', 'idtarefa', 'idcliente', 'idarea', 'descricao', 'status', 'ip', 'datapedido', 'idpedido');
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
			$obj = Componente::GetInstancia("pedido");
			if(empty($obj))
				return $retorno;
			foreach ($lista as $key=>$linha)
			{
				$obj->SalvarListaPedido($linha);
			}
			return true;
		}
		################################################################################################################
		public function SalvarListaPedido($dados = false)
		{
			if(empty($dados))
				return;
			$pedido = self::GetDadosChave($dados, array('pedido','pedido'));
			if(empty($pedido))
				return;
			$filtro = "pedido = '{$pedido}'";
			$obj = $this->FiltroObjeto($filtro);
			if(empty($obj))
			{
				$obj = $this->GetInstancia();
				$obj->pedido = $pedido;
			}
			$obj->Ajustar(true);
			$obj->Salvar();
			return;
		}
		################################################################################################################
		public function ExportarPedido()
		{
			$posicao = Componente::Request("posicao", 0);
			$total = Componente::Request("total", 0);
			$file = Componente::Request("file", "");
			$limite = 500;
			$filtro = $this->Filtro(true);
			$obj = Componente::GetInstancia('pedido');			
			if(empty($total))
			{
				$sqlTotal = $this->GetSqlTotalLista();
				$obj = $this->TotalRegistro($filtro, $sqlTotal, true);
				if(empty($total))
				{
					$dados['sucesso'] = false;
					$dados['erro'] = __("Nenhum pedido foi encontrado no momento.", SIUP_LANG);
				}
				else
				{
					$dados['sucesso'] = true;
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de pedido está processando.", SIUP_LANG);
					$dados['url'] = "";
					$dados['finalizado'] = false;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				return $dados;
			}
			$filtro .= " ORDER BY idpedido ASC";
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
					$dados['titulo'] = __("Exportação de pedido", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de pedido foi finalizada.<br/>O download se iniciará em instantes", SIUP_LANG);
					$dados['url'] = site_url("baixarpedido/{$file}");
					$dados['finalizado'] = true;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				else
				{
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de pedido está processando.", SIUP_LANG);
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
				$dados['erro'] = __("Nenhum pedido foi encontrado.");
			}
			return $dados;
		}
		################################################################################################################
		public function GetNomeFile()
		{
			$retorno = "exportacaopedido_".date("Y-m-d_H-i-s").".xls";
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
			$campos = array("ID"=>"idpedido","pedido"=>"pedido");
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
		public function GerarOpcoesIdtarefa($value = "0", $texto = "", $default = "0")
		{
			if(empty($texto))
				$texto = __("-- Selecione --");
			$primeiro = array("valor"=>$default,"texto"=>$texto);
			$tabela = $this->GetTabela();
			$sql = "SELECT idtarefa AS 'id', tarefa AS 'texto' FROM {$tabela} ORDER BY texto ASC";
			return Componente::GeraOpcoesSql($value, $sql, "id", "texto", $primeiro);
		}		################################################################################################################
		public function GerarOpcoesIdcliente($value = "0", $texto = "", $default = "0")
		{
			if(empty($texto))
				$texto = __("-- Selecione --");
			$primeiro = array("valor"=>$default,"texto"=>$texto);
			$tabela = $this->GetTabela();
			$sql = "SELECT idcliente AS 'id', nome AS 'texto' FROM wp_cliente ORDER BY texto ASC";
			return Componente::GeraOpcoesSql($value, $sql, "id", "texto", $primeiro);
		}		################################################################################################################
		public function GerarOpcoesIdarea($value = "0", $texto = "", $default = "0")
		{
			if(empty($texto))
				$texto = __("-- Selecione --");
			$primeiro = array("valor"=>$default,"texto"=>$texto);
			$tabela = $this->GetTabela();
			//$sql = "SELECT idpai AS 'id', pai AS 'texto' FROM {$tabela} ORDER BY texto ASC";
			$sql = "SELECT idarea as id, nome as texto FROM wp_area ORDER BY nome ASC";
			return Componente::GeraOpcoesSql($value, $sql, "id", "texto", $primeiro);
		}		
		################################################################################################################
		public function GerarOpcoesStatus($value = "Aguardando Atendimento", $texto = "", $default = "Aguardando Atendimento")
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