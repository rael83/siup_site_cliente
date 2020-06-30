<?php
class Adm
{
	################################################################################################################
	function __construct()
	{
	}
	################################################################################################################
	public function Painel()
	{
		$lista = array("full"=>"full","content"=>"content");
        $data['opcaotela'] = Componente::GeraOpcoesArray("", $lista, "-- Selecione --");
        $tabela = Componente::GetPrefix()."posts";
        $prefix = Componente::GetPrefix();
        $sql = "SELECT  `ID`,  LEFT(`post_title`, 256) AS TITULO FROM {$tabela} WHERE post_type IN('post','page') AND post_status = 'publish'";
		$data['opcaoidpagina'] = Componente::GeraOpcoesSql("", $sql, "ID", "TITULO", "-- Selecione --");		
		
		Componente::GetViews("adm/painel", $data);
		return;
	}
}
?>