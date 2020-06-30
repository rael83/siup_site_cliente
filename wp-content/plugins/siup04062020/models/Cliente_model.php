<?php
/***********************************************************************
 * Module:  /models/Cliente_model.php
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	27/05/2020 18:03:16
 * Purpose: Definição da Classe Cliente_model
 * Objeto:  $cliente = Competencia::GetInstancia("cliente");
 ***********************************************************************/
if (!class_exists('Cliente_model'))
{
	class Cliente_model extends Meumodelo
	{
		#region AREA PARA IMPLEMENTAÇÃO CABEÇARIO.
		################################################################################################################
		public function __construct( $dados = false)
		{
			try
			{
				$this->Tabela = "cliente";
				$this->PrimaryKey = "idcliente";
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
				$prefix = Componente::GetPrefix();
				return "SELECT * FROM {$prefix}cliente ";
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
				return "SELECT COUNT(*) AS CONT FROM {$prefix}cliente";
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
			
			$idsiup = Componente::GetFiltro("idsiup");
			if(!empty($idsiup))
			{
				$filtro .= " AND idsiup = '{$idsiup}'";
			}
			$mae = Componente::GetFiltro("mae");
			if(!empty($mae))
			{
				$filtro .= " AND mae LIKE '%{$mae}%'";
			}
			$email = Componente::GetFiltro("email");
			if(!empty($email))
			{
				$filtro .= " AND email = '{$email}'";
			}
			$senha = Componente::GetFiltro("senha");
			if(!empty($senha))
			{
				$filtro .= " AND senha = '{$senha}'";
			}
			$datanascimentoinicio = Componente::GetFiltro("datanascimentoinicio");
			$datanascimentofim = Componente::GetFiltro("datanascimentofim");
			if((!empty($datanascimentoinicio))&&(!empty($datanascimentofim)))
			{
				$filtro .= " AND datanascimento BETWEEN '{$datanascimentoinicio}' AND '{$datanascimentofim}' ";
			}
			$telefone = Componente::GetFiltro("telefone");
			if(!empty($telefone))
			{
				$filtro .= " AND telefone = '{$telefone}'";
			}
			if(!empty($filtro))
			{
				$filtro  = substr($filtro, 4);
			}
			if($semOrder)
			{
				return $filtro;
			}
			$ordem = array('idcliente', 'idsiup', 'nome', 'mae', 'email', 'senha', 'datanascimento', 'telefone', 'idcliente');
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
			$obj = Componente::GetInstancia("cliente");
			if(empty($obj))
				return $retorno;
			foreach ($lista as $key=>$linha)
			{
				$obj->SalvarListaCliente($linha);
			}
			return true;
		}
		################################################################################################################
		public function SalvarListaCliente($dados = false)
		{
			if(empty($dados))
				return;
			$cliente = self::GetDadosChave($dados, array('cliente','cliente'));
			if(empty($cliente))
				return;
			$filtro = "cliente = '{$cliente}'";
			$obj = $this->FiltroObjeto($filtro);
			if(empty($obj))
			{
				$obj = $this->GetInstancia();
				$obj->cliente = $cliente;
			}
			$obj->Ajustar(true);
			$obj->Salvar();
			return;
		}
		################################################################################################################
		public function ExportarCliente()
		{
			$posicao = Componente::Request("posicao", 0);
			$total = Componente::Request("total", 0);
			$file = Componente::Request("file", "");
			$limite = 500;
			$filtro = $this->Filtro(true);
			$obj = Componente::GetInstancia('cliente');			
			if(empty($total))
			{
				$sqlTotal = $this->GetSqlTotalLista();
				$obj = $this->TotalRegistro($filtro, $sqlTotal, true);
				if(empty($total))
				{
					$dados['sucesso'] = false;
					$dados['erro'] = __("Nenhum cliente foi encontrado no momento.", SIUP_LANG);
				}
				else
				{
					$dados['sucesso'] = true;
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de cliente está processando.", SIUP_LANG);
					$dados['url'] = "";
					$dados['finalizado'] = false;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				return $dados;
			}
			$filtro .= " ORDER BY idcliente ASC";
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
					$dados['titulo'] = __("Exportação de cliente", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de cliente foi finalizada.<br/>O download se iniciará em instantes", SIUP_LANG);
					$dados['url'] = site_url("baixarcliente/{$file}");
					$dados['finalizado'] = true;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				else
				{
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de cliente está processando.", SIUP_LANG);
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
				$dados['erro'] = __("Nenhum cliente foi encontrado.");
			}
			return $dados;
		}
		################################################################################################################
		public function GetNomeFile()
		{
			$retorno = "exportacaocliente_".date("Y-m-d_H-i-s").".xls";
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
			$campos = array("ID"=>"idcliente","cliente"=>"cliente");
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
		public function GerarOpcoesIdsiup($value = "0", $texto = "", $default = "0")
		{
			if(empty($texto))
				$texto = __("-- Selecione --");
			$primeiro = array("valor"=>$default,"texto"=>$texto);
			$tabela = $this->GetTabela();
			$sql = "SELECT idsiup AS 'id', siup AS 'texto' FROM {$tabela} ORDER BY texto ASC";
			return Componente::GeraOpcoesSql($value, $sql, "id", "texto", $primeiro);
		}




		#endregion FIM AREA PARA IMPLEMENTAÇÃO ADICIONAIS.
	}
}
?>