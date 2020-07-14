<?php 
/***********************************************************************
 * Module:  /controllers/Enquete.PHP
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	27/05/2020 20:28:35
 * Purpose: Definição da Classe Enquete
 ***********************************************************************/
if (!class_exists('Enquete'))
{
	class Enquete
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
                $this->BaixarEnquete($file);
            }
            elseif(!empty($page))
            {
                switch($page)
                {
                    case "listaenqueteSIUP":
                        $this->ListaEnquete();
                        break;
                    case "editarenqueteSIUP":
                        $this->EditarEnquete();
                        break;				
                }
            }
            elseif(!empty($metodo))
            {
                switch($metodo)
                {
                    case "listadeenquete":
                        $this->ListaDeEnquete();
                        break;
                    case "salvarenquete":
                        $this->SalvarEnquete();
                        break;
                    case "excluirenquete":
                        $this->ExcluirEnquete();
                        break;
                    case "exportarenquete":
                        $this->ExportarEnquete();
                        break;
                    case "enviarexcelenquete":
                        $this->EnviarExcelEnquete();
                        break;
                    case "importacaoexcelenquete":
                        $this->ImportacaoExcelEnquete();
                        break;
                    case "excluiropcao":
                        $this->ExcluirOpcao();
                        break;
                    case "carregaropcoes":
                        $this->CarregarOpcoes();
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
        public function ListaEnquete()
        {
            $obj = Componente::GetInstancia('enquete');
            $obj->Ajustar(false);
            $data['obj'] = $obj;
            $data['tamanhomax'] = Componente::TamanhoUpload();
            Componente::GetViews("adm/enquete/listar", $data);
            return;
        }	
        ################################################################################################################
        public function EditarEnquete()
        {
            $id = Componente::Request("id");
            $obj = Componente::GetInstancia('enquete');
            if(empty($id))
            {
                $obj->Ajustar(false);
                $data['obj'] = $obj;
            }
            else
            {
                $obj->idenquete = $id;
                if(!$obj->Load())
                {
                    $data['titulo'] = __("Erro");
                    $data['mensagem'] = __("Erro ao localizar esta enquete", SIUP_LANG);
                    Componente::GetViews("template/erro", $data);
                    return;
                }
                $obj->Ajustar(false);
                $data['obj'] = $obj;
            }
            Componente::GetViews("adm/enquete/editar", $data);
            return;
        }
        ################################################################################################################
        public function SalvarEnquete()
        {
            $dados = Componente::Request();
                    
            $enquete = Componente::GetInstancia("enquete");
            $enquete->Carregar($dados);
            $enquete->Ajustar(true);
            $id = $enquete->Salvar();
            if(empty($id))
            {
                Componente::SetErros(__("Falha ao salvar os dados da enquete.", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            if(empty($dados['idenquete']) )
            {
                $enquete->idenquete = $id;
            }
            $opcao = Componente::GetInstancia("opcaoenquete");
            $idopcaoenquetes = $dados['idopcaoenquete'];
            if( empty($idopcaoenquetes))
            {
                foreach($idopcaoenquetes as $key=>$idopcaoenquete)
                {
                    $posicao = $dados['posicao'][$key];
                    $opcaotexto = $dados['opcao'][$key];
                    $votos = $dados['votos'][$key];
                    $opcao->SalvarLista($idopcaoenquete, $enquete->idenquete, $posicao, $opcaotexto, $votos);
                }
            }
            if(empty($dados['idenquete']) )
            {
                $informe['mensagem'] = __("Os dados da enquete foram criados com sucesso.", SIUP_LANG);
            }
            else
            {
                $informe['mensagem'] = __("Os dados da enquete foram salvos com sucesso.", SIUP_LANG);
            }
            //componente::P($enquete);
            Componente::PrintDados($informe);
            wp_die();
            return;
        }	
        ################################################################################################################
        public function ExcluirEnquete()
        {
            $obj = Componente::GetInstancia('enquete');
            $id = Componente::Request("id");
            $obj->idenquete = $id;
            if(!$obj->Apagar())
            {
                Componente::SetErros(__("Não foi possível apagar os dados do enquete.", SIUP_LANG));
                Componente::PrintErros();
            }
            else
            {
                $informe['mensagem'] = __("Enquete foi excluida com sucesso.", SIUP_LANG);
                Componente::PrintDados($informe);
            }
            wp_die();
            return;
        }
        ################################################################################################################
        public function ExportarEnquete()
        {
            $obj = Componente::GetInstancia("enquete");		
            if(empty($obj))
            {
                Componente::SetErros(__("classe com os enquete não foi encontrados..", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            $dados = $obj->ExportarEnquete();
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
            wp_die();
            return;
        }
        ################################################################################################################
        public function ListaDeEnquete()
        {
            $obj = Componente::GetInstancia("enquete");
            
            if(empty($obj))
            {
                Componente::SetErros(__("classe com o enquete não foi encontradas..", SIUP_LANG));
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
                Componente::SetErros(__("Não foi possível listar os dados dos enquete.", HOST_LANG));
                Componente::PrintErros();
            }
            wp_die();
            return;
        }
        ################################################################################################################
        public function EnviarExcelEnquete()
        {
            if(!empty($_FILES['file']['name']))
            {
                $test = explode('.', $_FILES['file']['name']);
                $extension = end($test);
                $name = "importacao_enquete_";
                $name .= date("Y-m-d_H-i-s_");
                $name .= rand(100,999).'.'.$extension;
                $obj = Componente::GetInstancia("enquete");
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
            wp_die();
            return;
        }
        ################################################################################################################
        public function ImportacaoExcelEnquete()
        {
            $file = Componente::Request('file');
            $posicao = Componente::Request('posicao');
            $total = Componente::Request('total');
            $obj = Componente::GetInstancia('enquete');
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
                    $dados['mensagem'] = __("Importação de enquete está sendo processada.");
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
                    $dados['mensagem'] = __("Importação de enquete finalizada com sucesso.");
                    $dados['posicao'] = $posicao + $limite;
                    $dados['total'] = $total;
                }
            }
            else
            {
                Componente::SetErros(__("Erro ao localizar o registro de importação de enquete.", SIUP_LANG));
                Componente::PrintDados($dados);
                return;
            }
            Componente::PrintDados($dados);
            wp_die();
            return;
        }
        ################################################################################################################
        public function BaixarEnquete($file = "")
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
        ################################################################################################################
        public function CarregarOpcoes()
        {
            $contato = Componente::GetInstancia("opcaoenquete");
            $contato->CarregarOpcoes();
            return;
        }
        ################################################################################################################
        public function ExcluirOpcao()
        {
            $obj = Componente::GetInstancia('opcaoenquete');
            $id = Componente::Request("id");
            $obj->idopcaoenquete = $id;
            if(!$obj->Apagar())
            {
                Componente::SetErros(__("Não foi possível apagar os dados desta opção.", HOST_LANG));
                Componente::PrintErros();
            }
            else
            {
                $informe['mensagem'] = __("Opção de enquete foi excluida com sucesso.");
                Componente::PrintDados($informe);
            }
            return;
        }
		#region AREA PARA IMPLEMENTAÇÃO ADICIONAIS.

		#endregion FIM AREA PARA IMPLEMENTAÇÃO ADICIONAIS.
	}
}
?>