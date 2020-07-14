<?php
/***********************************************************************
 * Module:  /models/Midiassocial_model.php
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	10/06/2020 16:28:53
 * Purpose: Definição da Classe Midiassocial_model
 * Objeto:  $midiassocial = Competencia::GetInstancia("midiassocial");
 ***********************************************************************/
if (!class_exists('Midiassocial_model'))
{
	class Midiassocial_model extends Meumodelo
	{
		#region AREA PARA IMPLEMENTAÇÃO CABEÇARIO.
		################################################################################################################
		public function __construct( $dados = false)
		{
			try
			{
				$this->Tabela = "midiassocial";
				$this->PrimaryKey = "idmidiassocial";
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
			}
			else
			{
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
			
			$idmidiassocial = Componente::GetFiltro("idmidiassocial");
			if(!empty($idmidiassocial))
			{
				$filtro .= " AND idmidiassocial = '{$idmidiassocial}'";
			}
			$iduser = Componente::GetFiltro("iduser");
			if(!empty($iduser))
			{
				$filtro .= " AND iduser = '{$iduser}'";
			}
			$nome = Componente::GetFiltro("nome");
			if(!empty($nome))
			{
				$filtro .= " AND nome = '{$nome}'";
			}
			$imagem = Componente::GetFiltro("imagem");
			if(!empty($imagem))
			{
				$filtro .= " AND imagem = '{$imagem}'";
			}
			$icone = Componente::GetFiltro("icone");
			if(!empty($icone))
			{
				$filtro .= " AND icone = '{$icone}'";
			}
			$link = Componente::GetFiltro("link");
			if(!empty($link))
			{
				$filtro .= " AND link = '{$link}'";
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
			$ordem = array('idmidiassocial', 'iduser', 'nome', 'imagem', 'icone', 'link', 'status', 'ip', 'cadastradoem', 'idmidiassocial');
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
			$obj = Componente::GetInstancia("midiassocial");
			if(empty($obj))
				return $retorno;
			foreach ($lista as $key=>$linha)
			{
				$obj->SalvarListaMidiassocial($linha);
			}
			return true;
		}
		################################################################################################################
		public function SalvarListaMidiassocial($dados = false)
		{
			if(empty($dados))
				return;
			$midiassocial = self::GetDadosChave($dados, array('midiassocial','midiassocial'));
			if(empty($midiassocial))
				return;
			$filtro = "midiassocial = '{$midiassocial}'";
			$obj = $this->FiltroObjeto($filtro);
			if(empty($obj))
			{
				$obj = $this->GetInstancia();
				$obj->midiassocial = $midiassocial;
			}
			$obj->Ajustar(true);
			$obj->Salvar();
			return;
		}
		################################################################################################################
		public function ExportarMidiassocial()
		{
			$posicao = Componente::Request("posicao", 0);
			$total = Componente::Request("total", 0);
			$file = Componente::Request("file", "");
			$limite = 500;
			$filtro = $this->Filtro(true);
			$obj = Componente::GetInstancia('midiassocial');			
			if(empty($total))
			{
				$sqlTotal = $this->GetSqlTotalLista();
				$obj = $this->TotalRegistro($filtro, $sqlTotal, true);
				if(empty($total))
				{
					$dados['sucesso'] = false;
					$dados['erro'] = __("Nenhum midiassocial foi encontrado no momento.", SIUP_LANG);
				}
				else
				{
					$dados['sucesso'] = true;
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de midiassocial está processando.", SIUP_LANG);
					$dados['url'] = "";
					$dados['finalizado'] = false;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				return $dados;
			}
			$filtro .= " ORDER BY idmidiassocial ASC";
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
					$dados['titulo'] = __("Exportação de midiassocial", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de midiassocial foi finalizada.<br/>O download se iniciará em instantes", SIUP_LANG);
					$dados['url'] = site_url("baixarmidiassocial/{$file}");
					$dados['finalizado'] = true;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				else
				{
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de midiassocial está processando.", SIUP_LANG);
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
				$dados['erro'] = __("Nenhum midiassocial foi encontrado.");
			}
			return $dados;
		}
		################################################################################################################
		public function GetNomeFile()
		{
			$retorno = "exportacaomidiassocial_".date("Y-m-d_H-i-s").".xls";
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
			$campos = array("ID"=>"idmidiassocial","midiassocial"=>"midiassocial");
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