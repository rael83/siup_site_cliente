<?php

/**
 * *********************************************************************
 * Module:	Peca.PHP
 * Author:	CosmeWeb
 * Date:	13/08/2014 08:29:35
 * Purpose:	Definição da Classe Peca
 * *********************************************************************
 */
class OrdemPDF
{

	public static $x = 0;
	public static $y = 0;
	public static $ultimaAltura = 0;
	public static $PrintTitulo = true;
	// ################################### SALVAR PEçA ###############################
	public static function geraOrdemServico($filtro = false,  $sql = false, $file = false)
	{
		if( empty( $file ) )
			$file = "ordem_de_servico_" . date( "Y-m-d_H-m-s" );
		if( empty( $sql ) )
			$sql = Modelo::ProcSelPedidoFiltro();
		$obj = new Pedido();
		$objs = $obj->FiltroObjetos( $filtro, $sql );
		if( $objs )
		{
			$pdf = new RelatorioPDF();
			$pdf->RelatorioPDF( 'P', 'mm', 'a4' );
			$pdf->TemCabecario = false;
			$pdf->TamanhodaFonte = 9;
			$pdf->AliasNbPages();
			$pdf->Voltar = "";
			$pdf->SetLeftMargin( 0.4 );
			$pdf->SetRightMargin( 0.4 );
			$pdf->SetTopMargin( 0 );
			$pdf->SetAutoPageBreak( true, 1 );
			$pdf->SetFont( 'Arial', '', 9 );
			
			foreach( $objs as $obj )
			{
				self::MontarOrdem( $obj, $pdf );
			}
			$pdf->Output();
		}
	}
	// ################################### SALVAR PEçA ###############################
	public static function MontarOrdem( &$obj = false, &$pdf = false)
	{
		#$obj = new Ordem(); $pdf = new HtmlPDF();
		$pdf->AddPage();
		self::$x = $pdf->GetX();
		self::$y = $pdf->GetY();
		self::Imagem( $obj, $pdf );
		$X = 50;
		$pdf->SetXY( $X, self::$y - 5 );
		$pdf->SetTextColor(10,10,10);
		$pdf->SetFont( 'Arial', 'B', 20 );
		$aux = str_pad($obj->Idpedido, 6, "0", STR_PAD_LEFT);
		$pdf->Cell(152,20, "ORDEM DE SERVIÇO N°: {$aux}",1,0,'C');
		$pdf->SetTextColor(10,10,10);
		$pdf->SetFont( 'Arial', '', 8);
		
		$pdf->SetXY( $X, self::$y + 15 );
		self::Linha( $pdf, "Cliente: ", $obj->GetNomeFornecedor(), 152, 8 );
		$aux = self::$y + self::$ultimaAltura;
		$X = 7;
		$pdf->SetXY( $X, $aux );
		self::Linha( $pdf, "Cadastrado por: ", $obj->GetNomeAdministrador(), 140, 8 );
		$pdf->SetXY( $X+140, $aux );
		self::Linha( $pdf, "Solicitado em: ", $obj->Data, 55, 8 );

		$aux = self::$y + self::$ultimaAltura;
		$pdf->SetXY( $X, $aux );
		self::Linha( $pdf, "Solicitador por: ", $obj->Solicitadopor, 140, 8 );
		$pdf->SetXY( $X+140, $aux );
		self::Linha( $pdf, "Desconto: ", $obj->GetDesconto(), 55, 8 );
		
		$aux = self::$y + self::$ultimaAltura;
		$pdf->SetXY( $X, $aux );
		self::Linha( $pdf, "Atendimento: ", $obj->Atendimento, 97.5, 8 );
		$pdf->SetXY( $X+97.5, $aux );		
		self::Linha( $pdf, "Status: ", $obj->GetNomeStatus(), 97.5, 8 );
		
		
		$pdf->SetXY( $X, self::$y + self::$ultimaAltura );
		$pdf->SetTextColor(10,10,10);
		$pdf->SetFillColor(247,247,247);
		$pdf->SetFont( 'Arial', 'B', 9 );
		$pdf->Cell(195,10, "SERVIÇOS SOLICITADOS",1,0,'C',1);
		$pdf->SetTextColor(10,10,10);
		$pdf->SetFont( 'Arial', '', 8);
		self::$y = $pdf->GetY();		
		$pdf->SetXY( $X, self::$y + 10);
		$total = self::ListaItem($obj, $pdf);
		
		$pdf->SetXY( $X, self::$y + self::$ultimaAltura );
		self::Caixa( $pdf, "Informações:", self::ListaTramite($obj->Idpedido, $pdf), 195 );

		$pdf->SetXY( $X, self::$y + self::$ultimaAltura );
		self::TextoCobranca($total, $pdf, $obj->Idpedido);

		$pdf->SetXY( $X, self::$y + self::$ultimaAltura);
		$texto = "( ) Confidencial   (X) Retrita  ( ) Publica";
		self::Caixa( $pdf, "Classificação da Informação:", $texto, 195 );
		
	}
	// ################################### SALVAR PEçA ###############################
	public static function Caixa( &$pdf = false, $titulo = "", $MSN = "", $lagura = 0, $altura = 0)
	{
		#$obj = new Peca(); $pdf = new HtmlPDF();
		$h = 8;
		if( $altura <= 0 )
		{
			$pdf->SetFont( 'Arial', '', $h );
			$altura = $pdf->GetTamanhoTexto( $lagura, $h, $MSN );
		}
		if($pdf->IsFimDePagina($altura))
		{
			$diferenca = $pdf->PageBreakTrigger-$pdf->y-45;
			$porcento = $diferenca/$altura;
			$diferenca = $pdf->PageBreakTrigger-$pdf->y-15;
			#Componente::P(' diferenca',$diferenca,'PageBreakTrigger',$pdf->PageBreakTrigger,'Y',$pdf->y,'porcento',$porcento,"altura",$altura);
			if($aux = self::LimitaTexto($MSN, $porcento))
			{
				#Componente::P('1',strlen( $aux[0] ),'2', strlen( $aux[1] ),'msn', strlen( $MSN ));
				if(self::$PrintTitulo)
				{
					self::ImprimirCaixa(self::$PrintTitulo,$pdf, $titulo, $aux[0], $lagura, $diferenca);
					self::$PrintTitulo = false;
					if(!empty($aux[1]))
					{
						$pdf->AddPage();
						$pdf->SetLeftMargin( 0.4 );
						$pdf->SetRightMargin( 0.4 );
						$pdf->SetTopMargin( 0 );
						$pdf->SetXY( 5, 5);
						self::Caixa($pdf, $titulo, $aux[1], $lagura, 0);
					}
				}
				else
				{
					self::ImprimirCaixa(self::$PrintTitulo,$pdf, $titulo, $aux[0], $lagura, $diferenca);
					if(!empty($aux[1]))
					{
						$pdf->AddPage();
						$pdf->SetLeftMargin( 0.4 );
						$pdf->SetRightMargin( 0.4 );
						$pdf->SetTopMargin( 0 );
						$pdf->SetXY( 5, 5);
						self::Caixa($pdf, $titulo, $aux[1], $lagura, 0);
					}
				}
			}
			else
			{
				self::ImprimirCaixa(self::$PrintTitulo,$pdf, $titulo, $MSN, $lagura, $altura);
				self::$PrintTitulo = true;
			}	
		}
		else 
		{
			self::ImprimirCaixa(self::$PrintTitulo,$pdf, $titulo, $MSN, $lagura, $altura);
			self::$PrintTitulo = true;
		}
	}
	// ################################### SALVAR PEçA ###############################
	public static function LimitaTexto($texto = "", $porcento = 0)
	{
		if(empty($texto))
			return false;
		$tamanho = strlen($texto);
		$num = intval (floor($tamanho*$porcento));
		
		$dividendo = substr($texto,0,$num);
		$resto = trim(substr($texto,$num));
		if(empty($resto))
			$num = 0;
		else 
			$num = strpos($resto," ");
		if($num > 0)
		{	
			$palavra = substr($resto,0,$num);
			$dividendo .= $palavra;
			$resto = trim(substr($resto,$num));
		}
		return array($dividendo,$resto);
	}
	// ################################### SALVAR PEçA ###############################
	public static function ImprimirCaixa($comoTitulo = true, &$pdf = false, $titulo = "", $MSN = "", $lagura = 0, $altura = 0)
	{
		// obj = new Peca(); $pdf = new HtmlPDF();
		self::$x = $pdf->GetX();
		self::$y = $pdf->GetY();
		$h = 8;
		$paddingx = 2;
		if( $altura <= 0 )
		{
			$altura = $pdf->GetTamanhoTexto( $lagura, $h, $MSN );
		}
		$pdf->Rect( self::$x, self::$y, $lagura, $altura + $h, 'D' );
		if($comoTitulo)
		{
			$pdf->SetFont( 'Arial', 'B', $h );
			$pdf->Text( self::$x + $paddingx, self::$y + 4, $titulo );
		}
		$pdf->SetFont( 'Arial', '', $h );
		$MSN = html_entity_decode( $MSN, ENT_HTML5, 'ISO-8859-1' );
		$MSN = strip_tags( $MSN );
		$pdf->SetXY( self::$x + $paddingx, self::$y + $h - 3 );
		$pdf->MultiCell( $lagura - $paddingx, $altura, $MSN );
		$altura += $h;
		
		self::$ultimaAltura = $altura;
		$pdf->SetXY( self::$x, self::$y );
	}
	// ################################### SALVAR PEçA ###############################
	public static function Linha( &$pdf = false, $titulo = "", $MSN = "", $lagura = 0, $altura = 0)
	{
		# $obj = new Peca(); $pdf = new HtmlPDF();
		self::$x = $pdf->GetX();
		self::$y = $pdf->GetY();
		$h = 8;
		$paddingx = 2;
		$width = $pdf->GetStringWidth($titulo)+1;
		$pdf->SetFont( 'Arial', 'B', $h );
		$pdf->Text( self::$x + $paddingx, self::$y + 4, $titulo );
		$pdf->SetFont( 'Arial', '', $h );
		$MSN = html_entity_decode( $MSN, ENT_HTML5, 'ISO-8859-1' );
		$MSN = strip_tags( $MSN );
		if( $altura <= 0 )
		{
			$altura = $pdf->GetTamanhoTexto( $lagura, $h, $MSN );
		}
		$NovaLagura = self::$x + $paddingx + $width;
		$pdf->SetXY( $NovaLagura, self::$y + 1);
		$NovaLagura =  $lagura - $paddingx - $width;
		$pdf->MultiCell( $NovaLagura, $altura, $MSN);
		$pdf->Rect( self::$x, self::$y, $lagura, $altura, 'D' );
		self::$ultimaAltura = $altura;
		$pdf->SetXY( self::$x, self::$y );
	}
	public static function Imagem( &$obj = false, &$pdf = false)
	{
		// obj = new Peca(); $pdf = new HtmlPDF();
		self::$x = $pdf->GetX();
		self::$y = $pdf->GetY();
		$dados = Configuracao::GetLogo(40);
		
		$pdf->image( $dados['CAMINHO'], 7, 5, 40, $dados['HEIGHT']);
		$pdf->SetXY( self::$x, self::$y );
	}
	
	##################################### DELETAR REGISTROS DE CONTATO ###############################
	public static function ListaItem(&$obj = false, &$pdf = false)
	{
		if(empty($obj))
		{
			return;
		}
		$codigo = $obj->Idpedido;
		if(empty($codigo))
		{
			return;
		}
		self::$x = $pdf->GetX();
		self::$y = $pdf->GetY();
		self::$ultimaAltura = 0;
		$filtro = false;
		$X = 7;
		$sql = "SELECT S.*, V.QUANTIDADE, I.IDITEM FROM item I LEFT JOIN servico S ON(I.IDSERVICO = S.IDSERVICO) LEFT JOIN verificado V ON(I.IDITEM = V.IDITEM) WHERE I.IDPEDIDO = '{$codigo}' ORDER BY I.IDITEM ASC";
		$lista = "";
		$total = 0;
		$servico = new Servico();
		$servico->decode = false;
		$servicos = $servico->FiltroObjetos($filtro,$sql);
		if($servicos)
		{
			$pdf->Cabecalho = array("SERVIÇO"=>27, "ATENDIMENTO"=>6, "QTD."=>3, "PREÇO"=>7, "TOTAL"=>7, "EXTRA"=>10, "APROVAÇÃO"=>5);
			$pdf->TamanhodaFonte = 4;
			self::$ultimaAltura = $pdf->ImprimeCabeca(247,247,247);
			$pdf->TamanhodaFonte = 8;
			$pdf->CampoLink = 'APROVAÇÃO';
			foreach($servicos as $servico)
			{
				$pdf->SetX($X);
				$parcial = ($servico->Preco * $servico->auxiliar['QUANTIDADE']);
				$total += $parcial;
				$servico->dados['SERVIÇO'] = $servico->dados['SERVICO'];
				$servico->dados['ATENDIMENTO'] = $servico->dados['ATENDIMENTO'] ." horas";
				if($servico->Preco == 0.0)
					$servico->dados['PREÇO'] = ' __ ';
				else
					$servico->dados['PREÇO'] = 'R$ '.Componente::Moeda($servico->dados['PRECO']);
				if($parcial == 0.0)
					$servico->dados['TOTAL'] = ' __ ';
				else
					$servico->dados['TOTAL'] = 'R$ '.Componente::Moeda($parcial);
				if($servico->Extra == "Sim")
					$servico->dados['EXTRA'] = "Não coberto";
				else
					$servico->dados['EXTRA'] = "Coberto pelo Plano";
				$servico->dados['APROVAÇÃO'] = Itemaprovado::GerarlinkItem($servico->auxiliar['IDITEM']);
				$servico->dados['QTD.'] = str_pad($servico->auxiliar['QUANTIDADE'], 2,"0", STR_PAD_LEFT);
				self::$ultimaAltura += $pdf->ImprimeLinha($servico->dados);
				/*$texto = "Aprovar";
				$url = "https://www.w3schools.com/charsets/ref_html_ansi.asp";
				$y = self::$y - 5;//Componente::P($y);
				$aux = 170;
				self::PrintLink($url, $texto, $aux, $y, $pdf);*/
			}
		}
		if($obj->Desconto != 0.0)
		{
			self::$y = $pdf->GetY();
			$pdf->SetXY( $X, self::$y);
			$pdf->SetTextColor(10,10,10);
			$pdf->Cell(130,5, "DESCONTO:  ",1,0,'R',1);
			
			$pdf->SetXY( $X + 129, self::$y);
			$desconto = $obj->GetDesconto();
			$pdf->Cell(66,5, $desconto,1,0,'L',1);
			$pdf->SetXY( $X, self::$y + 5);
			$total -= $obj->Desconto;
		}
		self::$y = $pdf->GetY();
		$pdf->SetXY( $X, self::$y);
		$pdf->SetTextColor(10,10,10);
		$pdf->SetFillColor(247,247,247);
		$pdf->SetFont( 'Arial', 'B', 8 );
		$pdf->Cell(130,10, "TOTAL:  ",1,0,'R',1);

		$texto = "Clique aqui para aprovar todos os Itens";
		$url = Itemaprovado::GerarlinkPedido($obj->Idpedido);
		$y = self::$y ;
		self::PrintLink($url, $texto, $X, $y, $pdf);

		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(242,9,52);
		$pdf->SetFont( 'Arial', '', 8);
		$pdf->SetXY( $X + 129, self::$y);
		$preco = ' R$ '.Componente::Moeda($total);
		$pdf->Cell(66,10, $preco,1,0,'L',1);
		self::$y = $pdf->GetY();
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(10,10,10);
		$pdf->SetFont( 'Arial', '', 8);
		self::$ultimaAltura += 6;

		$pdf->TamanhodaFonte = 9;
		unset($objs,$obj);
		return $total;
	}
	##################################### DELETAR REGISTROS DE CONTATO ###############################
	public static function TextoCobranca($total = 0, &$pdf = false, $idpedido)
	{
		if(empty($total))
		{
			return;
		}
		self::$x = $pdf->GetX();
		self::$y = $pdf->GetY();
		self::$ultimaAltura = 0;
		$X = 7;
		$texto = "Esta ordem de serviços possui serviços não contemplados no contrato de prestação de serviços.\nA execução se dará somente após aprovação, que poderá ser formalizada pelo link constante na coluna aprovação ou no link abaixo para aprovar todos os serviços solicitados.\n\n";
		
		$pdf->SetXY( $X, self::$y + 10.1);
		self::Caixa( $pdf, "Atenção:", $texto, 195 );
		$texto = "Clique aqui para aprovar todos os Itens";
		$url = Itemaprovado::GerarlinkPedido($idpedido);;
		$y = self::$y + self::$ultimaAltura - 10;
		self::PrintLink($url, $texto, $X, $y, $pdf);
		$pdf->SetXY( self::$x, self::$y);
		
		return;
	}
	##################################### DELETAR REGISTROS DE CONTATO ###############################
	public static function PrintLink($url = "", $texto = "", $x = 0, $y = 0, &$pdf = false)
	{
		self::$x = $pdf->GetX();
		self::$y = $pdf->GetY();
		$paddingx = 2;
		$pdf->SetXY( $x + $paddingx, $y);
		$altura = 10;
		$tamanho = $pdf->GetStringWidth($texto) + 10; 
		$pdf->SetTextColor(10,10,255);
		$pdf->Cell($tamanho, $altura, $texto, 0, 0, 'L', 0, $url); //Componente::P($y);
		$pdf->SetTextColor(10,10,10);
		$pdf->SetXY( self::$x, self::$y);
		return;
	}
	##################################### DELETAR REGISTROS DE CONTATO ###############################
	public static function ListaTramite($codigo = false, &$pdf = false)
	{#$pdf = new RelatorioPDF();
		$obj = new Tramitepedido();
		if(empty($codigo))
		{
			return;
		}
		
		$filtro = false;
		$sql = "SELECT T.IDTRAMITEPEDIDO, T.DESCRICAO, DATE_FORMAT(T.DATA, '%d/%m/%Y %H:%i:%s') as 'DATA', C.NOME AS COLABORADOR, S.NOME FROM tramitepedido T LEFT JOIN statusservico S ON(T.IDSTATUSSERVICO = S.IDSTATUSSERVICO) LEFT JOIN colaborador C ON(T.IDCOLABORADOR = C.IDCOLABORADOR) WHERE T.IDPEDIDO = '{$codigo}' ORDER BY T.`DATA` ASC";
		$lista = "";		
		$obj->decode = false;
		$objs = $obj->FiltroObjetos($filtro,$sql);
		if($objs)
		{
			foreach($objs as $obj)
			{
				$lista .= "<b>Status:</b> ". $obj->auxiliar['NOME'];
				$lista .= "\n". $obj->dados['DESCRICAO'];
				$lista .= "\n<b>Responsável:</b> ". $obj->auxiliar['COLABORADOR']." ".$obj->dados['DATA']."\n\n";
			}
		}
		unset($objs,$obj);
		return $lista;
	}
}
?>