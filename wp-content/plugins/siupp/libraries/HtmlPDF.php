<?php
require_once(HOST_LIBRARIES_PATH."fpdf.php");
class HtmlPDF extends FPDF
{
	var $B;
	var $I;
	var $U;
	var $HREF;
	var $Voltar;
	var $Titulo;
	var $Campos;
	function __construct($orientation='P',$unit='mm',$format='A4')
	{
		$this->FPDF($orientation,$unit,$format);
	}
	function PDF($orientation='P',$unit='mm',$format='A4')
	{
		//Call parent constructor
		$this->FPDF($orientation,$unit,$format);
		//Initialization
		$this->B=0;
		$this->I=0;
		$this->U=0;
		$this->HREF='';
		$this->Campos = false;
		$this->Voltar = site_url();
		
	}
	/*function Header()
	{
		if(empty($this->Campos))
		{
			//Arial bold 15
			$this->SetFont('Arial','B',15);
			//Calculate width of title and position
			$w = $this->GetStringWidth($this->Titulo)+6;
			$this->SetX((210-$w)/2);
			$this->SetLineWidth(1);
			//Title
			$this->Cell($w,9,$this->Titulo,0,0,'C');
			//Line break
			$this->Ln(20);
		}
		else
		{
			$this->SetFont('Arial','b',10);
			$this->SetFillColor(204, 204, 204); 
			$h = $this->FontSize + 10;
			foreach ($this->Campos as $key => $w)
			{
				$this->Cell($w,$h,$key,1,2,'L',1);
			}
			//print_r($this->Campos);
			$this->SetX(0);
		}
	}
	
	//Page footer
	function Footer()
	{
		//Position at 1.5 cm from bottom
		$this->SetY(-18);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Page number
		$configuracao = new Configuracao();
		$configuracao->LeConfiguracao();
		$this->Cell(0,10,$configuracao->Nome,0,0,'C');
		$this->SetY(-15);
		$this->Cell(0,10,date('d/m/Y'),0,0,'C');
		$this->SetY(-12);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		$this->PutLink($this->Voltar,"Voltar");
		
	}
	
*/	
	function WriteHTML($html)
	{
		//HTML parser
		$html=str_replace("<p>","<p>       ",$html);
		$html=str_replace("<P>","<p>       ",$html);
		$html=str_replace("\n",' ',$html);
		$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				//Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else
			{
				//Tag
				if($e{0}=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					//Extract attributes
					$a2 = explode(' ',$e);
					$tag = strtoupper(array_shift($a2));
					$attr = array();
					foreach($a2 as $v)
						if(preg_match('/^([^=]*)=["\']?([^"\']*)["\']?$/',$v,$a3))
							$attr[strtoupper($a3[1])]=$a3[2];
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}
	
	function OpenTag($tag,$attr)
	{
		//Opening tag
		if($tag=='B' or $tag=='I' or $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF=$attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
		if($tag=='P')
			$this->Ln(10);
	}
	
	function CloseTag($tag)
	{
		//Closing tag
		if($tag=='B' or $tag=='I' or $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF='';
	}
	
	function SetStyle($tag,$enable)
	{
		//Modify style and select corresponding font
		$this->$tag+=($enable ? 1 : -1);
		$style='';
		foreach(array('B','I','U') as $s)
			if($this->$s>0)
				$style.=$s;
		$this->SetFont('',$style);
	}
	
	function PutLink($URL,$txt)
	{
		//Put a hyperlink
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}
	function Format($txt,$area)
	{
		//Fomatar com os caracteres que falta
		$aux = explode("</B> ",$txt);
		$aux[0] = str_replace("<B>", " ", $aux[0])." ";
		$this->SetStyle('B',true);
		$wLabel=$this->GetStringWidth($aux[0]);
		$this->SetStyle('B',false);
		$w=$this->GetStringWidth($aux[1])+$wLabel;
		if($w>$area)
		{
			$txt.= "<br>";
		}
		while($w<$area)
		{
			$aux[1].= " ";
			$txt.= " ";
			$w=$this->GetStringWidth($aux[1])+$wLabel; 
		}              
		return $txt;   
	}
	
	function Labelvalor($campo,$txt,$area)
	{
		
		$txt=' <B>'.$campo.':</B> '.$txt;
		//Fomatar com os caracteres que falta
		$txt=$this->Format($txt,$area);
		return $txt;   
	}

	function IsFimDePagina($h)
	{
		//Output a cell
		$k=$this->k;
		if($this->y+$h>$this->PageBreakTrigger && !$this->InFooter && $this->AcceptPageBreak())
		{
			return true;
		}
		return false;
	}
}

?>