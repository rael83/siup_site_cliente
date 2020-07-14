<?php 
/***********************************************************************
 * Module:  /controllers/Area.PHP
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	12/05/2020 16:39:34
 * Purpose: Definição da Classe Area
 ***********************************************************************/
if (!class_exists('Area'))
{
	class Area
	{
		#region AREA PARA IMPLEMENTAÇÃO CABEÇARIO.
		################################################################################################################
        function __construct()
        {
        }
        ################################################################################################################
        public function Controle($file = "")
        {
            $page = Componente::Request("page");
            $metodo = Componente::Request("metodo");
            if(!empty($file))
            {
                $this->BaixarArea($file);
            }
            elseif(!empty($page))
            {
                switch($page)
                {
                    case "listaareaSIUP":
                        $this->ListaArea();
                        break;
                    case "editarareaSIUP":
                        $this->EditarArea();
                        break;				
                }
            }
            elseif(!empty($metodo))
            {
                switch($metodo)
                {
                    case "listadearea":
                        $this->ListaDeArea();
                        break;
                    case "salvararea":
                        $this->SalvarArea();
                        break;
                    case "excluirarea":
                        $this->ExcluirArea();
                        break;
                    case "exportararea":
                        $this->ExportarArea();
                        break;
                    case "enviarexcelarea":
                        $this->EnviarExcelArea();
                        break;
                    case "importacaoexcelarea":
                        $this->ImportacaoExcelArea();
                        break;
                }
            }
            else
            {
                Componente::SetErros(__("Erro na passagem de paramentos", SIUP_LANG));
                Componente::PrintErros();
            }
            return;
        }
		#endregion FIM DA AREA PARA IMPLEMENTAÇÃO CABEÇARIO.

		#region AREA PARA IMPLEMENTAÇÃO CORPO.
        ################################################################################################################
        public function ListaArea()
        {
            $obj = Componente::GetInstancia('area');
            $obj->Ajustar(false);
            $data['obj'] = $obj;
            $data['tamanhomax'] = Componente::TamanhoUpload();
            Componente::GetViews("adm/area/listar", $data);
            return;
        }	
        ################################################################################################################
        public function EditarArea()
        {
            	
		    wp_enqueue_media();
            $id = Componente::Request("id");
            $obj = Componente::GetInstancia('area');
            if(empty($id))
            {
                $obj->Ajustar(false);
                $data['obj'] = $obj;
            }
            else
            {
                $obj->idarea = $id;
                if(!$obj->Load())
                {
                    $data['titulo'] = __("Erro");
                    $data['mensagem'] = __("Erro ao localizar esta area", SIUP_LANG);
                    Componente::GetViews("template/erro", $data);
                    return;
                }
                $obj->Ajustar(false);
                $data['obj'] = $obj;
            }
            Componente::GetViews("adm/area/editar", $data);
            return;
        }
        ################################################################################################################
        public function SalvarArea()
        {
            $dados = Componente::Request();
                    
            $area = Componente::GetInstancia("area");
            $area->Carregar($dados);
            $area->Ajustar(true);
            $id = $area->Salvar();
            if(empty($id))
            {
                Componente::SetErros(__("Falha ao salvar os dados da area.", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            
            if(empty($dados['idarea']) )
            {
                $informe['mensagem'] = __("Os dados da area foram criados com sucesso.", SIUP_LANG);
            }
            else
            {
                $informe['mensagem'] = __("Os dados da area foram salvos com sucesso.", SIUP_LANG);
            }
            Componente::PrintDados($informe);
            return;
        }	
        ################################################################################################################
        public function ExcluirArea()
        {
            $obj = Componente::GetInstancia('area');
            $id = Componente::Request("id");
            $obj->idarea = $id;
            if(!$obj->Apagar())
            {
                Componente::SetErros(__("Não foi possível apagar os dados do area.", SIUP_LANG));
                Componente::PrintErros();
            }
            else
            {
                $informe['mensagem'] = __("Area foi excluida com sucesso.", SIUP_LANG);
                Componente::PrintDados($informe);
            }
            return;
        }
        ################################################################################################################
        public function ExportarArea()
        {
            $obj = Componente::GetInstancia("area");		
            if(empty($obj))
            {
                Componente::SetErros(__("classe com os area não foi encontrados..", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            $dados = $obj->ExportarArea();
            if(!empty($dados['sucesso']))
            {
                unset($dados['sucesso']);
                Componente::PrintDados($dados);
            }
            else
            {
                Componente::SetErros($dados['erro']);
                Componente::PrintErros();
            }
            return;
        }
        ################################################################################################################
        public function ListaDeArea()
        {
            $obj = Componente::GetInstancia("area");
            
            if(empty($obj))
            {
                Componente::SetErros(__("classe com o area não foi encontradas..", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            $filtro = $obj->Filtro();
            $sql = $obj->GetSqlLista();
            $sqlTotal = $obj->GetSqlTotalLista();
            $dados = $obj->listatabela($filtro, $sql, $sqlTotal);
            if(!empty($dados))
            {
                Componente::Output($dados);
            }
            else
            {
                Componente::SetErros(__("Não foi possível listar os dados dos area.", HOST_LANG));
                Componente::PrintErros();
            }
            return;
        }
        ################################################################################################################
        public function EnviarExcelArea()
        {
            if(!empty($_FILES['file']['name']))
            {
                $test = explode('.', $_FILES['file']['name']);
                $extension = end($test);
                $name = "importacao_area_";
                $name .= date("Y-m-d_H-i-s_");
                $name .= rand(100,999).'.'.$extension;
                $obj = Componente::GetInstancia("area");
                $caminho = Componente::GetPasta("siup/");
                if(!is_dir($caminho))
                {
                    Componente::CriarPastas($caminho);
                }
                $location = $caminho.$name;
                if(!move_uploaded_file($_FILES['file']['tmp_name'], $location))
                {
                    Componente::SetErros(__("Erro ao localizar o arquivo."));
                    Componente::PrintErros();
                    return;
                }
                else
                {
                    $erro = $obj->VerificarArquivo($name);
                    if(empty($erro))
                    {
                        $dados['arquivo'] = $location;
                        $dados['caminho'] = Componente::GetPasta("siup/");
                        $dados['file'] = $name;
                        $dados['total'] = $obj->LerTotalRows($name);
                    }
                    else
                    {
                        Componente::SetErros($erro);
                        Componente::PrintErros();
                        return;
                    }
                }
            }
            else
            {
                Componente::SetErros(__("Erro ao localizar o arquivo."));
                Componente::PrintErros();
                return;
            }
            Componente::PrintDados($dados);
            return;
        }
        ################################################################################################################
        public function ImportacaoExcelArea()
        {
            $file = Componente::Request('file');
            $posicao = Componente::Request('posicao');
            $total = Componente::Request('total');
            $obj = Componente::GetInstancia('area');
            $arquivo = Componente::GetPasta("siup/".$file);
            $limiteimportaca = 50;
            if($obj->FileExiste($arquivo))
            {
                $limite = $total - $posicao;
                if($limite > $limiteimportacao)
                {
                    if(!$obj->Importar($posicao, self::$limiteimportacao, $file))
                    {
                        Componente::SetErros(__("Erro no processo de importação do excel para o banco.", SIUP_LANG));
                        Componente::PrintDados($dados);
                        return;
                    }
                    $dados['status'] = "Processando";
                    $dados['file'] = $file;
                    $dados['mensagem'] = __("Importação de area está sendo processada.");
                    $dados['posicao'] = $posicao + self::$limiteimportacao;
                    $dados['total'] = $total;
                }
                else
                {
                    if(!$obj->Importar($posicao, $limite, $file))
                    {
                        Componente::SetErros(__("Erro no processo de importação do excel para o banco.", SIUP_LANG));
                        Componente::PrintDados($dados);
                        return;
                    }
                    
                    $dados['status'] = "Finalizado";
                    $dados['file'] = $file;
                    $dados['mensagem'] = __("Importação de area finalizada com sucesso.");
                    $dados['posicao'] = $posicao + $limite;
                    $dados['total'] = $total;
                }
            }
            else
            {
                Componente::SetErros(__("Erro ao localizar o registro de importação de area.", SIUP_LANG));
                Componente::PrintDados($dados);
                return;
            }
            Componente::PrintDados($dados);
            return;
        }
        ################################################################################################################
        public function BaixarArea($file = "")
        {
            $caminho = Componente::GetPasta("siup/");
            $file = $caminho.$file;
            //Componente::P($file);exit();
            if(!file_exists($file))
            {
                echo __("Arquivo não foi localizado em nosso sistema.", HOST_LANG);
                exit();
            }
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            @ob_clean();
            flush();
            readfile($file);
            unlink($file);
            exit();
        }
		#endregion FIM DA AREA PARA IMPLEMENTAÇÃO CORPO.

		#region AREA PARA IMPLEMENTAÇÃO ADICIONAIS.

		#endregion FIM AREA PARA IMPLEMENTAÇÃO ADICIONAIS.
	}
}
?>