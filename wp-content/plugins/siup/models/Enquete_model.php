<?php
/***********************************************************************
 * Module:  /models/Enquete_model.php
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	27/05/2020 20:28:35
 * Purpose: Definição da Classe Enquete_model
 * Objeto:  $enquete = Competencia::GetInstancia("enquete");
 ***********************************************************************/
if (!class_exists('Enquete_model'))
{
	class Enquete_model extends Meumodelo
	{
		private static $listaUser = false;
		#region AREA PARA IMPLEMENTAÇÃO CABEÇARIO.
		################################################################################################################
		public function __construct( $dados = false)
		{
			try
			{
				$this->Tabela = "enquete";
				$this->PrimaryKey = "idenquete";
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
				
				if(Componente::emptyData($this->cadastradoem))
					$this->cadastradoem = date ("Y-m-d H:i:s");
				else
					$this->cadastradoem = date ("Y-m-d H:i:s",Componente::TimeData($this->cadastradoem));
				if(empty($this->ip))
					$this->ip = Componente::GetUserIP();
				if(Componente::emptyData($this->datainicio))
					$this->datainicio = "";
				else
					$this->datainicio = date ("Y-m-d H:i:s", Componente::TimeData($this->datainicio));
				if(Componente::emptyData($this->datafim))
					$this->datafim = "";
				else
					$this->datafim = date ("Y-m-d H:i:s", Componente::TimeData($this->datafim));
				if(empty($this->iduser))
					$this->iduser = get_current_user_id();
			}
			else
			{
				if(Componente::emptyData($this->datainicio))
					$this->datainicio = "";
				else
					$this->datainicio = date ("Y-m-d\TH:i:s", Componente::TimeData($this->datainicio));
				if(Componente::emptyData($this->datafim))
					$this->datafim = "";
				else
					$this->datafim = date ("Y-m-d\TH:i:s", Componente::TimeData($this->datafim));
				if(Componente::emptyData($this->cadastradoem))
					$this->cadastradoem = "";
				else
					$this->cadastradoem = date ("d/m/Y H:i:s", Componente::TimeData($this->cadastradoem));
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
				$prefix = Componente::GetPrefix();
				return "SELECT * FROM {$prefix}enquete";
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
				return "SELECT COUNT(*) AS CONT FROM {$tabela}";
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
				$filtro .= " AND (pergunta LIKE '%{$buscar}%' OR titulo LIKE '%{$buscar}%')";
			}
			$status = Componente::GetFiltro("status");
			if(!empty($status))
			{
				$filtro .= " AND status = '{$status}'";
			}
			$iduser = Componente::GetFiltro("iduser");
			if(!empty($iduser))
			{
				$filtro .= " AND iduser = '{$iduser}'";
			}
			$datainicioinicio = Componente::GetFiltro("datainicioinicio");
			if(!Componente::emptyData($datainicioinicio))
			{
				$aux = date("Y-m-d 00:00:00", Componente::TimeData($datainicioinicio));
				$filtro .= " AND datainicio >= '{$aux}'";
			}
			$datainiciofim = Componente::GetFiltro("datainiciofim");
			if(!Componente::emptyData($datainiciofim))
			{
				$aux = date("Y-m-d 23:59:59", Componente::TimeData($datainiciofim));
				$filtro .= " AND datainicio <= '{$aux}'";
			}
			$datafiminicio = Componente::GetFiltro("datafiminicio");
			if(!Componente::emptyData($datafiminicio))
			{
				$aux = date("Y-m-d 00:00:00", Componente::TimeData($datafiminicio));
				$filtro .= " AND datafim >= '{$aux}'";
			}
			$datafimfim = Componente::GetFiltro("datafimfim");
			if(!Componente::emptyData($datafimfim))
			{
				$aux = date("Y-m-d 23:59:59", Componente::TimeData($datafimfim));
				$filtro .= " AND datafim <= '{$aux}'";
			}
			$cadastradoeminicio = Componente::GetFiltro("cadastradoeminicio");
			if(!Componente::emptyData($cadastradoeminicio))
			{
				$aux = date("Y-m-d 00:00:00", Componente::TimeData($cadastradoeminicio));
				$filtro .= " AND cadastradoem >= '{$aux}'";
			}
			$cadastradoemfim = Componente::GetFiltro("cadastradoemfim");
			if(!Componente::emptyData($cadastradoemfim))
			{
				$aux = date("Y-m-d 23:59:59", Componente::TimeData($cadastradoemfim));
				$filtro .= " AND cadastradoem <='{$aux}'";
			}
			if(!empty($filtro))
			{
				$filtro  = substr($filtro, 4);
			}
			if($semOrder)
			{
				return $filtro;
			}
			$ordem = array('idenquete', 'titulo', 'imagem', 'status', 'datainicio', 'datafim', 'cadastradoem', 'idenquete');
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
			$obj = Componente::GetInstancia("enquete");
			if(empty($obj))
				return $retorno;
			foreach ($lista as $key=>$linha)
			{
				$obj->SalvarListaEnquete($linha);
			}
			return true;
		}
		################################################################################################################
		public function SalvarListaEnquete($dados = false)
		{
			if(empty($dados))
				return;
			$enquete = self::GetDadosChave($dados, array('enquete','enquete'));
			if(empty($enquete))
				return;
			$filtro = "enquete = '{$enquete}'";
			$obj = $this->FiltroObjeto($filtro);
			if(empty($obj))
			{
				$obj = $this->GetInstancia();
				$obj->enquete = $enquete;
			}
			$obj->Ajustar(true);
			$obj->Salvar();
			return;
		}
		################################################################################################################
		public function ExportarEnquete()
		{
			$posicao = Componente::Request("posicao", 0);
			$total = Componente::Request("total", 0);
			$file = Componente::Request("file", "");
			$limite = 500;
			$filtro = $this->Filtro(true);
			$obj = Componente::GetInstancia('enquete');			
			if(empty($total))
			{
				$sqlTotal = $this->GetSqlTotalLista();
				$obj = $this->TotalRegistro($filtro, $sqlTotal, true);
				if(empty($total))
				{
					$dados['sucesso'] = false;
					$dados['erro'] = __("Nenhum enquete foi encontrado no momento.", SIUP_LANG);
				}
				else
				{
					$dados['sucesso'] = true;
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de enquete está processando.", SIUP_LANG);
					$dados['url'] = "";
					$dados['finalizado'] = false;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				return $dados;
			}
			$filtro .= " ORDER BY idenquete ASC";
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
					$dados['titulo'] = __("Exportação de enquete", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de enquete foi finalizada.<br/>O download se iniciará em instantes", SIUP_LANG);
					$dados['url'] = site_url("baixarenquete/{$file}");
					$dados['finalizado'] = true;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				else
				{
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de enquete está processando.", SIUP_LANG);
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
				$dados['erro'] = __("Nenhum enquete foi encontrado.");
			}
			return $dados;
		}
		################################################################################################################
		public function GetNomeFile()
		{
			$retorno = "exportacaoenquete_".date("Y-m-d_H-i-s").".xls";
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
			$campos = array("ID"=>"idenquete","enquete"=>"enquete");
			return $campos;
		}
		################################################################################################################
		public function GetCaminho($file = "")
		{
			return $this->SetDominio($file);
		}
		################################################################################################################
		public function &GetJson(&$dados = false)
		{
			$retorno = false;
			try
			{
				if(empty($dados))
				{
					$dados = $this->GetDados();
				}
				$dados['usuario'] = self::GetListaUser($dados['iduser']);
				if(Componente::emptyData($dados['datainicio']))
					$dados['datainicio'] = "";
				else
					$dados['datainicio'] = date ("d/m/Y H:i", Componente::TimeData($dados['datainicio']));
				if(Componente::emptyData($dados['datafim']))
					$dados['datafim'] = "";
				else
					$dados['datafim'] = date ("d/m/Y H:i", Componente::TimeData($dados['datafim']));
				if(Componente::emptyData($dados['cadastradoem']))
					$dados['cadastradoem'] = "";
				else
					$dados['cadastradoem'] = date ("d/m/Y H:i", Componente::TimeData($dados['cadastradoem']));
				return $dados;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function __destruct()
		{
			unset($this->dados, $this->Tabela, $this->PrimaryKey);
		}
		#endregion FIM DA AREA PARA IMPLEMENTAÇÃO CORPO.
		#region AREA PARA IMPLEMENTAÇÃO ADICIONAIS.
		################################################################################################################
		public static function GetListaUser($iduser = 0)
		{
			$retorno = "";
			try
			{
				if(empty($iduser))
					return $retorno;
				if(empty(self::$listaUser[$iduser]))
				{
					$prefix = Componente::GetPrefix();
					$sql = "SELECT display_name AS nome FROM {$prefix}users WHERE ID = '{$iduser}'";
					self::$listaUser[$iduser] = self::GetSqlCampo($sql, "nome", "");
				}
				return self::$listaUser[$iduser];
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function GerarOpcoesUser($value = "0", $texto = "", $default = "0")
		{
			if(empty($texto))
				$texto = __("-- Selecione --");
			$primeiro = array("valor"=>$default,"texto"=>$texto);
			$tabela = $this->GetPrefix()."users";
			$sql = "SELECT ID AS 'id', display_name AS 'texto' FROM {$tabela} ORDER BY texto ASC";
			return Componente::GeraOpcoesSql($value, $sql, "id", "texto", $primeiro);
		}
		################################################################################################################
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