<?php 
/***********************************************************************
 * Module:  /controllers/Pedido.PHP
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	15/06/2020 02:58:38
 * Purpose: Definição da Classe Pedido
 ***********************************************************************/
if (!class_exists('Pedido'))
{
	class Pedido
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
                $this->BaixarPedido($file);
            }
            elseif(!empty($page))
            {
                switch($page)
                {
                    case "listapedidoSIUP":
                        $this->ListaPedido();
                        break;
                    case "editarpedidoSIUP":
                        $this->EditarPedido();
                        break;				
                }
            }
            elseif(!empty($metodo))
            {
                switch($metodo)
                {
                    case "listadepedido":
                        $this->ListaDePedido();
                        break;
                    case "salvarpedido":
                        $this->SalvarPedido();
                        break;
                    case "excluirpedido":
                        $this->ExcluirPedido();
                        break;
                    case "exportarpedido":
                        $this->ExportarPedido();
                        break;
                    case "enviarexcelpedido":
                        $this->EnviarExcelPedido();
                        break;
                    case "importacaoexcelpedido":
                        $this->ImportacaoExcelPedido();
                        break;
                    case "salvardocumento":
                        $this->SalvarDocumento();
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
        public function ListaPedido()
        {
            $obj = Componente::GetInstancia('pedido');
            $obj->Ajustar(false);
            $data['obj'] = $obj;
            $data['tamanhomax'] = Componente::TamanhoUpload();
            Componente::GetViews("adm/pedido/listar", $data);
            return;
        }	
        ################################################################################################################
        public function EditarPedido()
        {
            $id = Componente::Request("id");
            $obj = Componente::GetInstancia('pedido');
            if(empty($id))
            {
                $obj->Ajustar(false);
                $data['obj'] = $obj;
            }
            else
            {
                $obj->idpedido = $id;
                if(!$obj->Load())
                {
                    $data['titulo'] = __("Erro");
                    $data['mensagem'] = __("Erro ao localizar esta pedido", SIUP_LANG);
                    Componente::GetViews("template/erro", $data);
                    return;
                }
                $obj->Ajustar(false);
                $data['obj'] = $obj;
            }
            Componente::GetViews("adm/pedido/editar", $data);
            return;
        }
        ################################################################################################################
        public function SalvarPedido()
        {
            $dados = Componente::Request();
                    
            $pedido = Componente::GetInstancia("pedido");
            $pedido->Carregar($dados);
            $pedido->Ajustar(true);
            $id = $pedido->Salvar();
            if(empty($id))
            {
                Componente::SetErros(__("Falha ao salvar os dados da pedido.", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            if(empty($dados['idpedido']) )
            {
                $pedido->idpedido = $id;
            }
            $documento = Componente::GetInstancia("documento");
            $iddocumentos = $dados['iddocumento'];
            if(!empty($iddocumentos))
            {
                foreach($iddocumentos as $key=>$iddocumento)
                {
                    $documento = $dados['documento'][$key];
                    $data = $dados['data'][$key];
                    $documento->SalvarLista($iddocumento, $pedido->idpedido, $documento, $data);
                }
            }
            if(empty($dados['idpedido']) )
            {
                $informe['mensagem'] = __("Os dados da pedido foram criados com sucesso.", SIUP_LANG);
            }
            else
            {
                $informe['mensagem'] = __("Os dados da pedido foram salvos com sucesso.", SIUP_LANG);
            }
            Componente::PrintDados($informe);
            wp_die();
            return;
        }	
        ################################################################################################################
        public function ExcluirPedido()
        {
            $obj = Componente::GetInstancia('pedido');
            $id = Componente::Request("id");
            $obj->idpedido = $id;
            if(!$obj->Apagar())
            {
                Componente::SetErros(__("Não foi possível apagar os dados do pedido.", SIUP_LANG));
                Componente::PrintErros();
            }
            else
            {
                $informe['mensagem'] = __("Pedido foi excluida com sucesso.", SIUP_LANG);
                Componente::PrintDados($informe);
            }
            wp_die();
            return;
        }
        ################################################################################################################
        public function ExportarPedido()
        {
            $obj = Componente::GetInstancia("pedido");		
            if(empty($obj))
            {
                Componente::SetErros(__("classe com os pedido não foi encontrados..", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            $dados = $obj->ExportarPedido();
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
        public function ListaDePedido()
        {
            $obj = Componente::GetInstancia("pedido");
            
            if(empty($obj))
            {
                Componente::SetErros(__("classe com o pedido não foi encontradas..", SIUP_LANG));
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
                Componente::SetErros(__("Não foi possível listar os dados dos pedido.", SIUP_LANG));
                Componente::PrintErros();
            }
            wp_die();
            return;
        }
        ################################################################################################################
        public function EnviarExcelPedido()
        {
            if(!empty($_FILES['file']['name']))
            {
                $test = explode('.', $_FILES['file']['name']);
                $extension = end($test);
                $name = "importacao_pedido_";
                $name .= date("Y-m-d_H-i-s_");
                $name .= rand(100,999).'.'.$extension;
                $obj = Componente::GetInstancia("pedido");
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
        public function ImportacaoExcelPedido()
        {
            $file = Componente::Request('file');
            $posicao = Componente::Request('posicao');
            $total = Componente::Request('total');
            $obj = Componente::GetInstancia('pedido');
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
                    $dados['mensagem'] = __("Importação de pedido está sendo processada.");
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
                    $dados['mensagem'] = __("Importação de pedido finalizada com sucesso.");
                    $dados['posicao'] = $posicao + $limite;
                    $dados['total'] = $total;
                }
            }
            else
            {
                Componente::SetErros(__("Erro ao localizar o registro de importação de pedido.", SIUP_LANG));
                Componente::PrintDados($dados);
                return;
            }
            Componente::PrintDados($dados);
            wp_die();
            return;
        }
        ################################################################################################################
        public function BaixarPedido($file = "")
        {
            $caminho = Componente::GetPasta("siup/");
            $file = $caminho.$file;
            //Componente::P($file);exit();
            if(!file_exists($file))
            {
                echo __("Arquivo não foi localizado em nosso sistema.", SIUP_LANG);
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
         ################################################################################################################
         public function CarregarDocumentos()
         {
             $contato = Componente::GetInstancia("documento");
             $contato->CarregarDdocumento();
             return;
         }
         ################################################################################################################
         public function ExcluirDocumento()
         {
             $obj = Componente::GetInstancia('documento');
             $id = Componente::Request("id");
             $obj->iddocumento = $id;
             if(!$obj->Apagar())
             {
                 Componente::SetErros(__("Não foi possível apagar os dados deste documento.", SIUP_LANG));
                 Componente::PrintErros();
             }
             else
             {
                 $informe['mensagem'] = __("Documento foi excluida com sucesso.");
                 Componente::PrintDados($informe);
             }
             return;
         }
        ################################################################################################################
        public function SalvarDocumento()
        {
            $obj = Componente::GetInstancia('Documento');
            $obj->idpedido = Componente::Request("idpedido",0);
           
            $obj->Ajustar(true);
            if(!empty($_FILES['files']['name'][0]))
            {
                $error = $_FILES['files']['error'][0];
                if($error !== UPLOAD_ERR_OK)
                {
                    $mensagem = Componente::codeToMessageFile($error);
                    Componente::SetErros($mensagem);
                    Componente::PrintErros();
                    return;
                }
                $caminho = $obj->GetDIR();
                if(!is_dir($caminho))
                {
                    Componente::CriarPastas($caminho);
                }
                $name = strtolower($_FILES['files']['name'][0]);
                $name = uniqid("documento_")."_".Componente::AcertaNomeArquivo($name);
                $location = $caminho.$name;
                if(!move_uploaded_file($_FILES['files']['tmp_name'][0], $location))
                {
                    $mensagem = __("Erro ao salvar a imagem do slide.");
                    Componente::SetErros($mensagem);
                    Componente::PrintErros();
                    return;
                }
                else
                {
                    $obj->Set("documento", $name);
                }
            }
            else
            {
                Componente::SetErros(__("Você deve selecionar as imagens para o slide da empresa.", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            $id = $obj->Salvar();
            if(!$id)
            {
                Componente::SetErros(__("Não foi possível salvar os dados do slide.", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            $obj->iddocumento = $id;
            $informe['mensagem'] = sprintf(__("O arquivo %s do documento salvo com sucesso."), $name);
            $informe['imagem'] = $obj->GetDocumento();
            $informe['extensao'] = $obj->GetExtensao();
            $informe['iddocumento'] = $obj->iddocumento;
            $informe['idpedido'] = $obj->idpedido;
            Componente::PrintDados($informe);
            return;
        }
	    #endregion FIM AREA PARA IMPLEMENTAÇÃO ADICIONAIS.
	}
}
?>