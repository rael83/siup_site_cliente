<?php
	
	class Meumodelo extends stdClass
	{
		// Variável que define o nome da tabela
		public $Tabela = "";
		public $PrimaryKey = "";
		public $Prefix = "";
		public $dados = NULL;
		private static $camposdefault = NULL;
		
		################################################################################################################
		public function __construct($dados = false)
		{
			
			if(!empty($dados))
			{
				if(is_array($dados))
				{
					$this->Carregar($dados);
				}
				elseif(is_string($dados))
				{
					$row = $this->GetRow($dados);
					if($row)
						$this->Carregar($row);
					else
						$this->Carregar($this->GetDefault());
				}
				elseif(is_numeric($dados))
				{
					$row = $this->GetRow($dados);
					if($row)
						$this->Carregar($row);
					else
						$this->Carregar($this->GetDefault());
				}
			}
			else
				$this->Carregar($this->GetDefault());
		}
		################################################################################################################
		public function &GetInstancia($dados = false)
		{
			$obj = false;
			$nome = get_class($this);
			$obj = new $nome($dados);
			return $obj;
		}
		################################################################################################################
		public function __set($name, $value)
		{
			$this->dados[$name] = $value;
			$this->$name = $value;
		}
		################################################################################################################
		public function Get($nome = "", $defult = "")
		{
			$valor = $defult;
			if(isset($this->$nome))
			{
				$valor = $this->$nome;
			}
			elseif(isset($this->dados[$nome]))
			{
				$valor = $this->dados[$nome];
			}
			return $valor;
		}
		################################################################################################################
		public function Set($nome = "", $valor = "", $defult = "")
		{
			if(empty($valor))
			{
				if(!empty($defult))
					$valor = $defult;
			}
			if(!is_numeric($nome))
			{
				$this->$nome = $valor;
				$this->dados[$nome] = $valor;
			}
			return;
		}
		################################################################################################################
		public function GetID()
		{
			return $this->Get($this->PrimaryKey, 0);
		}
		################################################################################################################
		public function SetID($valor = "", $defult = 0)
		{
			return $this->Set($this->PrimaryKey, $valor, $defult);
		}
		################################################################################################################
		public function FormGet($nome = "", $defult = "")
		{
			if(empty($nome))
			{
				return $defult;
			}
			if(isset($_REQUEST[$nome]))
			{
				return $_REQUEST[$nome];
			}
			return @$this->Get($nome, $defult);
		}
		################################################################################################################
		public function FormGetData($nome = "", $formato = "d/m/Y", $defult = "")
		{
			$resultado = $this->FormGet($nome, $defult);
			if(empty($resultado))
				return $defult;
			$teste = date($formato,0);
			if($teste == $resultado)
				return $defult;
			return date($formato, Componente::TimeData($resultado));
		}
		################################################################################################################
		public function GetTabela()
		{
			$retorno = $this->Tabela;
			try
			{
				global $wpdb;
				return "{$wpdb->prefix}{$this->Tabela}";
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function GetPrefix()
		{
			$retorno = $this->Tabela;
			try
			{
				global $wpdb;
				return $wpdb->prefix;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function &FiltroObjetos($filtro = false, $sql = "", $defult = false)
		{
			$retorno = false;
			try
			{
				$instancia = $retorno;
				$rows = $this->GetRows($filtro, $sql, $defult);
				if(!empty($rows))
				{
					foreach ($rows as $key => $row)
					{
						$instancia[] = $this->GetInstancia($row);
					}
				}
				return $instancia;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function &FiltroObjeto($filtro = false, $sql = "", $defult = false)
		{
			$retorno = false;
			try
			{
				$instancia = $retorno;
				$row = $this->GetRow($filtro, $sql, $defult);
				if(!empty($row))
				{
					$instancia = $this->GetInstancia($row);
				}
				return $instancia;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function &FiltroJson($filtro = false, $sql = "", $defult = false)
		{
			$retorno = false;
			try
			{
				$instancia = $retorno;
				$rows = $this->GetRows($filtro, $sql, $defult);
				if(!empty($rows))
				{
					foreach ($rows as $key => $row)
					{
						$instancia[] = $this->GetJson($row);
					}
				}
				return $instancia;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function &GetRows($filtro = false, $sql = "", $defult = false)
		{
			$retorno = false;
			try
			{
				global $wpdb;
				$valor = $defult;
				if((empty($sql))&&(empty($this->Tabela)))
					return $retorno;
				if(empty($sql))
					$sql = "SELECT * FROM {$wpdb->prefix}{$this->Tabela}";
				if(!empty($filtro))
				{
					$onde = self::MontarFiltro($filtro, $this->Tabela, $this->PrimaryKey);
					$sql .= $onde;
				}
				$rows = $wpdb->get_results( $sql, ARRAY_A );
				if($rows)
					return $rows;
				else
					return $retorno;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function &GetRow($filtro = false, $sql = "", $defult = false)
		{
			$retorno = false;
			try
			{
				global $wpdb;
				$valor = $defult;
				if((empty($sql))&&(empty($this->Tabela)))
					return $retorno;
				if(empty($sql))
					$sql = "SELECT * FROM {$wpdb->prefix}{$this->Tabela}";
				if(!empty($filtro))
				{
					$onde = self::MontarFiltro($filtro, $this->Tabela, $this->PrimaryKey);
					$sql .= $onde;
				}
				$valor = $wpdb->get_row( $sql, ARRAY_A );
				if(empty($valor))
					return $retorno;
				return $valor;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function Total($filtro = false, $sql = "", $campo = "CONT")
		{
			$retorno = false;
			try
			{
				global $wpdb;
				if((empty($sql))&&(empty($this->Tabela)))
					return $retorno;
				if((empty($filtro))&&(empty($sql)))
				{
					if(empty($this->dados))
						return $retorno;
					foreach ($this->dados as $key => $value)
					{
						if(!empty($value))
							$filtro[$key] = $value;
					}
				}
				if(empty($sql))
					$sql = "SELECT COUNT(*) AS CONT FROM {$wpdb->prefix}{$this->Tabela}";
				if(!empty($filtro))
				{
					$onde = self::MontarFiltro($filtro, $this->Tabela, $this->PrimaryKey);
					$sql .= $onde;
				}
				return self::GetSqlCampo($sql, $campo, 0);
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function Existe($filtro = false, $sql = "", $campo = "CONT")
		{
			$retorno = false;
			try
			{
				$valor = $this->Total($filtro, $sql, $campo);
				if(empty($valor))
					return $retorno;
				return true;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function Load($filtro = false, $sql = "", $defult = false)
		{
			$retorno = false;
			try
			{
				if((empty($filtro))&&(empty($sql)))
				{
					$id = $this->Get($this->PrimaryKey,0);
					if(empty($id))
						return $retorno;
					else
					{
						$filtro[$this->PrimaryKey] = $id;
					}
				}
				$dados = $this->GetRow($filtro, $sql, $defult);
				if(empty($dados))
					return $retorno;
				@$this->Carregar($dados);
				return true;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function &GetDefault()
		{
			$retorno = false;
			try
			{
				if(empty($this->Tabela))
				{
					return $retorno;
				}
				if((self::$camposdefault == NULL)||(empty(self::$camposdefault[$this->Tabela])))
				{
					$tabela = $this->GetTabela();
					$sql = "SHOW COLUMNS FROM {$tabela}";
					$rows = self::GetRows(false,$sql);
					if(isset($rows))
					{
						foreach($rows as $key => $row)
						{
							$chave = $row['Field'];
							$valor = $row['Default'];
							if($row['Key'] == 'PRI')
								$valor = 0;
							if($valor == NULL)
								$valor = "";
							$retorno[$chave] = $valor;
						}
					}
					if(!empty($retorno))
						self::$camposdefault[$this->Tabela] = $retorno;
				}
				return self::$camposdefault[$this->Tabela];
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function SetDefault($dados = false)
		{
			$retorno = false;
			try
			{
				self::$camposdefault[$this->Tabela] = "";
				if(empty($dados))
				{
					$this->Carregar($this->GetDefault());
				}
				else
				{
					$this->Carregar($dados, $this->GetDefault());
				}
				
				return $retorno;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function SetDados()
		{
			$retorno = false;
			try
			{
				if(empty($this->dados))
				{
					$this->Carregar($this->GetDefault());
				}
				if(is_array($this->dados))
				{
					foreach ($this->dados as $key => $value)
					{
						$this->dados[$key] = $this->Get($key, $value);
					}
					return true;
				}
				return $retorno;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function &GetDados()
		{
			$retorno = false;
			try
			{
				$this->SetDados();
				return $this->dados;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function Carregar( $dados = false, $defult = false)
		{
			try
			{
				if(is_array($defult))
				{
					$dados = self::CompletaArray( $dados, $defult);
				}
				if( is_array( $dados ) )
				{
					foreach( $dados as $key => $value )
					{
						$this->Set($key, $value);
					}
				}
				$id = $this->Get($this->PrimaryKey,0);
				if(empty($id))
					$this->Set($this->PrimaryKey,0);
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
			}
		}
		################################################################################################################
		public static function Query($sql = "", $defult = false)
		{
			global $wpdb;
			if(empty($sql))
				return $defult;
			$query = $wpdb->query($sql);
			return $query;
		}
		################################################################################################################
		public static function GetSqlrow($sql = "", $defult = false)
		{
			global $wpdb;
			if(empty($sql))
				return $defult;
			$row = $wpdb->get_row($sql, ARRAY_A );
			if (isset($row))
			{
				return $row;
			}
			return $defult;
		}
		################################################################################################################
		public static function GetSqlrows($sql = "", $defult = false)
		{
			global $wpdb;
			if(empty($sql))
				return $defult;
			$row = $wpdb->get_results($sql, ARRAY_A );
			if (isset($row))
			{
				return $row;
			}
			return $defult;
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
		public function GetCampo($nome = "", $filtro = "", $sql = "", $defult = false)
		{
			$retorno = false;
			try
			{
				global $wpdb;
				if(empty($nome))
					return $retorno;
				if((empty($sql))&&(empty($this->Tabela)))
					return $retorno;
				if(empty($sql))
					$sql = "SELECT {$nome} FROM {$wpdb->prefix}{$this->Tabela}";
				if(empty($filtro))
				{
					if(empty($this->dados))
						return $retorno;
					foreach ($this->dados as $key => $value)
					{
						if(!empty($value))
							$filtro[$key] = $value;
					}
				}
				if(!empty($filtro))
				{
					$onde = self::MontarFiltro($filtro, $this->Tabela, $this->PrimaryKey);
					$sql .= $onde;
				}
				return self::GetSqlCampo($sql, $nome, 0);
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public static function MontarFiltro($filtro = false, $tabela = "", $chave = "")
		{
			if(is_array($filtro))
			{
				$onde = "";
				foreach ($filtro as $key => $value)
				{
					$onde .= "AND {$key} = '{$value}' ";
				}
				if(!empty($onde))
					$onde = substr($onde, 3);
			}
			elseif(is_numeric($filtro))
			{
				if(empty($chave))
				{
					global $wpdb;
					$sql = "SHOW COLUMNS FROM {$wpdb->prefix}{$tabela} WHERE COLUMNS.Key = 'PRI';";
					$nomeID = self::GetSqlCampo($sql, "Field", "id{$tabela}");
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
			{
				$filtro = $onde;
				$pos = stripos($onde, ' ORDER BY');
				if ($pos !== false)
				{
					$filtro = trim(stristr($onde, ' ORDER BY', true));
				}
				if(!empty($filtro))
					$onde = " WHERE ".$onde;
			}
			return $onde;
		}
		################################################################################################################
		public static function MontarCampos($dados = false)
		{
			if(empty($dados))
				return false;
			if(is_array($dados))
			{
				$lista = "";
				foreach ($dados as $key => $value)
				{
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
		########################################################################################################################
		public static function GetDadosChave(&$dados = false, $chaves = false)
		{
			if(empty($dados))
				return "";
			if(empty($chaves))
				return "";
			foreach($chaves as $chave)
			{
				if(isset($dados[$chave]))
					return $dados[$chave];
			}
			return "";
		}
		#######################################################################################################################
		public function &PreparaDados(&$dados = false)
		{
			$retorno = false;
			if(empty($dados))
				return false;
			$lista = $this->GetDefault();
			$novalista = [];
			foreach ($lista as $key => $value)
			{
				if(!isset($dados[$key]))
					continue;
				$novalista[$key] = $dados[$key];
			}
			return $novalista;
		}
		#######################################################################################################################
		public function Inserir($dados = false)
		{
			global $wpdb;
			if(empty($dados))
				return false;
			
			if(isset($dados[$this->PrimaryKey]))
				unset($dados[$this->PrimaryKey]);
			$dados = $this->PreparaDados($dados);
			$format = false;
			foreach ($dados as $key => $value)
			{
				$format[] = '%s';
			}
			$tabela = $wpdb->prefix.$this->Tabela;
			$id = $wpdb->insert($tabela, $dados, $format);
			if(empty($id))
				return false;
			return $wpdb->insert_id;
		}
		#######################################################################################################################
		public function Atualizar($id, $dados)
		{
			global $wpdb;
			if(is_null($id) || !isset($dados))
				return false;
			if(isset($dados[$this->PrimaryKey]))
				unset($dados[$this->PrimaryKey]);
			$dados = $this->PreparaDados($dados);
			$format = false;
			foreach ($dados as $key => $value)
			{
				$format[] = '%s';
			}
			$tabela = $wpdb->prefix.$this->Tabela;
			$resultado = $wpdb->update($tabela, $dados, array("{$this->PrimaryKey}"=>$id), $format, array('%d'));
			if(!empty($wpdb->last_error))
				return false;
			return $id;
		}
		#######################################################################################################################
		public function &GetById($id = 0)
		{
			$retorno = false;
			try
			{
				if(empty($id))
					return $retorno;
				return $this->FiltroObjeto("{$this->PrimaryKey} = '{$id}'");
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		#######################################################################################################################
		public function &GetAll($filtro = false)
		{
			$retorno = false;
			try
			{
				if(empty($id))
					return $retorno;
				return $this->FiltroObjetos($filtro);
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		#######################################################################################################################
		public function Excluir($id)
		{
			global $wpdb;
			if(empty($id))
				return false;
			$tabela = $wpdb->prefix.$this->Tabela;
			return $wpdb->delete($tabela, array("{$this->PrimaryKey}"=>$id), array('%d'));
		}
		#######################################################################################################################
		public function Apagar()
		{
			$id = $this->GetID();
			return $this->Excluir($id);
		}
		#######################################################################################################################
		public function ApagarLista($filtro = false, $sql = "")
		{
			$objs = $this->FiltroObjetos($filtro, $sql, false);
			if($objs)
			{
				foreach ($objs as $key=>$obj)
				{
					$obj->Apagar();
				}
			}
			return;
		}
		#######################################################################################################################
		public function Salvar()
		{
			$id = $this->GetID();
			$dados = self::CompletaArray( $this->GetDados(), $this->GetDefault(), 3);
			if(empty($id))
				$retorno = $this->Inserir($dados);
			else
				$retorno = $this->Atualizar($id, $dados);
			return $retorno;
		}
		################################################################################################################
		public function GeraOpcoesEnum($valor = "", $tabela = false, $MembroValor = false, $primeiro = false, $Filtro = false)
		{
			if(empty($tabela))
				return "";
			$lista = Componente::ArrayEnum( $tabela, $MembroValor , $Filtro , true);
			if(empty($lista))
				return "";
			return Componente::GeraOpcoesArray($valor, $lista, $primeiro);
		}
		################################################################################################################
		public function GeraOpcoesSql($valor = "", $sql = "", $identificador = "", $texto = "", $primeiro = false)
		{
			$lista = Componente::GeraOpcoesSql($valor, $sql, $identificador, $texto, $primeiro);
			return $lista;
		}
		################################################################################################################
		public function GetDIR($caminho = "")
		{
			$pasta = strtolower($this->Tabela);
			$aURL = HOST_UPLOADS_PATH."arquivos/{$pasta}/{$caminho}";
			return $aURL;
		}
		################################################################################################################
		public function GetDominio($caminho = "")
		{
			$pasta = strtolower($this->Tabela);
			$aURL = HOST_UPLOADS_URL."arquivos/{$pasta}/{$caminho}";
			return $aURL;
		}
		################################################################################################################
		public function GetPadrao($caminho = "", $fisico = false)
		{
			if($fisico)
				$aURL = HOST_IMAGES_PATH."padrao/{$caminho}";
			else
				$aURL = HOST_IMAGES_URL."padrao/{$caminho}";
			return $aURL;
		}
		################################################################################################################
		public function FileExiste($file = "", $caminho = "", $completo = true)
		{
			$file = trim($file);
			if(empty($file))
				return false;
			if(!$completo)
			{
				if(empty($caminho))
					$file = $this->GetDIR($file);
				else
					$file = $caminho.$file;
			}
			if(is_dir($file))
				return false;
			return file_exists($file);
		}
		#######################################################################################################
		public function listatabela($filtro = "", $sql = "", $sqlTotal = "")
		{
			if(empty($sqlTotal))
			{
				$sqlTotal = $sql;
				$limparSql = true;
			}
			else
			{
				$limparSql = false;
			}
			$total = $this->TotalRegistro(false, $sqlTotal, $limparSql);
			$totalfiltro = $this->TotalRegistro($filtro, $sqlTotal, $limparSql);
			$lista = $this->FiltroJson($filtro, $sql);
			$dados = array("draw" => Componente::Request('draw', 0),
				"recordsTotal" => $total,
				"recordsFiltered" => $totalfiltro,
				"data" => $lista);
			return $dados;
		}
		#######################################################################################################
		public function TotalRegistro($filtro = "", $sql = "", $limparsql = false)
		{
			$retorno = 0;
			try
			{
				global $wpdb;
				if((empty($sql))&&(empty($this->Tabela)))
					return $retorno;
				if(!empty($filtro))
				{
					$pos = stripos($filtro, ' ORDER BY');
					if ($pos !== false)
					{
						$filtro = trim(stristr($filtro, ' ORDER BY', true));
					}
				}
				if(empty($sql))
				{
					$sql = "SELECT COUNT(*) CONT FROM {$wpdb->prefix}{$this->Tabela}";
					if(!empty($filtro))
						$sql = $sql." WHERE ".$filtro;
				}
				else
				{
					if($limparsql)
					{
						$expressao = '/SELECT[A-Za-z0-9\-_\w\W\d\D]+FROM/';
						$QueryCont = "SELECT COUNT(*) AS CONT FROM";
						$sql = preg_replace($expressao, $QueryCont, $sql);
					}
					$pos = stripos($sql, ' WHERE');
					if(($pos === false)&&(!empty($filtro)))
					{
						$sql .= " WHERE ";
					}
					if(!empty($filtro))
						$sql = $sql.$filtro;
				}
				return self::GetSqlCampo($sql, "CONT", 0);
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
		################################################################################################################
		public function PrinfCheck($campo = "", $valor = "")
		{
			if(empty($campo))
				return "";
			if(empty($valor))
				return "";
			$aux = $this->Get($campo);
			if(empty($aux))
				return "";
			if($aux == $valor)
				return '  checked="checked"';
			return "";
		}
		################################################################################################################
		public function &GetJson(&$dados = false)
		{
			$retorno = false;
			try
			{
				if(empty($dados))
				{
					$dados = $this->GetDados();
				}
				
				return $dados;
			}
			catch( Exception $e )
			{
				throw new Exception( $e );
				return $retorno;
			}
		}
	}
?>