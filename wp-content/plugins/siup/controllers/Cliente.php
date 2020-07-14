<?php 
/***********************************************************************
 * Module:  /controllers/Cliente.PHP
 * Plugin:  siup
 * Author:  CosmeWeb
 * Date:	27/05/2020 18:03:16
 * Purpose: Definição da Classe Cliente
 ***********************************************************************/
if (!class_exists('Cliente'))
{
	class Cliente
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
                $this->BaixarCliente($file);
            }
            elseif(!empty($page))
            {
                switch($page)
                {
                    case "listaclienteSIUP":
                        $this->ListaCliente();
                        break;
                    case "editarclienteSIUP":
                        $this->EditarCliente();
                        break;				
                }
            }
            elseif(!empty($metodo))
            {
                switch($metodo)
                {
                    case "listadecliente":
                        $this->ListaDeCliente();
                        break;
                    case "salvarcliente":
                        $this->SalvarCliente();
                        break;
                    case "excluircliente":
                        $this->ExcluirCliente();
                        break;
                    case "exportarcliente":
                        $this->ExportarCliente();
                        break;
                    case "enviarexcelcliente":
                        $this->EnviarExcelCliente();
                        break;
                    case "importacaoexcelcliente":
                        $this->ImportacaoExcelCliente();
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
        public function ListaCliente()
        {
            $obj = Componente::GetInstancia('cliente');
            $obj->Ajustar(false);
            $data['obj'] = $obj;
            $data['tamanhomax'] = Componente::TamanhoUpload();
            Componente::GetViews("adm/cliente/listar", $data);
            return;
        }	
        ################################################################################################################
        public function EditarCliente()
        {
            $id = Componente::Request("id");
            $obj = Componente::GetInstancia('cliente');
            if(empty($id))
            {
                $obj->Ajustar(false);
                $data['obj'] = $obj;
            }
            else
            {
                $obj->idcliente = $id;
                if(!$obj->Load())
                {
                    $data['titulo'] = __("Erro");
                    $data['mensagem'] = __("Erro ao localizar esta cliente", SIUP_LANG);
                    Componente::GetViews("template/erro", $data);
                    return;
                }
                $obj->Ajustar(false);
                $data['obj'] = $obj;
            }
            Componente::GetViews("adm/cliente/editar", $data);
            return;
        }
        ################################################################################################################
        public function SalvarCliente()
        {
            $dados = Componente::Request();
                    
            $cliente = Componente::GetInstancia("cliente");
            $cliente->Carregar($dados);
            $cliente->Ajustar(true);
            $id = $cliente->Salvar();
            if(empty($id))
            {
                Componente::SetErros(__("Falha ao salvar os dados da cliente.", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            
            if(empty($dados['idcliente']) )
            {
                $informe['mensagem'] = __("Os dados da cliente foram criados com sucesso.", SIUP_LANG);
            }
            else
            {
                $informe['mensagem'] = __("Os dados da cliente foram salvos com sucesso.", SIUP_LANG);
            }
            Componente::PrintDados($informe);
            wp_die();
            return;
        }	
        ################################################################################################################
        public function ExcluirCliente()
        {
            $obj = Componente::GetInstancia('cliente');
            $id = Componente::Request("id");
            $obj->idcliente = $id;
            if(!$obj->Apagar())
            {
                Componente::SetErros(__("Não foi possível apagar os dados do cliente.", SIUP_LANG));
                Componente::PrintErros();
            }
            else
            {
                $informe['mensagem'] = __("Cliente foi excluida com sucesso.", SIUP_LANG);
                Componente::PrintDados($informe);
            }
            wp_die();
            return;
        }
        ################################################################################################################
        public function ExportarCliente()
        {
            $obj = Componente::GetInstancia("cliente");		
            if(empty($obj))
            {
                Componente::SetErros(__("classe com os cliente não foi encontrados..", SIUP_LANG));
                Componente::PrintErros();
                return;
            }
            $dados = $obj->ExportarCliente();
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
        public function ListaDeCliente()
        {
            $obj = Componente::GetInstancia("cliente");
            
            if(empty($obj))
            {
                Componente::SetErros(__("classe com o cliente não foi encontradas..", SIUP_LANG));
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
                Componente::SetErros(__("Não foi possível listar os dados dos cliente.", HOST_LANG));
                Componente::PrintErros();
            }
            wp_die();
            return;
        }
        ################################################################################################################
        public function EnviarExcelCliente()
        {
            if(!empty($_FILES['file']['name']))
            {
                $test = explode('.', $_FILES['file']['name']);
                $extension = end($test);
                $name = "importacao_cliente_";
                $name .= date("Y-m-d_H-i-s_");
                $name .= rand(100,999).'.'.$extension;
                $obj = Componente::GetInstancia("cliente");
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
        public function ImportacaoExcelCliente()
        {
            $file = Componente::Request('file');
            $posicao = Componente::Request('posicao');
            $total = Componente::Request('total');
            $obj = Componente::GetInstancia('cliente');
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
                    $dados['mensagem'] = __("Importação de cliente está sendo processada.");
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
                    $dados['mensagem'] = __("Importação de cliente finalizada com sucesso.");
                    $dados['posicao'] = $posicao + $limite;
                    $dados['total'] = $total;
                }
            }
            else
            {
                Componente::SetErros(__("Erro ao localizar o registro de importação de cliente.", SIUP_LANG));
                Componente::PrintDados($dados);
                return;
            }
            Componente::PrintDados($dados);
            wp_die();
            return;
        }
        ################################################################################################################
        public function BaixarCliente($file = "")
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