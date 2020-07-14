<?php
/***********************************************************************
 * Module:  /models/Opcaoenquete_model.php
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	12/05/2020 18:21:29
 * Purpose: Definição da Classe Opcaoenquete_model
 * Objeto:  $opcaoenquete = Competencia::GetInstancia("opcaoenquete");
 ***********************************************************************/
if (!class_exists('Opcaoenquete_model'))
{
	class Opcaoenquete_model extends Meumodelo
	{
		#region AREA PARA IMPLEMENTAÇÃO CABEÇARIO.
		################################################################################################################
		public function __construct( $dados = false)
		{
			try
			{
				$this->Tabela = "opcaoenquete";
				$this->PrimaryKey = "idopcaoenquete";
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
				if(!empty($this->opcao))
				{
					$this->opcao = trim(strip_tags($this->opcao));
				}
			}
			else
			{

			}
		}
		#endregion FIM DA AREA PARA IMPLEMENTAÇÃO CABEÇARIO.
		#region AREA PARA IMPLEMENTAÇÃO CORPO.		
		################################################################################################################
		public function SalvarLista($idopcaoenquete = 0, $idenquete = 0, $posicao = 0, $opcao = "", $votos = 0)
		{
			$this->idopcaoenquete = $idopcaoenquete;
			$this->idenquete = $idenquete;
			$this->posicao = $posicao;
			$this->opcao = $opcao;
			$this->votos = $votos;

			$this->Ajustar(true);
			$this->Salvar();
			return;
		}		
		################################################################################################################
		public function __destruct()
		{
			unset($this->dados, $this->Tabela, $this->PrimaryKey);
		}
		#endregion FIM DA AREA PARA IMPLEMENTAÇÃO CORPO.
		#region AREA PARA IMPLEMENTAÇÃO ADICIONAIS.
		################################################################################################################
		public function GerarOpcoesIdenquete($value = "0", $texto = "", $default = "0")
		{
			if(empty($texto))
				$texto = __("-- Selecione --");
			$primeiro = array("valor"=>$default,"texto"=>$texto);
			$tabela = $this->GetTabela();
			$sql = "SELECT idenquete AS 'id', enquete AS 'texto' FROM {$tabela} ORDER BY texto ASC";
			return Componente::GeraOpcoesSql($value, $sql, "id", "texto", $primeiro);
		}
		################################################################################################################
		public function &SelectAllEnquete($pFiltro = "")
		{
			$ret = false;
			try
			{
				if(empty($this->idopcaoenquete))
					return $ret;
				$obj = Componente::GetInstancia("enquete");
				$filtro = " idopcaoenquete = '{$this->idopcaoenquete}' {$pFiltro}";
				return $obj->FiltroObjetos($filtro);
			}
			catch (Exception $e)
			{
				throw new Exception($e);
				return $ret;
			}
		}
		################################################################################################################
		public function CarregarOpcoes()
		{
			$obj = Componente::GetInstancia('opcaoenquete');
			$id = Componente::Request("id");
			$tabela = $obj->GetTabela();
			$sql = "SELECT * FROM {$tabela} WHERE idenquete = '{$id}' ORDER BY posicao ASC";
			$rows = $obj->FiltroJson(false, $sql);
			if(empty($rows))
			{
				Componente::SetErros(__("Nenhuma opção de enquete foi encontrada no momento.", HOST_LANG));
				Componente::PrintErros();
			}
			else
			{
				$informe['lista'] = $rows;
				Componente::PrintDados($informe);
			}
			return;
		}
		#endregion FIM AREA PARA IMPLEMENTAÇÃO ADICIONAIS.
	}
}
?>