<?php
############ DS DIGITAL ###################
	
	class Componente
	{
		private static $erros = NULL;
		private static $dadosCookies = NULL;
		public static $Estados = array('AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas', 'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo', 'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul', 'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná', 'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte', 'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina', 'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins');
		
		######################################################################################################################
		public static function objectToArray($d)
		{
			if (is_object($d))
			{
				$d = get_object_vars( $d );
			}
			if( is_array( $d ) )
			{
				return array_map( array("Componente", "objectToArray"), $d );
			}
			else
			{
				return $d;
			}
		}
		################################################################################################################
		public static function &GetSql($sql = "")
		{
			global $wpdb;
			$query = $wpdb->query($sql);
			return $query;
		}
		################################################################################################################
		public static function NumLinhas(&$data = false)
		{
			return count($data);
		}
		################################################################################################################
		public static function NomeCampo(&$data = false, $posicao = 0)
		{
			if(empty($data[0]))
				$linha = array_keys($data);
			else
				$linha = array_keys($data);
			return $linha[$posicao];
		}
		################################################################################################################
		public static function &GetLinha($sql = "")
		{
			global $wpdb;
			$linha = $wpdb->get_row( $sql, ARRAY_A );
			return $linha;
		}
		################################################################################################################
		public static function GetUltimoID()
		{
			global $wpdb;
			return $wpdb->insert_id;
		}
		################################################################################################################
		public static function Free()
		{
			global $wpdb;
			$wpdb->flush();
		}
		################################################################################################################
		public static function GetSqlrow($sql = "", $defult = false)
		{
			$valor = $defult;
			$linha = self::GetLinha($sql);
			if(!empty($linha))
			{
				$valor = $linha;
			}
			self::Free();
			return $valor;
		}
		################################################################################################################
		public static function GetPrefix()
		{
			global $wpdb;
			return $wpdb->prefix;
		}
		################################################################################################################
		public static function GetSqlrows($sql = "")
		{
			global $wpdb;
			$lista = $wpdb->get_results( $sql, ARRAY_A );
			return $lista;
		}
		################################################################################################################
		public static function GetSqlCampo($sql = "", $nome = "", $defult = false)
		{
			$valor = $defult;
			$linha = self::GetSqlrow($sql, $defult);
			if(!empty($linha))
			{
				if(!empty($linha[$nome]))
					$valor = $linha[$nome];
			}
			return $valor;
		}
		################################################################################################################
		public static function GeraOpcoesSql($valor = "", $sql = "", $identificador = "", $texto = "", $primeiro = false)
		{
			$lista = "";
			if((is_string($primeiro))&&(!empty($primeiro)))
				$lista = "\n<option value=\"\">{$primeiro}</option>";
			elseif(is_array($primeiro))
			{
				if(!empty($primeiro['texto']))
				{
					$lista = "\n<option value=\"{$primeiro['valor']}\">{$primeiro['texto']}</option>";# code...
				}
				else
                {
                    foreach ($primeiro as $key=>$value)
                    {
	                    $lista .= "\n<option value=\"{$value['valor']}\">{$value['texto']}</option>";
                    }
                }
			}
			$query = self::GetSqlrows($sql);
			$totalRows = self::NumLinhas($query);
			if(is_numeric($valor))
            {
	            $valor = strval($valor);
            }
			if($totalRows>0)
			{
				if(empty($identificador))
					$identificador = self::NomeCampo($query, 0);
				if(empty($texto))
					$texto = self::NomeCampo($query, 1);
				foreach( $query as $key=>$row )
				{
					$value = $row[$identificador];
					$label = $row[$texto];
					if (strcmp($valor, $value) == 0)
						$selecionado = " selected";
					else
						$selecionado = "";
					$lista .= "\n<option value=\"{$value}\"{$selecionado}>{$label}</option>";
				}
			}
			return $lista;
		}
		################################################################################################################
		public static function GeraChaveValor($sql = "", $identificador = "", $texto = "", $primeiro = false)
		{
			$lista = "";
			if((is_string($primeiro))&&(!empty($primeiro)))
				$lista[$primeiro] = "";
			elseif(is_array($primeiro))
			{
				if(!empty($primeiro['texto']))
				{
					$lista[$primeiro['valor']] = $primeiro['texto'];
				}
				else
					$lista[$primeiro[0]] = $primeiro[1];
			}
			$query = self::GetSqlrows($sql);
			$totalRows = self::NumLinhas($query);
			if($totalRows>0)
			{
				if(empty($identificador))
					$identificador = self::NomeCampo($query, 0);
				if(empty($texto))
					$texto = self::NomeCampo($query, 1);
				foreach( $query as $key=>$row )
				{
					$value = $row[$identificador];
					$label = $row[$texto];
					$lista[$value] = $label;
				}
			}
			return $lista;
		}
		################################################################################################################
		public static function ArrayEnum( $tabela = "", $MembroValor = false, $Filtro = false, $semChave = false)
		{
			
			if(empty($tabela))
				return false;
			
			$sql = 'SHOW FIELDS FROM '.$tabela;
			$query = self::GetSqlrows($sql);
			
			if($query)
			{
				$mess = "";
				if( self::NumLinhas($query) > 0)
				{
					foreach( $query as $key=>$r )
					{
						if(!empty($MembroValor))
						{
							if($r['Field'] == $MembroValor)
							{
								$mess = $r['Type'];
								break;
							}
						}
						elseif(strpos($r['Type'], "enum(") !== false)
						{
							$mess = $r['Type'];
							break;
						}
					}
					if(empty($mess))
						return false;
					$mess = str_replace("enum(","",$mess);
					$mess = str_replace(")","",$mess);
					$mess = str_replace("'","",$mess);
					$aux = explode(",", $mess);
					if(is_array($aux))
					{
						if(is_array($Filtro))
						{
							$aux = array_diff($aux, $Filtro);
						}
						if(empty($semChave))
							return $aux;
						else
						{
							$lista = false;
							foreach ($aux as $key => $value) {
								$lista[$value] = $value;
							}
							return $lista;
						}
					}
				}
			}
			return false;
		}
		################################################################################################################
		public static function GeraOpcoesEnum($valor = "", $tabela = false, $MembroValor = false, $primeiro = false, $Filtro = false)
		{
			if(empty($tabela))
				return "";
			$lista = self::ArrayEnum( $tabela, $MembroValor , $Filtro , true);
			if(empty($lista))
				return "";
			return self::GeraOpcoesArray($valor, $lista, $primeiro);
		}
		################################################################################################################
		public static function GeraOpcoesArray($valor = "", $vetor = false, $primeiro = false)
		{
			$lista = "";
			if(is_string($primeiro))
				$lista = "\n<option value=\"\">{$primeiro}</option>";
			elseif(is_array($primeiro))
			{
				if(!empty($primeiro['texto']))
				{
					$lista = "\n<option value=\"{$primeiro['valor']}\">{$primeiro['texto']}</option>";# code...
				}
				else
					$lista = "\n<option value=\"{$primeiro[0]}\">{$primeiro[1]}</option>";
			}
			$totalRows = count($vetor);
			if($totalRows>0)
			{
				foreach ($vetor as $key => $value)
				{
					if (strcmp($valor, $key) == 0)
						$selecionado = " selected";
					else
						$selecionado = "";
					$lista .= "\n<option value=\"{$key}\"{$selecionado}>{$value}</option>";
				}
			}
			return $lista;
		}
		################################################################################################################
		public static function GeraDatalistSql($valor = "", $sql = "", $identificador = "", $texto = "", $primeiro = false, $label = false)
		{
			$lista = "";
			if((is_string($primeiro))&&(!empty($primeiro)))
				$lista = "\n<option value=\"\">{$primeiro}</option>";
			elseif(is_array($primeiro))
			{
				if(!empty($primeiro['texto']))
				{
					if(!empty($primeiro['label']))
						$lista = "\n<option value=\"{$primeiro['texto']}\" dado=\"{$primeiro['valor']}\">{$primeiro['label']}</option>";
					else
						$lista = "\n<option value=\"{$primeiro['texto']}\" dado=\"{$primeiro['valor']}\">";
				}
				else
					$lista = "\n<option value=\"{$primeiro[1]}\" dado=\"{$primeiro[0]}\">";
			}
			$query = self::GetSqlrows($sql);
			$totalRows = self::NumLinhas($query);
			if($totalRows>0)
			{
				if(empty($identificador))
					$identificador = self::NomeCampo($query, 0);
				if(empty($texto))
					$texto = self::NomeCampo($query, 1);
				foreach( $query as $key=>$row )
				{
					$value = $row[$identificador];
					$auxlabel = $row[$texto];
					if (strcmp($valor, $value) == 0)
						$selecionado = " selected";
					else
						$selecionado = "";
					$lista .= "\n<option value=\"{$auxlabel}\" dado=\"{$value}\"{$selecionado}>";
					if(!empty($label))
					{
						if(!empty($row[$label]))
							$lista .= $row[$label]."<option>";
						else
							$lista .= "</option>";
					}
				}
			}
			return $lista;
		}
		################################################################################################################
		public static function GeraOpcoesEstados($valor = "", $primeiro = false)
		{
			return self::GeraOpcoesArray($valor, self::$Estados, $primeiro);
		}
		################################################################################################################
		public static function GetURL()
		{
			$aURL = array();
			
			// Try to get the request URL
			if (!empty($_SERVER['REQUEST_URI'])) {
				
				$_SERVER['REQUEST_URI'] = str_replace(array('"',"'",'<','>'), array('%22','%27','%3C','%3E'), $_SERVER['REQUEST_URI']);
				$aURL = parse_url($_SERVER['REQUEST_URI']);
			}
			
			// Fill in the empty values
			if (empty($aURL['scheme']))
			{
				if (!empty($_SERVER['HTTP_SCHEME']))
				{
					$aURL['scheme'] = $_SERVER['HTTP_SCHEME'];
				}
				else
				{
					$aURL['scheme'] = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off')? 'https': 'http';
				}
			}
			
			if (empty($aURL['host']))
			{
				if (!empty($_SERVER['HTTP_X_FORWARDED_HOST']))
				{
					if(strpos($_SERVER['HTTP_X_FORWARDED_HOST'], ':') > 0)
					{
						list($aURL['host'], $aURL['port']) = explode(':', $_SERVER['HTTP_X_FORWARDED_HOST']);
					}
					else
					{
						$aURL['host'] = $_SERVER['HTTP_X_FORWARDED_HOST'];
					}
				}
				elseif(!empty($_SERVER['HTTP_HOST']))
				{
					if (strpos($_SERVER['HTTP_HOST'], ':') > 0)
					{
						list($aURL['host'], $aURL['port']) = explode(':', $_SERVER['HTTP_HOST']);
					}
					else
					{
						$aURL['host'] = $_SERVER['HTTP_HOST'];
					}
				}
				elseif(!empty($_SERVER['SERVER_NAME']))
				{
					$aURL['host'] = $_SERVER['SERVER_NAME'];
				}
				else
				{
					return "";
				}
			}
			
			if (empty($aURL['port']) && !empty($_SERVER['SERVER_PORT']))
			{
				$aURL['port'] = $_SERVER['SERVER_PORT'];
			}
			
			if (!empty($aURL['path']))
				if (0 == strlen(basename($aURL['path'])))
					unset($aURL['path']);
			
			if (empty($aURL['path']))
			{
				$sPath = array();
				if (!empty($_SERVER['PATH_INFO']))
				{
					$sPath = parse_url($_SERVER['PATH_INFO']);
				}
				else
				{
					$sPath = parse_url($_SERVER['PHP_SELF']);
				}
				if (isset($sPath['path']))
					$aURL['path'] = str_replace(array('"',"'",'<','>'), array('%22','%27','%3C','%3E'), $sPath['path']);
				unset($sPath);
			}
			
			if (empty($aURL['query']) && !empty($_SERVER['QUERY_STRING']))
			{
				$aURL['query'] = $_SERVER['QUERY_STRING'];
			}
			
			if (!empty($aURL['query']))
			{
				$aURL['query'] = '?'.$aURL['query'];
			}
			
			// Build the URL: Start with scheme, user and pass
			$sURL = $aURL['scheme'].'://';
			if (!empty($aURL['user']))
			{
				$sURL.= $aURL['user'];
				if (!empty($aURL['pass']))
				{
					$sURL.= ':'.$aURL['pass'];
				}
				$sURL.= '@';
			}
			
			// Add the host
			$sURL.= $aURL['host'];
			
			// Add the port if needed
			if (!empty($aURL['port']) && (($aURL['scheme'] == 'http' && $aURL['port'] != 80) || ($aURL['scheme'] == 'https' && $aURL['port'] != 443)))
			{
				$sURL.= ':'.$aURL['port'];
			}
			
			// Add the path and the query string
			$sURL.= $aURL['path'].@$aURL['query'];
			
			// Clean up
			unset($aURL);
			$aURL = explode("?", $sURL);
			
			if (1 < count($aURL))
			{
				$aQueries = explode("&", $aURL[1]);
				foreach ($aQueries as $sKey => $sQuery)
				{
					if ("xjxGenerate" == substr($sQuery, 0, 11))
						unset($aQueries[$sKey]);
				}
				$sQueries = implode("&", $aQueries);
				$aURL[1] = $sQueries;
				$sURL = implode("?", $aURL);
			}
			return $sURL;
		}
		################################################################################################################
		public static function SetURL($caminho = "")
		{
			$barra = substr($caminho, 0,1);
			if($barra != '/')
				$caminho = "/".$caminho;
			$aURL = get_site_url(null, $caminho);
			return $aURL;
		}
		################################################################################################################
		public static function SetDir($caminho = "")
		{
			$barra = substr($caminho, 0,1);
			if($barra == '/')
				$caminho =  substr($caminho, 1);
			$aURL = ABSPATH.$caminho;
			return $aURL;
		}
		################################################################################################################
		public static function SetDirPlugin($caminho = "")
		{
			$barra = substr($caminho, 0,1);
			if($barra == '/')
				$caminho =  substr($caminho, 1);
			$aURL = SIUP_PATH.$caminho;
			return $aURL;
		}
		################################################################################################################
		public static function SetURLPlugin($caminho = "")
		{
			$barra = substr($caminho, 0,1);
			if($barra == '/')
				$caminho =  substr($caminho, 1);
			$aURL = SIUP_URL.$caminho;
			return $aURL;
		}
		################################################################################################################
		public static function UrlJs($caminho = "")
		{
			$barra = substr($caminho, 0,1);
			if($barra == '/')
				$caminho =  substr($caminho, 1);
			$aURL = SIUP_JS_URL.$caminho;
			return $aURL;
		}
		################################################################################################################
		public static function UrlCss($caminho = "")
		{
			$barra = substr($caminho, 0,1);
			if($barra == '/')
				$caminho =  substr($caminho, 1);
			$aURL = SIUP_CSS_URL.$caminho;
			return $aURL;
		}
		################################################################################################################
		public static function UrlVendors($caminho = "")
		{
			$barra = substr($caminho, 0,1);
			if($barra == '/')
				$caminho =  substr($caminho, 1);
			$aURL = SIUP_URL."assets/vendors/{$caminho}";
			return $aURL;
		}
		################################################################################################################
		public static function UrlImages($caminho = "")
		{
			$barra = substr($caminho, 0,1);
			if($barra == '/')
				$caminho =  substr($caminho, 1);
			$aURL = SIUP_IMAGES_URL.$caminho;
			return $aURL;
		}
		################################################################################################################
		public static function CriarPastas($dirName, $rights=0777)
		{
			$dirs = explode('/', $dirName);
			$dir = '';
			foreach ($dirs as $part)
			{
				$dir .= $part.'/';
				if (!is_dir($dir) && strlen($dir) > 0)
					mkdir($dir, $rights);
			}
		}
		################################################################################################################
		public static function AcertaNomeArquivo($nomeAquivo = "")
		{
			if(empty($nomeAquivo))
				return "";
			$nomeAquivo = basename($nomeAquivo);
			$nomeAquivo = self::TiraAcento($nomeAquivo);
			$nomeAquivo = strtolower($nomeAquivo);
			$caracter = array('%','?','!','$','&','~','+','_',' ','#',"\'","[","\\","]","^","]","`",":",";","<","=",">","@","/","(",")","*",'°');
			for($i = 123; $i <= 255; $i++)
				$caracter[] = chr($i);
			$nomeAquivo = str_replace($caracter,"-",$nomeAquivo);
			return $nomeAquivo;
		}
		################################################################################################################
		public static function TiraAcento( $nome = "")
		{
			if( empty( $nome ) )
				return "";
			$nome = str_replace( array('á','à','â','ã','ª'), "a", $nome );
			$nome = str_replace( array('Á','À','Â','Ã'), "A", $nome );
			$nome = str_replace( array('é','è','ê','ë'), "e", $nome );
			$nome = str_replace( array('É','È','Ê','Ë'), "E", $nome );
			$nome = str_replace( array('ó','ò','ô','õ','º'), "o", $nome );
			$nome = str_replace( array('Ó','Ò','Ô','Õ]'), "O", $nome );
			$nome = str_replace( array('ì','í','î','ï'), "i", $nome );
			$nome = str_replace( array('Ì','Í','Î','Ï'), "I", $nome );
			$nome = str_replace( array('ú','ù','û','ü'), "u", $nome );
			$nome = str_replace( array('Ú','Ù','Û','Ü'), "U", $nome );
			$nome = str_replace( 'ç', "c", $nome );
			$nome = str_replace( 'Ç', "C", $nome );
			$nome = trim( $nome );
			return $nome;
		}
		################################################################################################################
		public static function CompletaArray( $atts, $pairs, $limita = false)
		{
			$atts = (array)$atts;
			$out = array();
			if($limita)
			{
				foreach($pairs as $name => $default)
				{
					if(array_key_exists($name, $atts))
						$out[$name] = $atts[$name];
					else
						$out[$name] = $default;
				}
			}
			else
			{
				foreach($atts as $name => $default)
				{
					if((array_key_exists($name, $pairs) === false)&&(isset($pairs[$name])))
						$out[$name] = $pairs[$name];
					else
						$out[$name] = $default;
				}
			}
			return $out;
		}
		################################################################################################################
		public static function Incluir( $tabela = "", $dados = false)
		{
			$resultado = false;
			if(empty($tabela))
				return false;
			$lista = self::MontarCampos($dados);
			if(empty($lista))
				return false;
			$sql = "INSERT INTO {$tabela} SET {$lista}";
			$query = self::GetSql($sql);
			return self::GetUltimoID();
		}
		################################################################################################################
		public static function Update( $tabela = "", $dados = false, $filtro = false, $chave = "")
		{
			$resultado = false;
			if(empty($tabela))
				return false;
			$lista = self::MontarCampos($dados, $chave);
			if(empty($lista))
				return false;
			$onde = self::MontarFiltro($filtro, $tabela, $chave);
			$sql = "UPDATE {$tabela} SET {$lista}{$onde}";
			return self::GetSql($sql);
		}
		################################################################################################################
		public static function MontarFiltro($filtro = false, $tabela = "", $chave = "")
		{
			if(is_array($filtro))
			{
				$onde = "";
				foreach ($filtro as $key => $value)
				{
					$onde .= "AND {$key} = '{$value}'";
				}
				if(!empty($onde))
					$onde = substr($onde, 3);
			}
			elseif(is_numeric($filtro))
			{
				if(empty($chave))
				{
					$sql = "SHOW COLUMNS FROM {$tabela} WHERE COLUMNS.Key = 'PRI';";
					$nomeID = self::GetSqlCampo($sql, "field", "id{$tabela}");
					$onde = "{$nomeID} = '{$filtro}'";
				}
				else
				{
					$onde = "{$chave} = '{$filtro}'";
				}
			}
			elseif(is_string($filtro))
				$onde = $filtro;
			else
				$onde = "";
			if(!empty($onde))
				$onde = " WHERE ".$onde;
			return $onde;
		}
		################################################################################################################
		public static function MontarCampos($dados = false, $chave = "")
		{
			if(empty($dados))
				return false;
			if(is_array($dados))
			{
				$lista = "";
				foreach ($dados as $key => $value)
				{
					if($key == $chave)
						continue;
					$lista .= ", {$key} = '{$value}'";
				}
				if(!empty($lista))
					$lista = substr($lista, 1);
			}
			elseif(is_string($dados))
				$lista = $dados;
			else
				return false;
			return $lista;
		}
		################################################################################################################
		public static function MontarLista($dados = false)
		{
			if(empty($dados))
				return false;
			if(is_array($dados))
			{
				$lista = "";
				foreach ($dados as $key => $value)
				{
					$lista .= ", {$value}";
				}
				if(!empty($lista))
					$lista = substr($lista, 1);
			}
			elseif(is_string($dados))
				$lista = $dados;
			else
				return false;
			return $lista;
		}
		################################################################################################################
		public static function Salvar( $tabela = "", $dados = false, $filtro = false, $chave = "")
		{
			$resultado = false;
			if(empty($filtro))
			{
				$resultado = self::Incluir($tabela, $dados);
			}
			else
			{
				$resultado = self::Update($tabela, $dados, $filtro, $chave);
			}
			return $resultado;
		}
		################################################################################################################
		public static function Excluir( $tabela = "", $filtro = false, $chave = "")
		{
			$resultado = false;
			if(empty($tabela))
				return false;
			$onde = self::MontarFiltro($filtro, $tabela, $chave);
			$sql = "DELETE FROM {$tabela} {$onde}";
			return self::GetSql($sql);
		}
		################################################################################################################
		public static function &GetDefault( $tabela = "")
		{
			$resultado = false;
			if(empty($tabela))
				return $resultado;
			$sql = "SHOW COLUMNS FROM {$tabela}";
			$query = self::GetSqlrows($sql);
			foreach( $query as $key=>$row )
			{
				$chave = $row['Field'];
				$valor = $row['Default'];
				if($row['Key'] == 'PRI')
					$valor = 0;
				$resultado[$chave] = $valor;
			}
			return $resultado;
		}
		################################################################################################################
		public static function GetNonce()
		{
			if (!isset($_SESSION)) {
				@session_start();
			}
			if(empty($_SESSION['NONCE']))
			{
				$agente = $_SERVER["HTTP_USER_AGENT"];
				$pos = strpos($agente, "Chrome");
				if ($pos === false)
					$_SESSION['NONCE'] = md5(uniqid(rand(), true));
				else
					$_SESSION['NONCE'] = md5("7758869568867775");
				
			}
			$nonce = $_SESSION['NONCE'];
			return $nonce;
		}
		################################################################################################################
		public static function CheckNonce($campo = "codigo")
		{
			
			$codigo = self::Request($campo, 0);
			
			if(empty($codigo))
			{
				return false;
			}
			if($codigo != self::GetNonce())
			{
				return false;
			}
			return true;
		}
		################################################################################################################
		public static function Request( $campo = "", $default = "", $decofica = false)
		{
			if($decofica)
			{
				if(is_array($_REQUEST))
				{
					foreach ($_REQUEST as $key=> $valor)
					{
					    if(!is_array($valor))
                        {
	                        $_REQUEST[$key] = str_replace(array("\'",'\"'),array("'",'"'), urldecode($valor)) ;
                        }
					}
				}
			}
			if(empty($campo))
			{
				if(empty($default))
					return $_REQUEST;
				else
				{
					return self::CompletaArray($_REQUEST, $default, true);
				}
			}
			else
			{
				if(!empty($_REQUEST[$campo]))
					return $_REQUEST[$campo];
				else
				{
					$dado = self::Input_Request($campo);
					if(!empty($dado))
						return $dado;
					else
					{
						$dado = self::HTTP_Request($campo);
						if(!empty($dado))
							return $dado;
						else
							return $default;
					}
				}
			}
		}
		#################################################################################################
		public static function GetFiltro($campo = "", $padrao = "")
		{
			if(empty($campo))
				return $padrao;
			$filtro = self::Request("FILTRO", $padrao);
			if(empty($filtro))
				return $padrao;
			$key = array_search($campo, array_column($filtro, 'name'));
			if($key === false)
				return $padrao;
			if(empty($filtro[$key]['value']))
				return $padrao;
			return $filtro[$key]['value'];
		}
		################################################################################################################
		public static function HTTP_Request( $campo = "", $default = "")
		{
			if(empty($HTTP_RAW_POST_DATA))
			{
				return $default;
			}
			else
			{
				if(!empty($HTTP_RAW_POST_DATA[$campo]))
					return $HTTP_RAW_POST_DATA[$campo];
				else
					return $default;
			}
		}
		################################################################################################################
		public static function Input_Request( $campo = "", $default = "")
		{
			$dados = file_get_contents('php://input');
			if(empty($dados))
			{
				return $default;
			}
			else
			{
				$check = substr($dados, 0,1);
				if(($check != "{")&&($check != "["))
				{
					parse_str($dados, $output);
				}
				else
				{
					$output = json_decode($dados);
				}
				if(!empty($output[$campo]))
					return $output[$campo];
				else
					return $default;
			}
		}
		################################################################################################################
		public static function Sessao( $campo = "", $default = "")
		{
			if(empty($campo))
			{
				if(empty($default))
					return $_SESSION;
				else
				{
					return self::CompletaArray($_SESSION, $default, true);
				}
			}
			else
			{
				if(!empty($_SESSION[$campo]))
					return $_SESSION[$campo];
				else
					return $default;
			}
		}
		################################################################################################################
		public static function PrintDados( $infor = "", $ajax = true, $retorno = false)
		{
			if($ajax)
			{
				$dados['sucesso'] = true;
				$dados['dados']	= $infor;
				self::Output($dados);
				return;
			}
			else
			{
				$texto = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>
              <h5>Sucesso!</h5>';
				if(is_array($infor))
				{
					$lista = "";
					foreach ($infor as $key => $value)
					{
						$lista .= "{$value}<br/>";
					}
					$texto .= $lista;
				}
				else
				{
					$texto .= $infor;
				}
				$texto .= '</div>';
			}
			if($retorno)
			{
				return $texto;
			}
			else
			{
				echo $texto;
			}
		}
		################################################################################################################
		public static function SetErros( $erro = "Ocorreu um erro desconhecido")
		{
			self::$erros[] = $erro;
		}
		################################################################################################################
		public static function PrintErros( $ajax = true, $retorno = false, $data = false)
		{
			if($ajax)
			{
				$dados['sucesso'] = false;
				if(!empty(self::$erros))
					$dados['erros']	= self::$erros;
				else
					$dados['erros'][0]	= 'Ocorreu um erro desconhecido';
				if(!empty($data))
					$dados['dados']	= $data;
				self::Output($dados);
				return;
			}
			else
			{
				$erro = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>
              <h5>Erro!</h5>';
				if(!empty(self::$erros))
				{
					$lista = "";
					foreach (self::$erros as $key => $value)
					{
						$lista = "{$value}<br/>".$lista;
					}
					$erro .= $lista;
				}
				else
				{
					$erro .= 'Ocorreu um erro desconhecido.';
				}
				$erro .= '</div>';
			}
			if($retorno)
			{
				return $erro;
			}
			else
			{
				echo $erro;
			}
		}
		################################################################################################################
		public static function Output($result, $http_status = 200)
		{
			$charset = get_option('blog_charset');
			if (!headers_sent())
			{
				status_header($http_status);
				header("Content-Type: application/json; charset={$charset}", true);
			}
			echo json_encode($result);
		}
		################################################################################################################
		public static function RedirecionaErro( $erro = "Ocorreu um erro desconhecido", $redireciona = "/erro.php")
		{
			$link = $redireciona;
			$link = "{$redireciona}?E=".urlencode($erro);
			$link = self::SetURL($link);
			header("Location: {$link}");
		}
		################################################################################################################
		public static function Redireciona($link = "/erro.php", $completo = false)
		{
			if(!$completo)
				$link = self::SetURL($link);
			wp_redirect($link);
			exit;
		}
		################################################################################################################
		public static function TimeData($valor = false)
		{
			if($valor == false)
			{
				return mktime (0, 0, 0, date("m")  , date("d"), date("Y"));
			}
			$valor = str_replace("T"," ",$valor);
			$aux = explode(" ",$valor);
			if(strpos($aux[0],"-") === false)
				list($dia, $mes, $ano) = explode("/", $aux[0]);
			else
				list($ano, $mes, $dia) = explode("-", $aux[0]);
			if( count($aux)>1 )
				$hora = $aux[1];
			else
				$hora = "0:0:0";
			if( ($hora == "00:00:00") || ($hora == "::"))
				$hora = "0:0:0";
			if($hora != "")
			{
				list($horas, $minuto, $segundo) = explode(":", $hora);
			}
			return mktime ($horas, $minuto, $segundo, $mes, $dia, $ano);
		}
		################################################################################################################
		public static function ComparaData($Data1 = false, $Data2 = false)
		{
			$num1 = self::TimeData($Data1);
			$num2 = self::TimeData($Data2);
			
			return $num1 - $num2;
		}
		################################################################################################################
		public static function Data($valor = false, $formato = "d/m/Y")
		{
			if($valor == false)
			{
				return date($formato);
			}
			$aux = date($formato, self::TimeData($valor));
			return $aux;
		}
		################################################################################################################
		public static function DiaAdd($valor = false, $num = 0)
		{
			if($valor == false)
			{
				return mktime (0, 0, 0, date("m")  , date("d") + $num, date("Y"));
			}
			$aux=explode(" ",$valor);
			if(strpos($aux[0],"-") === false)
				list($dia, $mes, $ano) = explode("/", $aux[0]);
			else
				list($ano, $mes, $dia) = explode("-", $aux[0]);
			if(count($aux)>1)
				$hora = $aux[1];
			else
				$hora = "0:0:0";
			if(($hora == "00:00:00")||($hora == "::"))
				$hora = "0:0:0";
			if($hora != "")
			{
				list($horas, $minuto, $segundo) = explode(":", $hora);
			}
			return mktime ($horas, $minuto, $segundo, $mes, $dia + $num, $ano);
		}
		################################################################################################################
		public static function MesAdd($valor = false, $num = 0)
		{
			if($valor == false)
			{
				return mktime (0, 0, 0, date("m") + $num , date("d"), date("Y"));
			}
			$aux = explode(" ",$valor);
			if(strpos($aux[0],"-") === false)
				list($dia, $mes, $ano) = explode("/", $aux[0]);
			else
				list($ano, $mes, $dia) = explode("-", $aux[0]);
			if(count($aux) > 1)
				$hora = $aux[1];
			else
				$hora = "0:0:0";
			if(($hora == "00:00:00")||($hora == "::"))
				$hora = "0:0:0";
			if($hora != "")
			{
				list($horas, $minuto, $segundo) = explode(":", $hora);
			}
			return mktime ($horas, $minuto, $segundo, $mes + $num, $dia, $ano);
		}
		################################################################################################################
		public static function Extensao($path)
		{
			$qpos = strpos($path, "?");
			if ($qpos!==false)
				$path = substr($path, 0, $qpos);
			
			$extension = pathinfo($path, PATHINFO_EXTENSION);
			
			return $extension;
		}
		################################################################################################################
		public static function StrEndereco($valor = false, $mapa = false)
		{
			if(!$valor)
				return("");
			if(!empty($valor['a_endereco']))
				$texto = trim($valor['a_endereco']);
			elseif(!empty($valor['endereco']))
				$texto = trim($valor['endereco']);
			else
				$texto = "";
			if((!empty($valor['estado']))&&(empty($valor['uf'])))
				$valor['uf'] = $valor['estado'];
			if($mapa)
			{
				$blanco = ", ";
				$SeparadorNumero = ", ";
				$SeparadorEstado = ", ";
			}
			else
			{
				$blanco = " ";
				$SeparadorNumero = " N&deg;: ";
				$SeparadorEstado = "-";
			}
			if(!empty($valor['numero']))
			{
			    if(empty($texto))
				    $texto .= "{$valor['numero']},";
			    else
				    $texto .= "{$SeparadorNumero}{$valor['numero']},";
			}
			if(!empty($valor['complemento']))
			{
				if(empty($texto))
					$texto .= "{$valor['complemento']}";
				else
					$texto .= "{$blanco}{$valor['complemento']}";
			}
			if(!empty($valor['bairro']))
			{
				if(empty($texto))
					$texto .= "{$valor['bairro']}";
				else
					$texto .= "{$blanco}{$valor['bairro']}";
			}
			if((!empty($valor['cidade']))&&(!empty($valor['uf'])))
			{
				$texto .= "{$blanco}{$valor['cidade']}{$SeparadorEstado}{$valor['uf']}";
			}
			else
			{
				$texto .= "{$blanco}{$valor['cidade']}{$valor['uf']}";
			}
			if(!empty($valor['sigla']))
			{
				$texto .= "{$blanco}{$valor['sigla']}";
			}
			$texto = trim($texto);
			return($texto);
		}
		################################################################################################################
		public static function Minusculo($texto = "")
		{
			$convertePara = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç");
			$converteDe = array("Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
			$texto = strtolower($texto);
			return str_replace($converteDe, $convertePara, $texto);
		}
		################################################################################################################
		public static function Maiusculo($texto = "")
		{
			$converteDe = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç");
			$convertePara = array("Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
			$texto = strtoupper($texto);
			return str_replace($converteDe, $convertePara, $texto);
		}
		################################################################################################################
		public static function LimpaVar(&$valor = "", $default = "")
		{
			$valor = trim($valor);
			if(($valor == "..")||($valor == "..."))
				$valor = $default;
		}
		################################################################################################################
		public static function FileExiste($file = "")
		{
			$file = trim($file);
			if(empty($file))
				return false;
			if(is_dir($file))
				return false;
			return file_exists($file);
		}
		################################################################################################################
		public static function GetArquivo($file = "", $caminho = "")
		{
			$resultado = "";
			try
			{
				$file = trim($file);
				if(empty($file))
					return $resultado;
				if(empty($caminho))
				{
					$caminho = self::SetDir("/wp-content/uploads/");
				}
				else
					$caminho = self::SetDir($caminho);
				if(!is_dir($caminho))
					return $resultado;
				$file = $caminho.$file;
				
				return self::GetFile($file);
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $resultado;
			}
		}
		################################################################################################################
		public static function GetFile($file = "")
		{
			$resultado = "";
			try
			{
				if(!self::FileExiste($file))
				{
					return $resultado;
				}
				$handle = fopen($file, "r");
				$conteudo = @fread($handle, filesize($file));
				fclose($handle);
				return $conteudo;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $resultado;
			}
		}
		################################################################################################################
		public static function SalvarParaArquivo($conteudo = "", $file = "", $caminho = "")
		{
			$file = trim($file);
			if(empty($file))
				$file = uniqid("file").".txt";
			if(empty($caminho))
			{
				$caminho = self::SetDir("/wp-content/uploads/");
			}
			if(!is_dir($caminho))
				return false;
			$file .= $caminho.$file;
			
			if (!$handle = fopen($file, 'w+'))
			{
				self::$erros = "Erro abrindo arquivo ($file)";
				return false;
			}
			if (!fwrite($handle, $conteudo))
			{
				self::$erros ="Erro escrevendo no arquivo ($file)";
				return false;
			}
			
			fclose($handle);
			return true;
		}
		################################################################################################################
		public static function DiretorioExiste($file = "")
		{
			$file = trim($file);
			if(empty($file))
				return false;
			return is_dir($file);
		}
		################################################################################################################
		public static function CheckHTTPS()
		{
			if(empty($_SERVER['HTTPS']))
			{
				if(!self::is_localhost())
				{
					$url = self::GetURL();
					$url = str_replace("http://", "https://", $url);
					$url = str_replace("https://www.", "https://", $url);
					header("Location: {$url}");
					exit();
				}
			}
			$url = (!empty($_SERVER['HTTP_HOST']))? $_SERVER['HTTP_HOST']: $_SERVER['SSL_TLS_SNI'];
			if(strpos($url, "www.") !== false)
			{
				$url = self::GetURL();
				$url = str_replace("www.", "", $url);
				header("Location: {$url}");
				exit();
			}
		}
		################################################################################################################
		public static function GetUserIP()
		{
			$client  = @$_SERVER['HTTP_CLIENT_IP'];
			$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
			$remote  = $_SERVER['REMOTE_ADDR'];
			
			if(filter_var($client, FILTER_VALIDATE_IP))
			{
				$ip = $client;
			}
			elseif(filter_var($forward, FILTER_VALIDATE_IP))
			{
				$ip = $forward;
			}
			else
			{
				$ip = $remote;
			}
			return $ip;
		}
		################################################################################################################
		public static function is_localhost()
		{
			static $whitelist = null;
			if($whitelist == null)
			{
				$whitelist = array( '127.0.0.1', '::1',$_SERVER['REMOTE_ADDR'],gethostbyname("localhost"));
			}
			if( in_array( self::GetUserIP(), $whitelist) )
				return true;
			return false;
		}
		################################################################################################################
		public static function FormataFone($numero = "")
		{
			$pattern = '/\D/';
			$numero  = preg_replace($pattern, '', $numero);
			$pattern = '/(\d{2})(\d*)(\d{4})/';
			$numero = preg_replace($pattern, '($1) $2-$3', $numero);
			return $numero;
		}
		################################################################################################################
		public static function Escape($inp)
		{
			if(is_array($inp))
				return array_map(__METHOD__, $inp);
			
			if(!empty($inp) && is_string($inp))
			{
				return str_replace(array('\\', "\0", "\n", "\r", "'", "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\Z'), $inp);
			}
			
			return $inp;
		}
		################################################################################################################
		public static function Enviar($url = "", $data = false, $post = true)
		{
			$curl = curl_init();
			$data = (is_array($data)) ? http_build_query($data) : $data;
			// Setup headers - I used the same headers from Firefox version 2.0.0.6
			// below was split up because php.net said the line was too long. :/
			$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
			$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
			$header[] = "Cache-Control: max-age=0";
			$header[] = "Connection: keep-alive";
			$header[] = "Keep-Alive: 300";
			$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
			$header[] = "Accept-Language: en-us,en;q=0.5";
			$header[] = "Content-length: ".strlen($data);
			$header[] = "Pragma: "; // browsers keep this blank.
			
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
			curl_setopt($curl, CURLOPT_REFERER, get_site_url());
			curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
			curl_setopt($curl, CURLOPT_AUTOREFERER, true);
			if($post)
			{
				curl_setopt($curl, CURLOPT_POST, 1 );
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			}
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 10);
			
			$html = curl_exec($curl); // execute the curl command
			curl_close($curl); // close the connection
			
			return $html; // and finally, return $html
		}
		################################################################################################################
		public static function BuscaCep($cep)
		{
			$expressao = '/([^0-9]+)/';
			$cep = preg_replace( $expressao, "", $cep );
			$WebServer = 'http://cep.republicavirtual.com.br/web_cep.php';
			$Resquest = '?cep=' . urlencode( $cep ) . '&formato=xml';
			$WebServer .= $Resquest;
			$xml = simplexml_load_file( $WebServer );
			$dados = self::objectToArray($xml);
			$resultado = strval($dados['resultado']);
			if($resultado < 1)
			{
				return false;
			}
			$resultado = array();
			foreach( $dados as $elemento => $valor )
			{
				$resultado[$elemento] = $valor;
				if($elemento == "uf")
					$resultado['estado'] = $valor;
				if($elemento == "logradouro")
					$resultado['endereco'] = $dados['tipo_logradouro'] ." ". $valor;
			}
			return $resultado;
		}
		################################################################################################################
		public static function GetIcones()
		{
			$icones = array('fa fa-glass',
				'fa fa-music',
				'fa fa-search',
				'fa fa-envelope-o',
				'fa fa-heart',
				'fa fa-star',
				'fa fa-star-o',
				'fa fa-user',
				'fa fa-film',
				'fa fa-th-large',
				'fa fa-th',
				'fa fa-th-list',
				'fa fa-check',
				'fa fa-remove',
				'fa fa-close',
				'fa fa-times',
				'fa fa-search-plus',
				'fa fa-search-minus',
				'fa fa-power-off',
				'fa fa-signal',
				'fa fa-gear',
				'fa fa-cog',
				'fa fa-trash-o',
				'fa fa-home',
				'fa fa-file-o',
				'fa fa-clock-o',
				'fa fa-road',
				'fa fa-download',
				'fa fa-arrow-circle-o-down',
				'fa fa-arrow-circle-o-up',
				'fa fa-inbox',
				'fa fa-play-circle-o',
				'fa fa-rotate-right',
				'fa fa-repeat',
				'fa fa-refresh',
				'fa fa-list-alt',
				'fa fa-lock',
				'fa fa-flag',
				'fa fa-headphones',
				'fa fa-volume-off',
				'fa fa-volume-down',
				'fa fa-volume-up',
				'fa fa-qrcode',
				'fa fa-barcode',
				'fa fa-tag',
				'fa fa-tags',
				'fa fa-book',
				'fa fa-bookmark',
				'fa fa-print',
				'fa fa-camera',
				'fa fa-font',
				'fa fa-bold',
				'fa fa-italic',
				'fa fa-text-height',
				'fa fa-text-width',
				'fa fa-align-left',
				'fa fa-align-center',
				'fa fa-align-right',
				'fa fa-align-justify',
				'fa fa-list',
				'fa fa-dedent',
				'fa fa-outdent',
				'fa fa-indent',
				'fa fa-video-camera',
				'fa fa-photo',
				'fa fa-image',
				'fa fa-picture-o',
				'fa fa-pencil',
				'fa fa-map-marker',
				'fa fa-adjust',
				'fa fa-tint',
				'fa fa-edit',
				'fa fa-pencil-square-o',
				'fa fa-share-square-o',
				'fa fa-check-square-o',
				'fa fa-arrows',
				'fa fa-step-backward',
				'fa fa-fast-backward',
				'fa fa-backward',
				'fa fa-play',
				'fa fa-pause',
				'fa fa-stop',
				'fa fa-forward',
				'fa fa-fast-forward',
				'fa fa-step-forward',
				'fa fa-eject',
				'fa fa-chevron-left',
				'fa fa-chevron-right',
				'fa fa-plus-circle',
				'fa fa-minus-circle',
				'fa fa-times-circle',
				'fa fa-check-circle',
				'fa fa-question-circle',
				'fa fa-info-circle',
				'fa fa-crosshairs',
				'fa fa-times-circle-o',
				'fa fa-check-circle-o',
				'fa fa-ban',
				'fa fa-arrow-left',
				'fa fa-arrow-right',
				'fa fa-arrow-up',
				'fa fa-arrow-down',
				'fa fa-mail-forward',
				'fa fa-share',
				'fa fa-expand',
				'fa fa-compress',
				'fa fa-plus',
				'fa fa-minus',
				'fa fa-asterisk',
				'fa fa-exclamation-circle',
				'fa fa-gift',
				'fa fa-leaf',
				'fa fa-fire',
				'fa fa-eye',
				'fa fa-eye-slash',
				'fa fa-warning',
				'fa fa-exclamation-triangle',
				'fa fa-plane',
				'fa fa-calendar',
				'fa fa-random',
				'fa fa-comment',
				'fa fa-magnet',
				'fa fa-chevron-up',
				'fa fa-chevron-down',
				'fa fa-retweet',
				'fa fa-shopping-cart',
				'fa fa-folder',
				'fa fa-folder-open',
				'fa fa-arrows-v',
				'fa fa-arrows-h',
				'fa fa-bar-chart-o',
				'fa fa-bar-chart',
				'fa fa-twitter-square',
				'fa fa-facebook-square',
				'fa fa-camera-retro',
				'fa fa-key',
				'fa fa-gears',
				'fa fa-cogs',
				'fa fa-comments',
				'fa fa-thumbs-o-up',
				'fa fa-thumbs-o-down',
				'fa fa-star-half',
				'fa fa-heart-o',
				'fa fa-sign-out',
				'fa fa-linkedin-square',
				'fa fa-thumb-tack',
				'fa fa-external-link',
				'fa fa-sign-in',
				'fa fa-trophy',
				'fa fa-github-square',
				'fa fa-upload',
				'fa fa-lemon-o',
				'fa fa-phone',
				'fa fa-square-o',
				'fa fa-bookmark-o',
				'fa fa-phone-square',
				'fa fa-twitter',
				'fa fa-facebook-f',
				'fa fa-facebook',
				'fa fa-github',
				'fa fa-unlock',
				'fa fa-credit-card',
				'fa fa-feed',
				'fa fa-rss',
				'fa fa-hdd-o',
				'fa fa-bullhorn',
				'fa fa-bell',
				'fa fa-certificate',
				'fa fa-hand-o-right',
				'fa fa-hand-o-left',
				'fa fa-hand-o-up',
				'fa fa-hand-o-down',
				'fa fa-arrow-circle-left',
				'fa fa-arrow-circle-right',
				'fa fa-arrow-circle-up',
				'fa fa-arrow-circle-down',
				'fa fa-globe',
				'fa fa-wrench',
				'fa fa-tasks',
				'fa fa-filter',
				'fa fa-briefcase',
				'fa fa-arrows-alt',
				'fa fa-group',
				'fa fa-users',
				'fa fa-chain',
				'fa fa-link',
				'fa fa-cloud',
				'fa fa-flask',
				'fa fa-cut',
				'fa fa-scissors',
				'fa fa-copy',
				'fa fa-files-o',
				'fa fa-paperclip',
				'fa fa-save',
				'fa fa-floppy-o',
				'fa fa-square',
				'fa fa-navicon',
				'fa fa-reorder',
				'fa fa-bars',
				'fa fa-list-ul',
				'fa fa-list-ol',
				'fa fa-strikethrough',
				'fa fa-underline',
				'fa fa-table',
				'fa fa-magic',
				'fa fa-truck',
				'fa fa-pinterest',
				'fa fa-pinterest-square',
				'fa fa-google-plus-square',
				'fa fa-google-plus',
				'fa fa-money',
				'fa fa-caret-down',
				'fa fa-caret-up',
				'fa fa-caret-left',
				'fa fa-caret-right',
				'fa fa-columns',
				'fa fa-unsorted',
				'fa fa-sort',
				'fa fa-sort-down',
				'fa fa-sort-desc',
				'fa fa-sort-up',
				'fa fa-sort-asc',
				'fa fa-envelope',
				'fa fa-linkedin',
				'fa fa-rotate-left',
				'fa fa-undo',
				'fa fa-legal',
				'fa fa-gavel',
				'fa fa-dashboard',
				'fa fa-tachometer',
				'fa fa-comment-o',
				'fa fa-comments-o',
				'fa fa-flash',
				'fa fa-bolt',
				'fa fa-sitemap',
				'fa fa-umbrella',
				'fa fa-paste',
				'fa fa-clipboard',
				'fa fa-lightbulb-o',
				'fa fa-exchange',
				'fa fa-cloud-download',
				'fa fa-cloud-upload',
				'fa fa-user-md',
				'fa fa-stethoscope',
				'fa fa-suitcase',
				'fa fa-bell-o',
				'fa fa-coffee',
				'fa fa-cutlery',
				'fa fa-file-text-o',
				'fa fa-building-o',
				'fa fa-hospital-o',
				'fa fa-ambulance',
				'fa fa-medkit',
				'fa fa-fighter-jet',
				'fa fa-beer',
				'fa fa-h-square',
				'fa fa-plus-square',
				'fa fa-angle-double-left',
				'fa fa-angle-double-right',
				'fa fa-angle-double-up',
				'fa fa-angle-double-down',
				'fa fa-angle-left',
				'fa fa-angle-right',
				'fa fa-angle-up',
				'fa fa-angle-down',
				'fa fa-desktop',
				'fa fa-laptop',
				'fa fa-tablet',
				'fa fa-mobile-phone',
				'fa fa-mobile',
				'fa fa-circle-o',
				'fa fa-quote-left',
				'fa fa-quote-right',
				'fa fa-spinner',
				'fa fa-circle',
				'fa fa-mail-reply',
				'fa fa-reply',
				'fa fa-github-alt',
				'fa fa-folder-o',
				'fa fa-folder-open-o',
				'fa fa-smile-o',
				'fa fa-frown-o',
				'fa fa-meh-o',
				'fa fa-gamepad',
				'fa fa-keyboard-o',
				'fa fa-flag-o',
				'fa fa-flag-checkered',
				'fa fa-terminal',
				'fa fa-code',
				'fa fa-mail-reply-all',
				'fa fa-reply-all',
				'fa fa-star-half-empty',
				'fa fa-star-half-full',
				'fa fa-star-half-o',
				'fa fa-location-arrow',
				'fa fa-crop',
				'fa fa-code-fork',
				'fa fa-unlink',
				'fa fa-chain-broken',
				'fa fa-question',
				'fa fa-info',
				'fa fa-exclamation',
				'fa fa-superscript',
				'fa fa-subscript',
				'fa fa-eraser',
				'fa fa-puzzle-piece',
				'fa fa-microphone',
				'fa fa-microphone-slash',
				'fa fa-shield',
				'fa fa-calendar-o',
				'fa fa-fire-extinguisher',
				'fa fa-rocket',
				'fa fa-maxcdn',
				'fa fa-chevron-circle-left',
				'fa fa-chevron-circle-right',
				'fa fa-chevron-circle-up',
				'fa fa-chevron-circle-down',
				'fa fa-html5',
				'fa fa-css3',
				'fa fa-anchor',
				'fa fa-unlock-alt',
				'fa fa-bullseye',
				'fa fa-ellipsis-h',
				'fa fa-ellipsis-v',
				'fa fa-rss-square',
				'fa fa-play-circle',
				'fa fa-ticket',
				'fa fa-minus-square',
				'fa fa-minus-square-o',
				'fa fa-level-up',
				'fa fa-level-down',
				'fa fa-check-square',
				'fa fa-pencil-square',
				'fa fa-external-link-square',
				'fa fa-share-square',
				'fa fa-compass',
				'fa fa-toggle-down',
				'fa fa-caret-square-o-down',
				'fa fa-toggle-up',
				'fa fa-caret-square-o-up',
				'fa fa-toggle-right',
				'fa fa-caret-square-o-right',
				'fa fa-euro',
				'fa fa-eur',
				'fa fa-gbp',
				'fa fa-dollar',
				'fa fa-usd',
				'fa fa-rupee',
				'fa fa-inr',
				'fa fa-cny',
				'fa fa-rmb',
				'fa fa-yen',
				'fa fa-jpy',
				'fa fa-ruble',
				'fa fa-rouble',
				'fa fa-rub',
				'fa fa-won',
				'fa fa-krw',
				'fa fa-bitcoin',
				'fa fa-btc',
				'fa fa-file',
				'fa fa-file-text',
				'fa fa-sort-alpha-asc',
				'fa fa-sort-alpha-desc',
				'fa fa-sort-amount-asc',
				'fa fa-sort-amount-desc',
				'fa fa-sort-numeric-asc',
				'fa fa-sort-numeric-desc',
				'fa fa-thumbs-up',
				'fa fa-thumbs-down',
				'fa fa-youtube-square',
				'fa fa-youtube',
				'fa fa-xing',
				'fa fa-xing-square',
				'fa fa-youtube-play',
				'fa fa-dropbox',
				'fa fa-stack-overflow',
				'fa fa-instagram',
				'fa fa-flickr',
				'fa fa-adn',
				'fa fa-bitbucket',
				'fa fa-bitbucket-square',
				'fa fa-tumblr',
				'fa fa-tumblr-square',
				'fa fa-long-arrow-down',
				'fa fa-long-arrow-up',
				'fa fa-long-arrow-left',
				'fa fa-long-arrow-right',
				'fa fa-apple',
				'fa fa-windows',
				'fa fa-android',
				'fa fa-linux',
				'fa fa-dribbble',
				'fa fa-skype',
				'fa fa-foursquare',
				'fa fa-trello',
				'fa fa-female',
				'fa fa-male',
				'fa fa-gittip',
				'fa fa-gratipay',
				'fa fa-sun-o',
				'fa fa-moon-o',
				'fa fa-archive',
				'fa fa-bug',
				'fa fa-vk',
				'fa fa-weibo',
				'fa fa-renren',
				'fa fa-pagelines',
				'fa fa-stack-exchange',
				'fa fa-arrow-circle-o-right',
				'fa fa-arrow-circle-o-left',
				'fa fa-toggle-left',
				'fa fa-caret-square-o-left',
				'fa fa-dot-circle-o',
				'fa fa-wheelchair',
				'fa fa-vimeo-square',
				'fa fa-turkish-lira',
				'fa fa-try',
				'fa fa-plus-square-o',
				'fa fa-space-shuttle',
				'fa fa-slack',
				'fa fa-envelope-square',
				'fa fa-wordpress',
				'fa fa-openid',
				'fa fa-institution',
				'fa fa-bank',
				'fa fa-university',
				'fa fa-mortar-board',
				'fa fa-graduation-cap',
				'fa fa-yahoo',
				'fa fa-google',
				'fa fa-reddit',
				'fa fa-reddit-square',
				'fa fa-stumbleupon-circle',
				'fa fa-stumbleupon',
				'fa fa-delicious',
				'fa fa-digg',
				'fa fa-pied-piper-pp',
				'fa fa-pied-piper-alt',
				'fa fa-drupal',
				'fa fa-joomla',
				'fa fa-language',
				'fa fa-fax',
				'fa fa-building',
				'fa fa-child',
				'fa fa-paw',
				'fa fa-spoon',
				'fa fa-cube',
				'fa fa-cubes',
				'fa fa-behance',
				'fa fa-behance-square',
				'fa fa-steam',
				'fa fa-steam-square',
				'fa fa-recycle',
				'fa fa-automobile',
				'fa fa-car',
				'fa fa-cab',
				'fa fa-taxi',
				'fa fa-tree',
				'fa fa-spotify',
				'fa fa-deviantart',
				'fa fa-soundcloud',
				'fa fa-database',
				'fa fa-file-pdf-o',
				'fa fa-file-word-o',
				'fa fa-file-excel-o',
				'fa fa-file-powerpoint-o',
				'fa fa-file-photo-o',
				'fa fa-file-picture-o',
				'fa fa-file-image-o',
				'fa fa-file-zip-o',
				'fa fa-file-archive-o',
				'fa fa-file-sound-o',
				'fa fa-file-audio-o',
				'fa fa-file-movie-o',
				'fa fa-file-video-o',
				'fa fa-file-code-o',
				'fa fa-vine',
				'fa fa-codepen',
				'fa fa-jsfiddle',
				'fa fa-life-bouy',
				'fa fa-life-buoy',
				'fa fa-life-saver',
				'fa fa-support',
				'fa fa-life-ring',
				'fa fa-circle-o-notch',
				'fa fa-ra',
				'fa fa-resistance',
				'fa fa-rebel',
				'fa fa-ge',
				'fa fa-empire',
				'fa fa-git-square',
				'fa fa-git',
				'fa fa-y-combinator-square',
				'fa fa-yc-square',
				'fa fa-hacker-news',
				'fa fa-tencent-weibo',
				'fa fa-qq',
				'fa fa-wechat',
				'fa fa-weixin',
				'fa fa-send',
				'fa fa-paper-plane',
				'fa fa-send-o',
				'fa fa-paper-plane-o',
				'fa fa-history',
				'fa fa-circle-thin',
				'fa fa-header',
				'fa fa-paragraph',
				'fa fa-sliders',
				'fa fa-share-alt',
				'fa fa-share-alt-square',
				'fa fa-bomb',
				'fa fa-soccer-ball-o',
				'fa fa-futbol-o',
				'fa fa-tty',
				'fa fa-binoculars',
				'fa fa-plug',
				'fa fa-slideshare',
				'fa fa-twitch',
				'fa fa-yelp',
				'fa fa-newspaper-o',
				'fa fa-wifi',
				'fa fa-calculator',
				'fa fa-paypal',
				'fa fa-google-wallet',
				'fa fa-cc-visa',
				'fa fa-cc-mastercard',
				'fa fa-cc-discover',
				'fa fa-cc-amex',
				'fa fa-cc-paypal',
				'fa fa-cc-stripe',
				'fa fa-bell-slash',
				'fa fa-bell-slash-o',
				'fa fa-trash',
				'fa fa-copyright',
				'fa fa-at',
				'fa fa-eyedropper',
				'fa fa-paint-brush',
				'fa fa-birthday-cake',
				'fa fa-area-chart',
				'fa fa-pie-chart',
				'fa fa-line-chart',
				'fa fa-lastfm',
				'fa fa-lastfm-square',
				'fa fa-toggle-off',
				'fa fa-toggle-on',
				'fa fa-bicycle',
				'fa fa-bus',
				'fa fa-ioxhost',
				'fa fa-angellist',
				'fa fa-cc',
				'fa fa-shekel',
				'fa fa-sheqel',
				'fa fa-ils',
				'fa fa-meanpath',
				'fa fa-buysellads',
				'fa fa-connectdevelop',
				'fa fa-dashcube',
				'fa fa-forumbee',
				'fa fa-leanpub',
				'fa fa-sellsy',
				'fa fa-shirtsinbulk',
				'fa fa-simplybuilt',
				'fa fa-skyatlas',
				'fa fa-cart-plus',
				'fa fa-cart-arrow-down',
				'fa fa-diamond',
				'fa fa-ship',
				'fa fa-user-secret',
				'fa fa-motorcycle',
				'fa fa-street-view',
				'fa fa-heartbeat',
				'fa fa-venus',
				'fa fa-mars',
				'fa fa-mercury',
				'fa fa-intersex',
				'fa fa-transgender',
				'fa fa-transgender-alt',
				'fa fa-venus-double',
				'fa fa-mars-double',
				'fa fa-venus-mars',
				'fa fa-mars-stroke',
				'fa fa-mars-stroke-v',
				'fa fa-mars-stroke-h',
				'fa fa-neuter',
				'fa fa-genderless',
				'fa fa-facebook-official',
				'fa fa-pinterest-p',
				'fa fa-whatsapp',
				'fa fa-server',
				'fa fa-user-plus',
				'fa fa-user-times',
				'fa fa-hotel',
				'fa fa-bed',
				'fa fa-viacoin',
				'fa fa-train',
				'fa fa-subway',
				'fa fa-medium',
				'fa fa-yc',
				'fa fa-y-combinator',
				'fa fa-optin-monster',
				'fa fa-opencart',
				'fa fa-expeditedssl',
				'fa fa-battery-4',
				'fa fa-battery',
				'fa fa-battery-full',
				'fa fa-battery-3',
				'fa fa-battery-three-quarters',
				'fa fa-battery-2',
				'fa fa-battery-half',
				'fa fa-battery-1',
				'fa fa-battery-quarter',
				'fa fa-battery-0',
				'fa fa-battery-empty',
				'fa fa-mouse-pointer',
				'fa fa-i-cursor',
				'fa fa-object-group',
				'fa fa-object-ungroup',
				'fa fa-sticky-note',
				'fa fa-sticky-note-o',
				'fa fa-cc-jcb',
				'fa fa-cc-diners-club',
				'fa fa-clone',
				'fa fa-balance-scale',
				'fa fa-hourglass-o',
				'fa fa-hourglass-1',
				'fa fa-hourglass-start',
				'fa fa-hourglass-2',
				'fa fa-hourglass-half',
				'fa fa-hourglass-3',
				'fa fa-hourglass-end',
				'fa fa-hourglass',
				'fa fa-hand-grab-o',
				'fa fa-hand-rock-o',
				'fa fa-hand-stop-o',
				'fa fa-hand-paper-o',
				'fa fa-hand-scissors-o',
				'fa fa-hand-lizard-o',
				'fa fa-hand-spock-o',
				'fa fa-hand-pointer-o',
				'fa fa-hand-peace-o',
				'fa fa-trademark',
				'fa fa-registered',
				'fa fa-creative-commons',
				'fa fa-gg',
				'fa fa-gg-circle',
				'fa fa-tripadvisor',
				'fa fa-odnoklassniki',
				'fa fa-odnoklassniki-square',
				'fa fa-get-pocket',
				'fa fa-wikipedia-w',
				'fa fa-safari',
				'fa fa-chrome',
				'fa fa-firefox',
				'fa fa-opera',
				'fa fa-internet-explorer',
				'fa fa-tv',
				'fa fa-television',
				'fa fa-contao',
				'fa fa-500px',
				'fa fa-amazon',
				'fa fa-calendar-plus-o',
				'fa fa-calendar-minus-o',
				'fa fa-calendar-times-o',
				'fa fa-calendar-check-o',
				'fa fa-industry',
				'fa fa-map-pin',
				'fa fa-map-signs',
				'fa fa-map-o',
				'fa fa-map',
				'fa fa-commenting',
				'fa fa-commenting-o',
				'fa fa-houzz',
				'fa fa-vimeo',
				'fa fa-black-tie',
				'fa fa-fonticons',
				'fa fa-reddit-alien',
				'fa fa-edge',
				'fa fa-credit-card-alt',
				'fa fa-codiepie',
				'fa fa-modx',
				'fa fa-fort-awesome',
				'fa fa-usb',
				'fa fa-product-hunt',
				'fa fa-mixcloud',
				'fa fa-scribd',
				'fa fa-pause-circle',
				'fa fa-pause-circle-o',
				'fa fa-stop-circle',
				'fa fa-stop-circle-o',
				'fa fa-shopping-bag',
				'fa fa-shopping-basket',
				'fa fa-hashtag',
				'fa fa-bluetooth',
				'fa fa-bluetooth-b',
				'fa fa-percent',
				'fa fa-gitlab',
				'fa fa-wpbeginner',
				'fa fa-wpforms',
				'fa fa-envira',
				'fa fa-universal-access',
				'fa fa-wheelchair-alt',
				'fa fa-question-circle-o',
				'fa fa-blind',
				'fa fa-audio-description',
				'fa fa-volume-control-phone',
				'fa fa-braille',
				'fa fa-assistive-listening-systems',
				'fa fa-asl-interpreting',
				'fa fa-american-sign-language-interpreting',
				'fa fa-deafness',
				'fa fa-hard-of-hearing',
				'fa fa-deaf',
				'fa fa-glide',
				'fa fa-glide-g',
				'fa fa-signing',
				'fa fa-sign-language',
				'fa fa-low-vision',
				'fa fa-viadeo',
				'fa fa-viadeo-square',
				'fa fa-snapchat',
				'fa fa-snapchat-ghost',
				'fa fa-snapchat-square',
				'fa fa-pied-piper',
				'fa fa-first-order',
				'fa fa-yoast',
				'fa fa-themeisle',
				'fa fa-google-plus-circle',
				'fa fa-google-plus-official',
				'fa fa-fa',
				'fa fa-font-awesome',
				'fa fa-handshake-o',
				'fa fa-envelope-open',
				'fa fa-envelope-open-o',
				'fa fa-linode',
				'fa fa-address-book',
				'fa fa-address-book-o',
				'fa fa-vcard',
				'fa fa-address-card',
				'fa fa-vcard-o',
				'fa fa-address-card-o',
				'fa fa-user-circle',
				'fa fa-user-circle-o',
				'fa fa-user-o',
				'fa fa-id-badge',
				'fa fa-drivers-license',
				'fa fa-id-card',
				'fa fa-drivers-license-o',
				'fa fa-id-card-o',
				'fa fa-quora',
				'fa fa-free-code-camp',
				'fa fa-telegram',
				'fa fa-thermometer-4',
				'fa fa-thermometer',
				'fa fa-thermometer-full',
				'fa fa-thermometer-3',
				'fa fa-thermometer-three-quarters',
				'fa fa-thermometer-2',
				'fa fa-thermometer-half',
				'fa fa-thermometer-1',
				'fa fa-thermometer-quarter',
				'fa fa-thermometer-0',
				'fa fa-thermometer-empty',
				'fa fa-shower',
				'fa fa-bathtub',
				'fa fa-s15',
				'fa fa-bath',
				'fa fa-podcast',
				'fa fa-window-maximize',
				'fa fa-window-minimize',
				'fa fa-window-restore',
				'fa fa-times-rectangle',
				'fa fa-window-close',
				'fa fa-times-rectangle-o',
				'fa fa-window-close-o',
				'fa fa-bandcamp',
				'fa fa-grav',
				'fa fa-etsy',
				'fa fa-imdb',
				'fa fa-ravelry',
				'fa fa-eercast',
				'fa fa-microchip',
				'fa fa-snowflake-o',
				'fa fa-superpowers',
				'fa fa-wpexplorer',
				'fa fa-meetup',
				'el el-address-book-alt',
				'el el-address-book',
				'el el-adjust-alt',
				'el el-adjust',
				'el el-adult',
				'el el-align-center',
				'el el-align-justify',
				'el el-align-left',
				'el el-align-right',
				'el el-arrow-down',
				'el el-arrow-left',
				'el el-arrow-right',
				'el el-arrow-up',
				'el el-asl',
				'el el-asterisk',
				'el el-backward',
				'el el-ban-circle',
				'el el-barcode',
				'el el-behance',
				'el el-bell',
				'el el-blind',
				'el el-blogger',
				'el el-bold',
				'el el-book',
				'el el-bookmark-empty',
				'el el-bookmark',
				'el el-braille',
				'el el-briefcase',
				'el el-broom',
				'el el-brush',
				'el el-bulb',
				'el el-bullhorn',
				'el el-calendar-sign',
				'el el-calendar',
				'el el-camera',
				'el el-car',
				'el el-caret-down',
				'el el-caret-left',
				'el el-caret-right',
				'el el-caret-up',
				'el el-cc',
				'el el-certificate',
				'el el-check-empty',
				'el el-check',
				'el el-chevron-down',
				'el el-chevron-left',
				'el el-chevron-right',
				'el el-chevron-up',
				'el el-child',
				'el el-circle-arrow-down',
				'el el-circle-arrow-left',
				'el el-circle-arrow-right',
				'el el-circle-arrow-up',
				'el el-cloud-alt',
				'el el-cloud',
				'el el-cog-alt',
				'el el-cog',
				'el el-cogs',
				'el el-comment-alt',
				'el el-comment',
				'el el-compass-alt',
				'el el-compass',
				'el el-credit-card',
				'el el-css',
				'el el-dashboard',
				'el el-delicious',
				'el el-deviantart',
				'el el-digg',
				'el el-download-alt',
				'el el-download',
				'el el-dribbble',
				'el el-edit',
				'el el-eject',
				'el el-envelope-alt',
				'el el-envelope',
				'el el-error-alt',
				'el el-error',
				'el el-eur',
				'el el-exclamation-sign',
				'el el-eye-close',
				'el el-eye-open',
				'el el-facebook',
				'el el-facetime-video',
				'el el-fast-backward',
				'el el-fast-forward',
				'el el-female',
				'el el-file-alt',
				'el el-file-edit-alt',
				'el el-file-edit',
				'el el-file-new-alt',
				'el el-file-new',
				'el el-file',
				'el el-film',
				'el el-filter',
				'el el-fire',
				'el el-flag-alt',
				'el el-flag',
				'el el-flickr',
				'el el-folder-close',
				'el el-folder-open',
				'el el-folder-sign',
				'el el-folder',
				'el el-font',
				'el el-fontsize',
				'el el-fork',
				'el el-forward-alt',
				'el el-forward',
				'el el-foursquare',
				'el el-friendfeed-rect',
				'el el-friendfeed',
				'el el-fullscreen',
				'el el-gbp',
				'el el-gift',
				'el el-github-text',
				'el el-github',
				'el el-glass',
				'el el-glasses',
				'el el-globe-alt',
				'el el-globe',
				'el el-googleplus',
				'el el-graph-alt',
				'el el-graph',
				'el el-group-alt',
				'el el-group',
				'el el-guidedog',
				'el el-hand-down',
				'el el-hand-left',
				'el el-hand-right',
				'el el-hand-up',
				'el el-hdd',
				'el el-headphones',
				'el el-hearing-impaired',
				'el el-heart-alt',
				'el el-heart-empty',
				'el el-heart',
				'el el-home-alt',
				'el el-home',
				'el el-hourglass',
				'el el-idea-alt',
				'el el-idea',
				'el el-inbox-alt',
				'el el-inbox-box',
				'el el-inbox',
				'el el-indent-left',
				'el el-indent-right',
				'el el-info-circle',
				'el el-instagram',
				'el el-iphone-home',
				'el el-italic',
				'el el-key',
				'el el-laptop-alt',
				'el el-laptop',
				'el el-lastfm',
				'el el-leaf',
				'el el-lines',
				'el el-link',
				'el el-linkedin',
				'el el-list-alt',
				'el el-list',
				'el el-livejournal',
				'el el-lock-alt',
				'el el-lock',
				'el el-magic',
				'el el-magnet',
				'el el-male',
				'el el-map-marker-alt',
				'el el-map-marker',
				'el el-mic-alt',
				'el el-mic',
				'el el-minus-sign',
				'el el-minus',
				'el el-move',
				'el el-music',
				'el el-myspace',
				'el el-network',
				'el el-off',
				'el el-ok-circle',
				'el el-ok-sign',
				'el el-ok',
				'el el-opensource',
				'el el-paper-clip-alt',
				'el el-paper-clip',
				'el el-path',
				'el el-pause-alt',
				'el el-pause',
				'el el-pencil-alt',
				'el el-pencil',
				'el el-person',
				'el el-phone-alt',
				'el el-phone',
				'el el-photo-alt',
				'el el-photo',
				'el el-picasa',
				'el el-picture',
				'el el-pinterest',
				'el el-plane',
				'el el-play-alt',
				'el el-play-circle',
				'el el-play',
				'el el-plurk-alt',
				'el el-plurk',
				'el el-plus-sign',
				'el el-plus',
				'el el-podcast',
				'el el-print',
				'el el-puzzle',
				'el el-qrcode',
				'el el-question-sign',
				'el el-question',
				'el el-quote-alt',
				'el el-quote-right-alt',
				'el el-quote-right',
				'el el-quotes',
				'el el-random',
				'el el-record',
				'el el-reddit',
				'el el-redux',
				'el el-refresh',
				'el el-remove-circle',
				'el el-remove-sign',
				'el el-remove',
				'el el-repeat-alt',
				'el el-repeat',
				'el el-resize-full',
				'el el-resize-horizontal',
				'el el-resize-small',
				'el el-resize-vertical',
				'el el-return-key',
				'el el-retweet',
				'el el-reverse-alt',
				'el el-road',
				'el el-rss',
				'el el-scissors',
				'el el-screen-alt',
				'el el-screen',
				'el el-screenshot',
				'el el-search-alt',
				'el el-search',
				'el el-share-alt',
				'el el-share',
				'el el-shopping-cart-sign',
				'el el-shopping-cart',
				'el el-signal',
				'el el-skype',
				'el el-slideshare',
				'el el-smiley-alt',
				'el el-smiley',
				'el el-soundcloud',
				'el el-speaker',
				'el el-spotify',
				'el el-stackoverflow',
				'el el-star-alt',
				'el el-star-empty',
				'el el-star',
				'el el-step-backward',
				'el el-step-forward',
				'el el-stop-alt',
				'el el-stop',
				'el el-stumbleupon',
				'el el-tag',
				'el el-tags',
				'el el-tasks',
				'el el-text-height',
				'el el-text-width',
				'el el-th-large',
				'el el-th-list',
				'el el-th',
				'el el-thumbs-down',
				'el el-thumbs-up',
				'el el-time-alt',
				'el el-time',
				'el el-tint',
				'el el-torso',
				'el el-trash-alt',
				'el el-trash',
				'el el-tumblr',
				'el el-twitter',
				'el el-universal-access',
				'el el-unlock-alt',
				'el el-unlock',
				'el el-upload',
				'el el-usd',
				'el el-user',
				'el el-viadeo',
				'el el-video-alt',
				'el el-video-chat',
				'el el-video',
				'el el-view-mode',
				'el el-vimeo',
				'el el-vkontakte',
				'el el-volume-down',
				'el el-volume-off',
				'el el-volume-up',
				'el el-w3c',
				'el el-warning-sign',
				'el el-website-alt',
				'el el-website',
				'el el-wheelchair',
				'el el-wordpress',
				'el el-wrench-alt',
				'el el-wrench',
				'el el-youtube',
				'el el-zoom-in',
				'el el-zoom-out',
				'ion ion-alert',
				'ion ion-alert-circled',
				'ion ion-android-add',
				'ion ion-android-add-contact',
				'ion ion-android-alarm',
				'ion ion-android-archive',
				'ion ion-android-arrow-back',
				'ion ion-android-arrow-down-left',
				'ion ion-android-arrow-down-right',
				'ion ion-android-arrow-forward',
				'ion ion-android-arrow-up-left',
				'ion ion-android-arrow-up-right',
				'ion ion-android-battery',
				'ion ion-android-book',
				'ion ion-android-calendar',
				'ion ion-android-call',
				'ion ion-android-camera',
				'ion ion-android-chat',
				'ion ion-android-checkmark',
				'ion ion-android-clock',
				'ion ion-android-close',
				'ion ion-android-contact',
				'ion ion-android-contacts',
				'ion ion-android-data',
				'ion ion-android-developer',
				'ion ion-android-display',
				'ion ion-android-download',
				'ion ion-android-drawer',
				'ion ion-android-dropdown',
				'ion ion-android-earth',
				'ion ion-android-folder',
				'ion ion-android-forums',
				'ion ion-android-friends',
				'ion ion-android-hand',
				'ion ion-android-image',
				'ion ion-android-inbox',
				'ion ion-android-information',
				'ion ion-android-keypad',
				'ion ion-android-lightbulb',
				'ion ion-android-locate',
				'ion ion-android-location',
				'ion ion-android-mail',
				'ion ion-android-microphone',
				'ion ion-android-mixer',
				'ion ion-android-more',
				'ion ion-android-note',
				'ion ion-android-playstore',
				'ion ion-android-printer',
				'ion ion-android-promotion',
				'ion ion-android-reminder',
				'ion ion-android-remove',
				'ion ion-android-search',
				'ion ion-android-send',
				'ion ion-android-settings',
				'ion ion-android-share',
				'ion ion-android-social',
				'ion ion-android-social-user',
				'ion ion-android-sort',
				'ion ion-android-stair-drawer',
				'ion ion-android-star',
				'ion ion-android-stopwatch',
				'ion ion-android-storage',
				'ion ion-android-system-back',
				'ion ion-android-system-home',
				'ion ion-android-system-windows',
				'ion ion-android-timer',
				'ion ion-android-trash',
				'ion ion-android-user-menu',
				'ion ion-android-volume',
				'ion ion-android-wifi',
				'ion ion-aperture',
				'ion ion-archive',
				'ion ion-arrow-down-a',
				'ion ion-arrow-down-b',
				'ion ion-arrow-down-c',
				'ion ion-arrow-expand',
				'ion ion-arrow-graph-down-left',
				'ion ion-arrow-graph-down-right',
				'ion ion-arrow-graph-up-left',
				'ion ion-arrow-graph-up-right',
				'ion ion-arrow-left-a',
				'ion ion-arrow-left-b',
				'ion ion-arrow-left-c',
				'ion ion-arrow-move',
				'ion ion-arrow-resize',
				'ion ion-arrow-return-left',
				'ion ion-arrow-return-right',
				'ion ion-arrow-right-a',
				'ion ion-arrow-right-b',
				'ion ion-arrow-right-c',
				'ion ion-arrow-shrink',
				'ion ion-arrow-swap',
				'ion ion-arrow-up-a',
				'ion ion-arrow-up-b',
				'ion ion-arrow-up-c',
				'ion ion-asterisk',
				'ion ion-at',
				'ion ion-bag',
				'ion ion-battery-charging',
				'ion ion-battery-empty',
				'ion ion-battery-full',
				'ion ion-battery-half',
				'ion ion-battery-low',
				'ion ion-beaker',
				'ion ion-beer',
				'ion ion-bluetooth',
				'ion ion-bonfire',
				'ion ion-bookmark',
				'ion ion-briefcase',
				'ion ion-bug',
				'ion ion-calculator',
				'ion ion-calendar',
				'ion ion-camera',
				'ion ion-card',
				'ion ion-cash',
				'ion ion-chatbox',
				'ion ion-chatbox-working',
				'ion ion-chatboxes',
				'ion ion-chatbubble',
				'ion ion-chatbubble-working',
				'ion ion-chatbubbles',
				'ion ion-checkmark',
				'ion ion-checkmark-circled',
				'ion ion-checkmark-round',
				'ion ion-chevron-down',
				'ion ion-chevron-left',
				'ion ion-chevron-right',
				'ion ion-chevron-up',
				'ion ion-clipboard',
				'ion ion-clock',
				'ion ion-close',
				'ion ion-close-circled',
				'ion ion-close-round',
				'ion ion-closed-captioning',
				'ion ion-cloud',
				'ion ion-code',
				'ion ion-code-download',
				'ion ion-code-working',
				'ion ion-coffee',
				'ion ion-compass',
				'ion ion-compose',
				'ion ion-connection-bars',
				'ion ion-contrast',
				'ion ion-cube',
				'ion ion-disc',
				'ion ion-document',
				'ion ion-document-text',
				'ion ion-drag',
				'ion ion-earth',
				'ion ion-edit',
				'ion ion-egg',
				'ion ion-eject',
				'ion ion-email',
				'ion ion-eye',
				'ion ion-eye-disabled',
				'ion ion-female',
				'ion ion-filing',
				'ion ion-film-marker',
				'ion ion-fireball',
				'ion ion-flag',
				'ion ion-flame',
				'ion ion-flash',
				'ion ion-flash-off',
				'ion ion-flask',
				'ion ion-folder',
				'ion ion-fork',
				'ion ion-fork-repo',
				'ion ion-forward',
				'ion ion-funnel',
				'ion ion-game-controller-a',
				'ion ion-game-controller-b',
				'ion ion-gear-a',
				'ion ion-gear-b',
				'ion ion-grid',
				'ion ion-hammer',
				'ion ion-happy',
				'ion ion-headphone',
				'ion ion-heart',
				'ion ion-heart-broken',
				'ion ion-help',
				'ion ion-help-buoy',
				'ion ion-help-circled',
				'ion ion-home',
				'ion ion-icecream',
				'ion ion-icon-social-google-plus',
				'ion ion-icon-social-google-plus-outline',
				'ion ion-image',
				'ion ion-images',
				'ion ion-information',
				'ion ion-information-circled',
				'ion ion-ionic',
				'ion ion-ios7-alarm',
				'ion ion-ios7-alarm-outline',
				'ion ion-ios7-albums',
				'ion ion-ios7-albums-outline',
				'ion ion-ios7-americanfootball',
				'ion ion-ios7-americanfootball-outline',
				'ion ion-ios7-analytics',
				'ion ion-ios7-analytics-outline',
				'ion ion-ios7-arrow-back',
				'ion ion-ios7-arrow-down',
				'ion ion-ios7-arrow-forward',
				'ion ion-ios7-arrow-left',
				'ion ion-ios7-arrow-right',
				'ion ion-ios7-arrow-thin-down',
				'ion ion-ios7-arrow-thin-left',
				'ion ion-ios7-arrow-thin-right',
				'ion ion-ios7-arrow-thin-up',
				'ion ion-ios7-arrow-up',
				'ion ion-ios7-at',
				'ion ion-ios7-at-outline',
				'ion ion-ios7-barcode',
				'ion ion-ios7-barcode-outline',
				'ion ion-ios7-baseball',
				'ion ion-ios7-baseball-outline',
				'ion ion-ios7-basketball',
				'ion ion-ios7-basketball-outline',
				'ion ion-ios7-bell',
				'ion ion-ios7-bell-outline',
				'ion ion-ios7-bolt',
				'ion ion-ios7-bolt-outline',
				'ion ion-ios7-bookmarks',
				'ion ion-ios7-bookmarks-outline',
				'ion ion-ios7-box',
				'ion ion-ios7-box-outline',
				'ion ion-ios7-briefcase',
				'ion ion-ios7-briefcase-outline',
				'ion ion-ios7-browsers',
				'ion ion-ios7-browsers-outline',
				'ion ion-ios7-calculator',
				'ion ion-ios7-calculator-outline',
				'ion ion-ios7-calendar',
				'ion ion-ios7-calendar-outline',
				'ion ion-ios7-camera',
				'ion ion-ios7-camera-outline',
				'ion ion-ios7-cart',
				'ion ion-ios7-cart-outline',
				'ion ion-ios7-chatboxes',
				'ion ion-ios7-chatboxes-outline',
				'ion ion-ios7-chatbubble',
				'ion ion-ios7-chatbubble-outline',
				'ion ion-ios7-checkmark',
				'ion ion-ios7-checkmark-empty',
				'ion ion-ios7-checkmark-outline',
				'ion ion-ios7-circle-filled',
				'ion ion-ios7-circle-outline',
				'ion ion-ios7-clock',
				'ion ion-ios7-clock-outline',
				'ion ion-ios7-close',
				'ion ion-ios7-close-empty',
				'ion ion-ios7-close-outline',
				'ion ion-ios7-cloud',
				'ion ion-ios7-cloud-download',
				'ion ion-ios7-cloud-download-outline',
				'ion ion-ios7-cloud-outline',
				'ion ion-ios7-cloud-upload',
				'ion ion-ios7-cloud-upload-outline',
				'ion ion-ios7-cloudy',
				'ion ion-ios7-cloudy-night',
				'ion ion-ios7-cloudy-night-outline',
				'ion ion-ios7-cloudy-outline',
				'ion ion-ios7-cog',
				'ion ion-ios7-cog-outline',
				'ion ion-ios7-compose',
				'ion ion-ios7-compose-outline',
				'ion ion-ios7-contact',
				'ion ion-ios7-contact-outline',
				'ion ion-ios7-copy',
				'ion ion-ios7-copy-outline',
				'ion ion-ios7-download',
				'ion ion-ios7-download-outline',
				'ion ion-ios7-drag',
				'ion ion-ios7-email',
				'ion ion-ios7-email-outline',
				'ion ion-ios7-expand',
				'ion ion-ios7-eye',
				'ion ion-ios7-eye-outline',
				'ion ion-ios7-fastforward',
				'ion ion-ios7-fastforward-outline',
				'ion ion-ios7-filing',
				'ion ion-ios7-filing-outline',
				'ion ion-ios7-film',
				'ion ion-ios7-film-outline',
				'ion ion-ios7-flag',
				'ion ion-ios7-flag-outline',
				'ion ion-ios7-folder',
				'ion ion-ios7-folder-outline',
				'ion ion-ios7-football',
				'ion ion-ios7-football-outline',
				'ion ion-ios7-gear',
				'ion ion-ios7-gear-outline',
				'ion ion-ios7-glasses',
				'ion ion-ios7-glasses-outline',
				'ion ion-ios7-heart',
				'ion ion-ios7-heart-outline',
				'ion ion-ios7-help',
				'ion ion-ios7-help-empty',
				'ion ion-ios7-help-outline',
				'ion ion-ios7-home',
				'ion ion-ios7-home-outline',
				'ion ion-ios7-infinite',
				'ion ion-ios7-infinite-outline',
				'ion ion-ios7-information',
				'ion ion-ios7-information-empty',
				'ion ion-ios7-information-outline',
				'ion ion-ios7-ionic-outline',
				'ion ion-ios7-keypad',
				'ion ion-ios7-keypad-outline',
				'ion ion-ios7-lightbulb',
				'ion ion-ios7-lightbulb-outline',
				'ion ion-ios7-location',
				'ion ion-ios7-location-outline',
				'ion ion-ios7-locked',
				'ion ion-ios7-locked-outline',
				'ion ion-ios7-loop',
				'ion ion-ios7-loop-strong',
				'ion ion-ios7-medkit',
				'ion ion-ios7-medkit-outline',
				'ion ion-ios7-mic',
				'ion ion-ios7-mic-off',
				'ion ion-ios7-mic-outline',
				'ion ion-ios7-minus',
				'ion ion-ios7-minus-empty',
				'ion ion-ios7-minus-outline',
				'ion ion-ios7-monitor',
				'ion ion-ios7-monitor-outline',
				'ion ion-ios7-moon',
				'ion ion-ios7-moon-outline',
				'ion ion-ios7-more',
				'ion ion-ios7-more-outline',
				'ion ion-ios7-musical-note',
				'ion ion-ios7-musical-notes',
				'ion ion-ios7-navigate',
				'ion ion-ios7-navigate-outline',
				'ion ion-ios7-paper',
				'ion ion-ios7-paper-outline',
				'ion ion-ios7-paperplane',
				'ion ion-ios7-paperplane-outline',
				'ion ion-ios7-partlysunny',
				'ion ion-ios7-partlysunny-outline',
				'ion ion-ios7-pause',
				'ion ion-ios7-pause-outline',
				'ion ion-ios7-paw',
				'ion ion-ios7-paw-outline',
				'ion ion-ios7-people',
				'ion ion-ios7-people-outline',
				'ion ion-ios7-person',
				'ion ion-ios7-person-outline',
				'ion ion-ios7-personadd',
				'ion ion-ios7-personadd-outline',
				'ion ion-ios7-photos',
				'ion ion-ios7-photos-outline',
				'ion ion-ios7-pie',
				'ion ion-ios7-pie-outline',
				'ion ion-ios7-play',
				'ion ion-ios7-play-outline',
				'ion ion-ios7-plus',
				'ion ion-ios7-plus-empty',
				'ion ion-ios7-plus-outline',
				'ion ion-ios7-pricetag',
				'ion ion-ios7-pricetag-outline',
				'ion ion-ios7-pricetags',
				'ion ion-ios7-pricetags-outline',
				'ion ion-ios7-printer',
				'ion ion-ios7-printer-outline',
				'ion ion-ios7-pulse',
				'ion ion-ios7-pulse-strong',
				'ion ion-ios7-rainy',
				'ion ion-ios7-rainy-outline',
				'ion ion-ios7-recording',
				'ion ion-ios7-recording-outline',
				'ion ion-ios7-redo',
				'ion ion-ios7-redo-outline',
				'ion ion-ios7-refresh',
				'ion ion-ios7-refresh-empty',
				'ion ion-ios7-refresh-outline',
				'ion ion-ios7-reload',  'ion ion-ios7-reloading',
				'ion ion-ios7-reverse-camera',
				'ion ion-ios7-reverse-camera-outline',
				'ion ion-ios7-rewind',
				'ion ion-ios7-rewind-outline',
				'ion ion-ios7-search',
				'ion ion-ios7-search-strong',
				'ion ion-ios7-settings',
				'ion ion-ios7-settings-strong',
				'ion ion-ios7-shrink',
				'ion ion-ios7-skipbackward',
				'ion ion-ios7-skipbackward-outline',
				'ion ion-ios7-skipforward',
				'ion ion-ios7-skipforward-outline',
				'ion ion-ios7-snowy',
				'ion ion-ios7-speedometer',
				'ion ion-ios7-speedometer-outline',
				'ion ion-ios7-star',
				'ion ion-ios7-star-half',
				'ion ion-ios7-star-outline',
				'ion ion-ios7-stopwatch',
				'ion ion-ios7-stopwatch-outline',
				'ion ion-ios7-sunny',
				'ion ion-ios7-sunny-outline',
				'ion ion-ios7-telephone',
				'ion ion-ios7-telephone-outline',
				'ion ion-ios7-tennisball',
				'ion ion-ios7-tennisball-outline',
				'ion ion-ios7-thunderstorm',
				'ion ion-ios7-thunderstorm-outline',
				'ion ion-ios7-time',
				'ion ion-ios7-time-outline',
				'ion ion-ios7-timer',
				'ion ion-ios7-timer-outline',
				'ion ion-ios7-toggle',
				'ion ion-ios7-toggle-outline',
				'ion ion-ios7-trash',
				'ion ion-ios7-trash-outline',
				'ion ion-ios7-undo',
				'ion ion-ios7-undo-outline',
				'ion ion-ios7-unlocked',
				'ion ion-ios7-unlocked-outline',
				'ion ion-ios7-upload',
				'ion ion-ios7-upload-outline',
				'ion ion-ios7-videocam',
				'ion ion-ios7-videocam-outline',
				'ion ion-ios7-volume-high',
				'ion ion-ios7-volume-low',
				'ion ion-ios7-wineglass',
				'ion ion-ios7-wineglass-outline',
				'ion ion-ios7-world',
				'ion ion-ios7-world-outline',
				'ion ion-ipad',
				'ion ion-iphone',
				'ion ion-ipod',
				'ion ion-jet',
				'ion ion-key',
				'ion ion-knife',
				'ion ion-laptop',
				'ion ion-leaf',
				'ion ion-levels',
				'ion ion-lightbulb',
				'ion ion-link',
				'ion ion-load-a',  'ion ion-loading-a',
				'ion ion-load-b',  'ion ion-loading-b',
				'ion ion-load-c',  'ion ion-loading-c',
				'ion ion-load-d',  'ion ion-loading-d',
				'ion ion-location',
				'ion ion-locked',
				'ion ion-log-in',
				'ion ion-log-out',
				'ion ion-loop',  'ion ion-looping',
				'ion ion-magnet',
				'ion ion-male',
				'ion ion-man',
				'ion ion-map',
				'ion ion-medkit',
				'ion ion-merge',
				'ion ion-mic-a',
				'ion ion-mic-b',
				'ion ion-mic-c',
				'ion ion-minus',
				'ion ion-minus-circled',
				'ion ion-minus-round',
				'ion ion-model-s',
				'ion ion-monitor',
				'ion ion-more',
				'ion ion-mouse',
				'ion ion-music-note',
				'ion ion-navicon',
				'ion ion-navicon-round',
				'ion ion-navigate',
				'ion ion-network',
				'ion ion-no-smoking',
				'ion ion-nuclear',
				'ion ion-outlet',
				'ion ion-paper-airplane',
				'ion ion-paperclip',
				'ion ion-pause',
				'ion ion-person',
				'ion ion-person-add',
				'ion ion-person-stalker',
				'ion ion-pie-graph',
				'ion ion-pin',
				'ion ion-pinpoint',
				'ion ion-pizza',
				'ion ion-plane',
				'ion ion-planet',
				'ion ion-play',
				'ion ion-playstation',
				'ion ion-plus',
				'ion ion-plus-circled',
				'ion ion-plus-round',
				'ion ion-podium',
				'ion ion-pound',
				'ion ion-power',
				'ion ion-pricetag',
				'ion ion-pricetags',
				'ion ion-printer',
				'ion ion-pull-request',
				'ion ion-qr-scanner',
				'ion ion-quote',
				'ion ion-radio-waves',
				'ion ion-record',
				'ion ion-refresh', 'ion ion-refreshing',
				'ion ion-reply',
				'ion ion-reply-all',
				'ion ion-ribbon-a',
				'ion ion-ribbon-b',
				'ion ion-sad',
				'ion ion-scissors',
				'ion ion-search',
				'ion ion-settings',
				'ion ion-share',
				'ion ion-shuffle',
				'ion ion-skip-backward',
				'ion ion-skip-forward',
				'ion ion-social-android',
				'ion ion-social-android-outline',
				'ion ion-social-apple',
				'ion ion-social-apple-outline',
				'ion ion-social-bitcoin',
				'ion ion-social-bitcoin-outline',
				'ion ion-social-buffer',
				'ion ion-social-buffer-outline',
				'ion ion-social-designernews',
				'ion ion-social-designernews-outline',
				'ion ion-social-dribbble',
				'ion ion-social-dribbble-outline',
				'ion ion-social-dropbox',
				'ion ion-social-dropbox-outline',
				'ion ion-social-facebook',
				'ion ion-social-facebook-outline',
				'ion ion-social-foursquare',
				'ion ion-social-foursquare-outline',
				'ion ion-social-freebsd-devil',
				'ion ion-social-github',
				'ion ion-social-github-outline',
				'ion ion-social-google',
				'ion ion-social-google-outline',
				'ion ion-social-googleplus',
				'ion ion-social-googleplus-outline',
				'ion ion-social-hackernews',
				'ion ion-social-hackernews-outline',
				'ion ion-social-instagram',
				'ion ion-social-instagram-outline',
				'ion ion-social-linkedin',
				'ion ion-social-linkedin-outline',
				'ion ion-social-pinterest',
				'ion ion-social-pinterest-outline',
				'ion ion-social-reddit',
				'ion ion-social-reddit-outline',
				'ion ion-social-rss',
				'ion ion-social-rss-outline',
				'ion ion-social-skype',
				'ion ion-social-skype-outline',
				'ion ion-social-tumblr',
				'ion ion-social-tumblr-outline',
				'ion ion-social-tux',
				'ion ion-social-twitter',
				'ion ion-social-twitter-outline',
				'ion ion-social-usd',
				'ion ion-social-usd-outline',
				'ion ion-social-vimeo',
				'ion ion-social-vimeo-outline',
				'ion ion-social-windows',
				'ion ion-social-windows-outline',
				'ion ion-social-wordpress',
				'ion ion-social-wordpress-outline',
				'ion ion-social-yahoo',
				'ion ion-social-yahoo-outline',
				'ion ion-social-youtube',
				'ion ion-social-youtube-outline',
				'ion ion-speakerphone',
				'ion ion-speedometer',
				'ion ion-spoon',
				'ion ion-star',
				'ion ion-stats-bars',
				'ion ion-steam',
				'ion ion-stop',
				'ion ion-thermometer',
				'ion ion-thumbsdown',
				'ion ion-thumbsup',
				'ion ion-toggle',
				'ion ion-toggle-filled',
				'ion ion-trash-a',
				'ion ion-trash-b',
				'ion ion-trophy',
				'ion ion-umbrella',
				'ion ion-university',
				'ion ion-unlocked',
				'ion ion-upload',
				'ion ion-usb',
				'ion ion-videocamera',
				'ion ion-volume-high',
				'ion ion-volume-low',
				'ion ion-volume-medium',
				'ion ion-volume-mute',
				'ion ion-wand',
				'ion ion-waterdrop',
				'ion ion-wifi',
				'ion ion-wineglass',
				'ion ion-woman',
				'ion ion-wrench',
				'ion ion-xbox');
			
			return $icones;
		}
		################################################################################################################
		public static function SelectIcone($func = "SetIcone")
		{
			$icones = self::GetIcones();
			$lista = "";
			foreach ($icones as $key => $valor)
			{
				$lista .= "<li><a id=\"Aicone{$key}\" href=\"javascript:;\" onclick=\"{$func}(this,'{$valor}')\"><i class=\"{$valor}\"></i></a></li>\n";
			}
			
			return $lista;
		}
		################################################################################################################
		public static function GetListaIcone($func = "SetIcone", $estilo = "")
		{
			if(!empty($estilo))
				$estilo = " style=\"{$estilo}\"";
			$lista = "<div class=\"listaicones\"{$estilo}><ul>";
			$lista .= self::SelectIcone($func);
			$lista .= '</ul></div>';
			return $lista;
		}
		################################################################################################################
		public static function ExibeListaIcone($func = "SetIcone", $estilo = "")
		{
			$lista = self::GetListaIcone($func, $estilo);
			echo $lista;
		}
		################################################################################################################
		public static function GetViews($fileobj = "setup", $dados = false)
		{
			$retorno = false;
			$fileobj = strtolower($fileobj);
			$file = SIUP_VIEWS_PATH."{$fileobj}.php";
			if(!self::FileExiste($file))
				return $retorno;
			if(!empty($dados))
			{
				extract ($dados, EXTR_PREFIX_SAME, "COB");
			}
			include($file);
			return $file;
		}
		################################################################################################################
		public static function LerViews($obj = "setup", $dados = false)
		{
			$retorno = false;
			$obj = strtolower($obj);
			$file = SIUP_VIEWS_PATH."{$obj}.php";
			if(!self::FileExiste($file))
				return $retorno;
			if(!empty($dados))
			{
				extract ($dados, EXTR_PREFIX_SAME, "COB");
			}
			ob_start();
			include($file);
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
		################################################################################################################
		public static function &GetInstancia($obj = "setup")
		{
			$retorno = false;
			$obj = ucfirst(strtolower($obj)."_model");
			$file = SIUP_MODELS_PATH."{$obj}.php";
			if(!self::FileExiste($file))
				return $retorno;
			require_once($file);
			$classe = $obj;
			$obj = new $classe();
			return $obj;
		}
		################################################################################################################
		public static function &GetLibrary($obj = "setup", $caminho = "")
		{
			$retorno = false;
			$file = SIUP_LIBRARIES_PATH.$caminho."{$obj}.php";
			if(!self::FileExiste($file))
				return $retorno;
			require_once($file);
			$classe = ucfirst($obj);
			$obj = new $classe();
			return $obj;
		}
		################################################################################################################
		public static function &GetControle($obj = "setup")
		{
			$retorno = false;
			$obj = ucfirst(strtolower($obj));
			$file = SIUP_CONTROLLERS_PATH."{$obj}.php";
			if(!self::FileExiste($file))
				return $retorno;
			require_once($file);
			$classe = $obj;
			$obj = new $classe();
			return $obj;
		}
		################################################################################################################
		public static function SalvarOpcao( $Nome = "", $valor = "", $autoload = "no", $atualizar = true )
		{
			if ( get_option($Nome) !== false )
			{
				if($atualizar)
					update_option($Nome, $valor);
				return true;
			}
			else
			{
				$deprecated = null;
				return add_option( $Nome, $valor, $deprecated, $autoload );
			}
			return false;
		}
		################################################################################################################
		public static function FormataPrice( $price, $args = array() ) {
			$args = apply_filters(
				'wc_price_args', wp_parse_args(
					$args, array(
						'ex_tax_label'       => false,
						'currency'           => '',
						'target'           => '',
						'class'           => '',
						'decimal_separator'  => wc_get_price_decimal_separator(),
						'thousand_separator' => wc_get_price_thousand_separator(),
						'decimals'           => wc_get_price_decimals(),
						'price_format'       => get_woocommerce_price_format(),
					)
				)
			);
			
			$unformatted_price = $price;
			$negative          = $price < 0;
			$price             = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * -1 : $price ) );
			$price             = apply_filters( 'formatted_woocommerce_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );
			
			if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
				$price = wc_trim_zeros( $price );
			}
			
			$formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], get_woocommerce_currency_symbol( $args['currency'] ), $price );
			if(!empty($args['target'])){
				if(!empty($args['class']))
					$aux = 'class="'.$args['class'].'"';
				$retorno          = '<'.$args['target']." ".$aux.'>' . $formatted_price . '</'.$args['target'].'>';
			}
			else
				$retorno = $formatted_price;
			
			return $retorno;
		}
		################################################################################################################
		public static function GetPriceNormal( $id = 0 ) {
			$args = get_post_custom($id);
			$preco = $args['_regular_price'][0];
			return $preco;
		}
		################################################################################################################
		public static function GetPricePromocao( $id = 0) {
			$args = get_post_custom($id);
			$preco = $args['_sale_price'][0];
			if(!empty($preco))
			{
				$datainicio = $args['_sale_price_dates_from'][0];
				$datafim = $args['_sale_price_dates_to'][0];
				if((!empty($datainicio))||(!empty($datafim)))
				{
					$datainicio = date("Y-m-d",$datainicio);
					$datafim = date("Y-m-d",$datafim);
					if((Componente::ComparaData($datainicio) > 0)||(Componente::ComparaData($datafim) < 0))
					{
						$preco = null;
					}
				}
			}
			else
				$preco = null;
			
			return $preco;
		}
		################################################################################################################
		public static function isMobile() {
			$useragent=$_SERVER['HTTP_USER_AGENT'];
			if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
				return true;
			
			return false;
		}
		################################################################################################################
		public static function SetConfiguracao( &$configs = false) {
			
			if(empty($configs['online-shop-feature-right-post-number']))
				return;
			if(self::isMobile())
			{
				$num = strval($configs['online-shop-feature-right-post-number']);
				if($num > 2)
					$configs['online-shop-feature-right-post-number'] = 2;
			}
			return;
		}
		################################################################################################################
		public static function GetOpcao($nome = false, $default = "")
		{
			if(empty($nome))
				return $default;
			$opcao = self::GetLibrary("opcao");
			return $opcao->GetOpcao($nome, $default);
		}
		################################################################################################################
		public static function eMoney( $money)
		{
			return 'R$ ' . number_format( $money, 2, ',', '.' );
		}
		################################################################################################################
		public static function jMoney($money)
		{
			return number_format( $money, 2, ',', '.' );
		}
		################################################################################################################
		public static function Getfloat($num)
		{
			$dotPos = strrpos($num, '.');
			$commaPos = strrpos($num, ',');
			$sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
			
			if(!$sep)
			{
				return floatval(preg_replace("/[^0-9]/", "", $num));
			}
			$decimal = preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)));
			if(strlen($decimal)>=3)
			{
				return floatval(preg_replace("/[^0-9]/", "", $num));
			}
			return floatval(
				preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
				preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
			);
		}
		################################################################################################################
		public static function TamanhoUpload()
		{
			$TamanhoPost = ini_get( 'post_max_size' );
			$TamanhoUpload = ini_get( 'upload_max_filesize' );
			$valor = min( $TamanhoPost, $TamanhoUpload );
			return $valor."B";
		}
		################################################################################################################
		public static function GerarThumb($file = "", $max_w = null, $max_h = null, $crop = false, $caminho = false, $nome = "")
		{
			if(!self::FileExiste($file))
				return;
			if(empty($caminho))
				$caminho = dirname($file)."/thumb/";
			if(empty($nome))
				$nome = basename($file);
			if(!is_dir($caminho))
				self::CriarPastas($caminho);
			$thumb = $caminho.$nome;
			$image = wp_get_image_editor($file);
			if ( ! is_wp_error( $image ) ) {
				$image->resize( $max_w, $max_h, $crop );
				$image->save($thumb);
			}
		}
		################################################################################################################
		public static function codeToMessageFile($code)
		{
			switch ($code) {
				case UPLOAD_ERR_INI_SIZE:
					$message = __("O arquivo enviado excede a diretiva upload_max_filesize no php.ini", SIUP_LANG);
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$message = __("O arquivo enviado excede a diretiva MAX_FILE_SIZE especificada no formulário HTML", SIUP_LANG);
					break;
				case UPLOAD_ERR_PARTIAL:
					$message = __("O arquivo enviado foi enviado apenas parcialmente", SIUP_LANG);
					break;
				case UPLOAD_ERR_NO_FILE:
					$message = __("Nenhum arquivo foi enviado", SIUP_LANG);
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$message = __("Faltando uma pasta temporária", SIUP_LANG);
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$message = __("Falha ao gravar o arquivo no disco", SIUP_LANG);
					break;
				case UPLOAD_ERR_EXTENSION:
					$message = __("Upload de arquivo interrompido por extensão", SIUP_LANG);
					break;
				case UPLOAD_ERR_OK:
					$message = "";
					break;
				default:
					$message = __("Erro de upload desconhecido", SIUP_LANG);
					break;
			}
			return $message;
		}
		################################################################################################################
		public static function SetTitulo($texto = "")
		{
			if(empty($texto))
				$texto = get_bloginfo('name');
			else
				$texto .=' | '.get_bloginfo('name');
			return $texto;
		}
		################################################################################################################
		public static function gerarSenha($tam = 8, $tipo = "Normal")
		{
		    if($tipo == "Normal")
			    $con = 'aeiou1234567890ybdghwjmnxpqflrstvzck';
		    elseif($tipo == "Numerico")
			    $con = '123456789';
            elseif($tipo == "Alfanumerico")
	            $con = 'aeiouybdghwjmnxpqflrstvzck';
            elseif($tipo == "Minusculas")
			    $con = strtoupper('aeiou1234567890ybdghwjmnxpqflrstvzck');
			$senha = '';
			for($i = 0; $i < $tam; $i++):
				$senha .= $con[(rand() % strlen( $con ))];
			endfor;
			return $senha;
		}
		################################################################################################################
		public static function EmailNovaSenha( $user = false, $novasenha = '' ) {
			
			if(empty($user))
				return;
			$user_login = stripslashes( $user->user_login );
			$user_email = stripslashes( $user->user_email );
			$user_nome = stripslashes( $user->display_name );
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$subject = sprintf(__('Olá %s, sua nova senha no site  %s:', SIUP_LANG), $user_nome, $blogname);
			$login = self::SetURL("/logar");
			
			/* Vamos enviar o email customizado para o usuário. */
			
			ob_start();
			include( SIUP_VIEWS_PATH.'email/email_header.php' );
			?>
			<p>Olá <?php echo esc_html($user_nome); ?>, foi solicitado uma nova senha no website <?php echo $blogname; ?>. </p>
			<p>O seu usuário é <?php echo esc_html( $user_login ); ?> e a sua nova senha <?php echo esc_html( $novasenha ); ?>
			<br>Por favor mantenha-a segura!</p>
			<p>Para fazer login por favor, <a href="<?php echo $login; ?>">clique aqui!</a></p>
			<p>Desejamos que aproveite o melhor deste website. Se tiver algum problema, comentários ou sugestões, por favor, sinta-se confortável para entrar em contato conosco.</p>
			
			<?php
			include(  SIUP_VIEWS_PATH.'email/email_footer.php' );
			$message = ob_get_contents();
			ob_end_clean();
			wp_mail( $user_email, $subject, $message );
			
		}
		################################################################################################################
		public static function EmailSeusDados( $user = false ) {
			
			if(empty($user))
				return;
			$user_login = stripslashes( $user->user_login );
			$user_email = stripslashes( $user->user_email );
			$user_nome = stripslashes( $user->display_name );
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$subject = sprintf(__('Olá %s, Informações da sua conta no site  %s:', SIUP_LANG), $user_nome, $blogname);
			$login = self::SetURL("/logar");
			
			/* Vamos enviar o email customizado para o usuário. */
			
			ob_start();
			include( SIUP_VIEWS_PATH.'email/email_header.php' );
			?>
			<p>Olá <?php echo esc_html($user_nome); ?>, foi solicitado as informações da sua conta no website <?php echo $blogname; ?>. </p>
			<p>O seu usuário é <?php echo esc_html( $user_login ); ?> e o seu e-mail é <?php echo esc_html( $user_email ); ?>
            <br>Por favor mantenha-a segura!</p>
			<p>Para fazer login por favor, <a href="<?php echo $login; ?>">clique aqui!</a></p>
			<p>Desejamos que aproveite o melhor deste website. Se tiver algum problema, comentários ou sugestões, por favor, sinta-se confortável para entrar em contato conosco.</p>
			
			<?php
			include(SIUP_VIEWS_PATH.'email/email_footer.php' );
			$message = ob_get_contents();
			ob_end_clean();
			wp_mail( $user_email, $subject, $message );
			
		}
		################################################################################################################
		public static function EmaildeCadastroCliente( $user = false ) {
			
			if(empty($user))
				return;
			$user_login = stripslashes( $user->user_login );
			$user_email = stripslashes( $user->user_email );
			$user_nome = stripslashes( $user->display_name );
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$subject = sprintf(__('Olá %s, Informações da sua conta no site  %s', SIUP_LANG), $user_nome, $blogname);
			$login = self::SetURL("/logar");
			$telefone = get_user_meta ($user->ID, "telefone", true);
			if(Componente::IsCelular($telefone))
			{
				$dados['nome'] = $user_nome;
				Componente::SalvarSms( $telefone,'Cadastro cliente', $dados);
			}
			/* Vamos enviar o email customizado para o usuário. */
			
			ob_start();
			include( SIUP_VIEWS_PATH.'email/email_header.php' );
			?>
            <p>Olá <?php echo esc_html($user_nome); ?>, foi solicitado as informações da sua conta no website <?php echo $blogname; ?>. </p>
            <p>O seu usuário é <?php echo esc_html( $user_login ); ?> e o seu e-mail é <?php echo esc_html( $user_email ); ?>
                <br>Por favor mantenha-a segura!</p>
            <p>Para fazer login por favor, <a href="<?php echo $login; ?>">clique aqui!</a></p>
            <p>Desejamos que aproveite o melhor deste website. Se tiver algum problema, comentários ou sugestões, por favor, sinta-se confortável para entrar em contato conosco.</p>
			
			<?php
			include(SIUP_VIEWS_PATH.'email/email_footer.php' );
			$message = ob_get_contents();
			ob_end_clean();
			wp_mail( $user_email, $subject, $message );
			
		}
		################################################################################################################
		public static function GetUsernome( $nome = "", $email = "" ) {
			
			$pos = strripos($nome," ");
			if($pos === false)
			{
				if(!username_exists($nome))
					return $nome;
				$sobrenome = $nome;
				$user_nome = $nome."0";
			}
			else
			{
				$aux = explode(" ", $nome);
				$user_nome = $aux[0]." ".$aux[1];
				$sobrenome = $user_nome;
				if(!username_exists($user_nome))
					return $user_nome;
				if(count($aux) > 2)
				{
					$user_nome = $aux[0]." ".$aux[1]." ".$aux[2];
					if(!username_exists($user_nome))
						return $user_nome;
					$user_nome = $aux[0]." ".$aux[1]."0";
				}
			}
			if(!username_exists($user_nome))
				return $user_nome;
			$user_email = strstr($email, '@', true);
			if(!username_exists($user_email))
				return $user_email;
			if(!username_exists($nome))
				return $nome;
			for($i = 1; $i < 255; $i++ )
			{
				$user_email = $sobrenome.$i;
				if(!username_exists($user_email))
					return $user_email;
			}
			return "";
			
		}
        ################################################################################################################
		public static function GerarIN( $dados = 0, $not_in = false)
		{
			if( is_array( $dados ) )
			{
				$lista = '';
				foreach( $dados as $valor )
				{
					if(!is_array($valor))
					    $lista .= ", '{$valor}'";
					else
                    {
                        $aux = array_keys($valor);
                        $key = $aux[0];
	                    $aux = $valor[$key];
	                    $lista .= ", '{$aux}'";
                    }
				}
				$lista = substr( $lista, 2 );
				if($not_in)
					return " NOT IN({$lista})";
				else
					return " IN({$lista})";
			}
			if($not_in)
				return " != '{$dados}'";
			else
				return " = '{$dados}'";
		}
        ################################################################################################################
		public static function emptyData($data = "" )
		{
			if(empty($data))
				return true;
			if($data == "0000-00-00")
				return true;
			if($data == "00/00/0000")
				return true;
			if($data == "0000-00-00 00:00:00")
				return true;
			if($data == "00/00/0000 00:00:00")
				return true;
			return false;
		}
		################################################################################################################
	    public static function IsCelular($campo = "")
		{
			$campo = preg_replace( '/\D/', '', $campo);
			if(empty($campo))
				return false;
			$digito = intval(substr($campo, 0, 2));
			if($digito == 55)
				$ddi = true;
			else
			    $ddi = false;
			$len = strlen($campo);
			$digito = 0;
			
			if(!empty($ddi))
			{
				if($len <= 4)
					return false;
				if($len >= 13)
					$digito = intval(substr($campo, 5, 1));
				elseif($len >= 12)
					$digito = intval(substr($campo, 4, 1));
			}
			else
			{
				if($len <= 2)
					return false;
				if($len >= 11)
					$digito = intval(substr($campo, 3, 1));
				elseif($len >= 10)
					$digito = intval(substr($campo, 2, 1));
			}
			if($digito >= 8)
				return true;
			
			if($digito < 6)
				return false;
			if(!empty($ddi))
				$ddd = intval(substr($campo, 2, 2));
			else
				$ddd = intval(substr($campo, 0, 2));
			
			if(($ddd >= 11)&&($ddd <= 28)&&($ddd != 20))
				return true;
			
			return false;
		}
		################################################################################################################
		public static function SalvarSms($telefone = "", $tipo = "", $dados = false)
		{
			if(empty($telefone))
				return false;
			$obj = Componente::GetInstancia("sms");
			return $obj->SalvarSms($telefone, $tipo, $dados);
		}
		################################################################################################################
		public static function Excel($dados = false)
		{
			$default = array(
				"file"=>"excel_exportacao_".date("Y-m-d_H-m-s")."xls",
				"lista"=>false,
				"html"=>true,
				"campos"=>false,
				"download"=>true,
				"pasta"=>""
			);
			$dados = self::CompletaArray($dados, $default);
			if(!empty($dados['download']))
			{
				header('Content-type: application/x-msdownload');
				header('Content-Disposition: attachment; filename='.$dados['file'].'.xls');
				header('Pragma: no-cache');
				header('Expires: 0');
			}
			$spreadsheet="";
			if(empty($dados['lista']))
			{
				$spreadsheet = "Falha ao gerar o arquivo!\t";
				if(!empty($dados['download']))
					echo $spreadsheet;
				else
				{
					if(empty($dados['pasta']))
					{
						$caminho = self::GetPasta("/arquivos/projeto/");
					}
					else
					{
						$caminho = $dados['pasta'];
					}
					self::CriarPastas($caminho);
					$file = $caminho.$dados['file'];
					file_put_contents($file,utf8_decode($spreadsheet),FILE_TEXT);
				}
				return;
			}
			$n = count($dados['lista']);
			if( $n > 0)
			{
				$spreadsheet = self::MontarCabecario($dados);
				
				foreach($dados['lista'] as $key=>$obj)
				{
					if(method_exists($obj,'GetDadosExcel'))
					{
						$linha = $obj->GetDadosExcel();
					}
					else
					{
						$linha = $obj->GetDados();
					}
					$spreadsheet .= self::MontarLinha($dados['campos'], $linha, $dados['html']);
				}
			}
			else
			{
				$spreadsheet .= "Nenhum registro foi encontrado!\t";
			}
			if(!empty($dados['download']))
				echo $spreadsheet;
			else
			{
				if(empty($dados['pasta']))
				{
					$caminho = self::GetPasta("/arquivos/projeto");
				}
				else
				{
					$caminho = $dados['pasta'];
				}
				self::CriarPastas($caminho);
				$file = $caminho.$dados['file'];
				file_put_contents($file,utf8_decode($spreadsheet),FILE_TEXT);
			}
			return;
		}
		################################################################################################################
		public static function GetPasta($caminho = "")
		{
			$aux = substr($caminho,0, 1);
			if($aux == "/")
				$caminho = substr($caminho,1);
			$aURL = SIUP_UPLOADS_PATH."{$caminho}";
			return $aURL;
		}
        ################################################################################################################
		public static function MontarCabecario(&$dados = false)
		{
			if(!$dados)
				return "";
			$spreadsheet = "";
			if(empty($dados['campos']))
			{
				if(empty($dados['lista']))
					return "";
				$obj = $dados['lista'][0];
				if(empty($obj))
					return "";
				if(method_exists($obj,'CamposExcel'))
				{
					$campos = $obj->CamposExcel();
				}
				else
				{
					$columns = $obj->GetDados();
					foreach($columns as $nome=>$valor)
					{
						if(!is_numeric($nome))
							$campos[$nome] = $nome;
					}
				}
				$dados['campos'] = $campos;
			}
			else
				$campos = $dados['campos'];
			foreach ($campos as $key => $v)
			{
				$aux = self::Maiusculo($key);
				if($dados['html'])
					$spreadsheet .= "<th align='center' valign='middle' bgcolor='#FB7474'>{$aux}</th>";
				else
					$spreadsheet .= "{$aux}\t";
			}
			if($dados['html'])
				$spreadsheet = "<table  dir='ltr' border='1' cellspacing='0' cellpadding='1'><tr>{$spreadsheet}</tr>";
			return $spreadsheet;
		}
        ################################################################################################################
		public static function MontarLinha($Campos = false, &$r = false, $html = false)
		{
			if(!$r)
				return "";
			$linha = "";
			$cor = self::SetColor();
			foreach ($Campos as $nome)
			{
				if(empty($r[$nome]))
					$aux = "";
				else
					$aux = $r[$nome];
				$linha .= self::MontarCelula($aux, $html, $cor);
			}
			if($html)
				$linha = "<tr>{$linha}</tr>";
			else
				$linha = "\n{$linha}";
			return $linha;
		}
        ################################################################################################################
		public static function MontarCelula(&$AUX = false, $html = false, $cor = "#ffffff")
		{
			if($html)
			{
			    if(is_numeric($AUX))
                {
			        $numero = $AUX + 0;
				    if((is_double($numero)) || (is_float($numero)))
				    {
					    $celula = self::jMoney(floatval($numero));
					    $celula = "<td bgcolor='{$cor}' valign='middle' align='right'>{$celula}</td>";
				    }
				    elseif ((is_int($numero)) || (is_integer($numero)))
                    {
	                    $celula = "<td bgcolor='{$cor}' valign='middle' align='right'>{$AUX}</td>";
                    }
				    else
                    {
	                    $AUX = nl2br($AUX);
	                    $AUX = preg_replace('/\\r\\n/m', ' ', $AUX);
	                    $AUX = preg_replace('/\\n/m', ' ', $AUX);
	                    $AUX = preg_replace('/\\t/m', '   ', $AUX);
					    $celula = "<td bgcolor='{$cor}' valign='middle'  align='left'>{$AUX}</td>";
				    }
			    }
			    else
			    {
				    $AUX = nl2br($AUX);
				    $AUX = preg_replace('/\\r\\n/m', ' ', $AUX);
				    $AUX = preg_replace('/\\n/m', ' ', $AUX);
				    $AUX = preg_replace('/\\t/m', '   ', $AUX);
				    $celula = "<td bgcolor='{$cor}' valign='middle'  align='left'>{$AUX}</td>";
			    }
			}
			else
			{
				if(is_numeric($AUX))
				{
					$numero = $AUX + 0;
					if ((is_double($numero)) || (is_float($numero)))
					    $celula = self::jMoney($numero) . "\t";
                    elseif ((is_int($numero)) || (is_integer($numero)))
					{
						$celula = $AUX . "\t";
					}
					else
                        $celula = $AUX . "\t";
				}
				else
				{
					$AUX = preg_replace('/\\r\\n/m', ' ', $AUX);
					$AUX = preg_replace('/\\n/m', ' ', $AUX);
					$AUX = preg_replace('/\\t/m', '   ', $AUX);
					$celula = $AUX."\t";
				}
			}
			return $celula;
		}
        ################################################################################################################
		public static function SetColor()
		{
			static $CorLinha = "#ffffff";
			if($CorLinha == "#ffffff")
				$CorLinha = "#E4E4E4";
			else
				$CorLinha = "#ffffff";
			return $CorLinha;
		}
        ################################################################################################################
		public static function IsHtml($html = "")
		{
			return preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', $html);
		}
		################################################################################################################
		public static function P($obj = false)
		{
			$numargs = func_num_args();
			if($numargs == 0)
			{
				echo "<pre>sem paramentro</pre>";
				return;
			}
			for ($i = 0; $i < $numargs; $i++)
			{
				$valor = func_get_arg($i);
				if(empty($valor))
				{
					echo "<pre>Variavel vazia =>[".print_r($valor, true)."]</pre>";
				}
				else
				{
					echo "<pre>";
					if(is_object($valor))
					{
						print_r($valor);
					}
					elseif(is_array($valor))
						print_r($valor);
					else
						var_dump($valor);
					echo "</pre>";
				}
			}
			return;
		}
	}
?>