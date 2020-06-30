<?php
/***********************************************************************
 * Module:  /models/Evento_model.php
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	05/06/2020 15:16:38
 * Purpose: Definição da Classe Evento_model
 * Objeto:  $evento = Competencia::GetInstancia("evento");
 ***********************************************************************/
if (!class_exists('Evento_model'))
{
	class Evento_model extends Meumodelo
	{
		#region AREA PARA IMPLEMENTAÇÃO CABEÇARIO.
		################################################################################################################
		public function __construct( $dados = false)
		{
			try
			{
				$this->Tabela = "evento";
				$this->PrimaryKey = "idevento";
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

				
				if(Componente::emptyData($this->datainicio))
					$this->datainicio = date ("Y-m-d H:i:s");
				else
					$this->datainicio = date ("Y-m-d H:i:s",Componente::TimeData($this->datainicio));


				if(Componente::emptyData($this->datafim))
					$this->datafim = date ("Y-m-d H:i:s");
				else
					$this->datafim = date ("Y-m-d H:i:s",Componente::TimeData($this->datafim));


				if(Componente::emptyData($this->ip))
					$this->ip = Componente::GetUserIP();

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
			
			$idevento = Componente::GetFiltro("idevento");
			if(!empty($idevento))
			{
				$filtro .= " AND idevento = '{$idevento}'";
			}
			$iduser = Componente::GetFiltro("iduser");
			if(!empty($iduser))
			{
				$filtro .= " AND iduser = '{$iduser}'";
			}
			$titulo = Componente::GetFiltro("titulo");
			if(!empty($titulo))
			{
				$filtro .= " AND titulo = '{$titulo}'";
			}
			$resumo = Componente::GetFiltro("resumo");
			if(!empty($resumo))
			{
				$filtro .= " AND resumo = '{$resumo}'";
			}
			$descricao = Componente::GetFiltro("descricao");
			if(!empty($descricao))
			{
				$filtro .= " AND descricao = '{$descricao}'";
			}
			$datainicio = Componente::GetFiltro("datainicio");
			if(!empty($datainicio))
			{
				$filtro .= " AND datainicio = '{$datainicio}'";
			}
			$datafim = Componente::GetFiltro("datafim");
			if(!empty($datafim))
			{
				$filtro .= " AND datafim = '{$datafim}'";
			}
			$imagem = Componente::GetFiltro("imagem");
			if(!empty($imagem))
			{
				$filtro .= " AND imagem = '{$imagem}'";
			}
			$thumbnail = Componente::GetFiltro("thumbnail");
			if(!empty($thumbnail))
			{
				$filtro .= " AND thumbnail = '{$thumbnail}'";
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
			$ordem = array('idevento', 'iduser', 'titulo', 'resumo', 'descricao', 'datainicio', 'datafim', 'imagem', 'thumbnail', 'status', 'ip', 'cadastradoem', 'idevento');
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
			$obj = Componente::GetInstancia("evento");
			if(empty($obj))
				return $retorno;
			foreach ($lista as $key=>$linha)
			{
				$obj->SalvarListaEvento($linha);
			}
			return true;
		}
		################################################################################################################
		public function SalvarListaEvento($dados = false)
		{
			if(empty($dados))
				return;
			$evento = self::GetDadosChave($dados, array('evento','evento'));
			if(empty($evento))
				return;
			$filtro = "evento = '{$evento}'";
			$obj = $this->FiltroObjeto($filtro);
			if(empty($obj))
			{
				$obj = $this->GetInstancia();
				$obj->evento = $evento;
			}
			$obj->Ajustar(true);
			$obj->Salvar();
			return;
		}
		################################################################################################################
		public function ExportarEvento()
		{
			$posicao = Componente::Request("posicao", 0);
			$total = Componente::Request("total", 0);
			$file = Componente::Request("file", "");
			$limite = 500;
			$filtro = $this->Filtro(true);
			$obj = Componente::GetInstancia('evento');			
			if(empty($total))
			{
				$sqlTotal = $this->GetSqlTotalLista();
				$obj = $this->TotalRegistro($filtro, $sqlTotal, true);
				if(empty($total))
				{
					$dados['sucesso'] = false;
					$dados['erro'] = __("Nenhum evento foi encontrado no momento.", SIUP_LANG);
				}
				else
				{
					$dados['sucesso'] = true;
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de evento está processando.", SIUP_LANG);
					$dados['url'] = "";
					$dados['finalizado'] = false;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				return $dados;
			}
			$filtro .= " ORDER BY idevento ASC";
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
					$dados['titulo'] = __("Exportação de evento", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de evento foi finalizada.<br/>O download se iniciará em instantes", SIUP_LANG);
					$dados['url'] = site_url("baixarevento/{$file}");
					$dados['finalizado'] = true;
					$dados['file'] = $file;
					$dados['posicao'] = $posicao;
					$dados['total'] = $total;
				}
				else
				{
					$dados['titulo'] = __("Processando Exportação", SIUP_LANG);
					$dados['mensagem'] = __("Exportação de evento está processando.", SIUP_LANG);
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
				$dados['erro'] = __("Nenhum evento foi encontrado.");
			}
			return $dados;
		}
		################################################################################################################
		public function GetNomeFile()
		{
			$retorno = "exportacaoevento_".date("Y-m-d_H-i-s").".xls";
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
			$campos = array("ID"=>"idevento","evento"=>"evento");
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
		public function GerarOpcoesIduser($value = "", $texto = "", $default = "")
		{
			if(empty($texto))
				$texto = __("-- Selecione --");
			$primeiro = array("valor"=>$default,"texto"=>$texto);
			$tabela = $this->GetTabela();
			$sql = "SELECT iduser AS 'id', user AS 'texto' FROM {$tabela} ORDER BY texto ASC";
			return Componente::GeraOpcoesSql($value, $sql, "id", "texto", $primeiro);
		}		################################################################################################################
		public function GerarOpcoesStatus($value = "Aguardando Publicação", $texto = "", $default = "Aguardando Publicação")
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