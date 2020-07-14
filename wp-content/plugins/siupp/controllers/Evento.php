<?php 
/***********************************************************************
 * Module:  /controllers/Evento.PHP
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	21/05/2020 03:39:05
 * Purpose: Definição da Classe Evento
 ***********************************************************************/
if (!class_exists('Evento'))
{
	class Evento
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
                $this->BaixarEvento($file);
            }
            elseif(!empty($page))
            {
                switch($page)
                {
                    case "listaeventoSIUP":
                        $this->ListaEvento();
                        break;
                    case "editareventoSIUP":
                        $this->EditarEvento();
                        break;				
                }
            }
            elseif(!empty($metodo))
            {
                switch($metodo)
                {
                    case "listadeevento":
                        $this->ListaDeEvento();
                        break;
                    case "salvarevento":
                        $this->SalvarEvento();
                        break;
                    case "excluirevento":
                        $this->ExcluirEvento();
                        break;
                    case "exportarevento":
                        $this->ExportarEvento();
                        break;
                    case "enviarexcelevento":
                        $this->EnviarExcelEvento();
                        break;
                    case "importacaoexcelevento":
                        $this->ImportacaoExcelEvento();
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
        public function ListaEvento()
        {
            $obj = Componente::GetInstancia('evento');
            $obj->Ajustar(false);
            $data['obj'] = $obj;
            $data['tamanhomax'] = Componente::TamanhoUpload();
            Componente::GetViews("adm/evento/listar", $data);
            return;
        }	
        ################################################################################################################
        public function EditarEvento()
        {
            $id = Componente::Request("id");
            $obj = Componente::GetInstancia('evento');
            if(empty($id))
            {
                $obj->Ajustar(false);
                $data['obj'] = $obj;
            }
            else
            {
                $obj->idevento = $id;
                if(!$obj->Load())
                {
                    $data['titulo'] = __("Erro");
                    $data['mensagem'] = __("Erro ao localizar esta evento", SIUP_LANG);
                    Componente::GetViews("template/erro", $data);
                    return;
                }
                $obj->Ajustar(false);
                $data['obj'] = $obj;
            }
            Componente::GetViews("adm/evento/editar", $data);
            return;
        }
        ################################################################################################################
        public function SalvarEvento()
        {
            $dados = Componente::Request();
                    
            $evento = Componente::GetInstancia("evento");
            $evento->Carregar($dados);
            $evento->Ajustar(true);
            $id = $evento->Salvar();
            if(empty($id))
            {
                Componente::SetErros(__("Falha ao salvar os dados da evento.", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            
            if(empty($dados['idevento']) )
            {
                $informe['mensagem'] = __("Os dados da evento foram criados com sucesso.", SIUP_LANG);
            }
            else
            {
                $informe['mensagem'] = __("Os dados da evento foram salvos com sucesso.", SIUP_LANG);
            }
            Componente::PrintDados($informe);
            return;
        }	
        ################################################################################################################
        public function ExcluirEvento()
        {
            $obj = Componente::GetInstancia('evento');
            $id = Componente::Request("id");
            $obj->idevento = $id;
            if(!$obj->Apagar())
            {
                Componente::SetErros(__("Não foi possível apagar os dados do evento.", SIUP_LANG));
                Componente::PrintErros();
            }
            else
            {
                $informe['mensagem'] = __("Evento foi excluida com sucesso.", SIUP_LANG);
                Componente::PrintDados($informe);
            }
            return;
        }
        ################################################################################################################
        public function ExportarEvento()
        {
            $obj = Componente::GetInstancia("evento");		
            if(empty($obj))
            {
                Componente::SetErros(__("classe com os evento não foi encontrados..", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            $dados = $obj->ExportarEvento();
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
        public function ListaDeEvento()
        {
            $obj = Componente::GetInstancia("evento");
            
            if(empty($obj))
            {
                Componente::SetErros(__("classe com o evento não foi encontradas..", SIUP_LANG));
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
                Componente::SetErros(__("Não foi possível listar os dados dos evento.", HOST_LANG));
                Componente::PrintErros();
            }
            return;
        }
        ################################################################################################################
        public function EnviarExcelEvento()
        {
            if(!empty($_FILES['file']['name']))
            {
                $test = explode('.', $_FILES['file']['name']);
                $extension = end($test);
                $name = "importacao_evento_";
                $name .= date("Y-m-d_H-i-s_");
                $name .= rand(100,999).'.'.$extension;
                $obj = Componente::GetInstancia("evento");
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
        public function ImportacaoExcelEvento()
        {
            $file = Componente::Request('file');
            $posicao = Componente::Request('posicao');
            $total = Componente::Request('total');
            $obj = Componente::GetInstancia('evento');
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
                    $dados['mensagem'] = __("Importação de evento está sendo processada.");
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
                    $dados['mensagem'] = __("Importação de evento finalizada com sucesso.");
                    $dados['posicao'] = $posicao + $limite;
                    $dados['total'] = $total;
                }
            }
            else
            {
                Componente::SetErros(__("Erro ao localizar o registro de importação de evento.", SIUP_LANG));
                Componente::PrintDados($dados);
                return;
            }
            Componente::PrintDados($dados);
            return;
        }
        ################################################################################################################
        public function BaixarEvento($file = "")
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