<?php
class Pagseguro
{
	private static $pagseguroAccount = null;
	private static $pagseguroToken = null;
	################################################################################################################
	function __construct()
	{
		require_once HOST_LIBRARIES_PATH."PagSeguroLibrary/PagSeguroLibrary.php";
		self::$pagseguroAccount = PagSeguroConfig::getData('credentials','email');
		self::$pagseguroToken = PagSeguroConfig::getData('credentials','token');
	}
	public function pagamentoseguro($idfatura)
	{
		$this->data['hasError'] = false;
		$this->data['errorList'] = array();
		
		$venda = Componente::GetInstancia("fatura");
		$venda->idfatura = $idfatura;
		// validações
		if(!$venda->Load())
		{
			$this->data['hasError'] = true;
			$this->data['errorList'][] = array('message' => 'Não foi possível localizar sua compra.');
		}
		else
		{
			$empresa = $venda->GetEmpresa();
			if(!$empresa)
			{
				$this->data['hasError'] = true;
				$this->data['errorList'][] = array('message' => 'Não foi possível localizar seu dados.');
			}
			$plano = $venda->GetPlano();
			if(!$plano)
			{
				$this->data['hasError'] = true;
				$this->data['errorList'][] = array('message' => 'Não foi possível localizar seu dados.');
			}
		}
		
		if(!$this->data['hasError'])
		{
			// Instantiate a new payment request
			$paymentRequest = new PagSeguroPaymentRequest();
			// Sets the currency
			$paymentRequest->setCurrency ( "BRL" );
			
			
			// Sets a reference code for this payment request, it is useful to
			// identify this payment in future notifications.
			$paymentRequest->setReference ( $venda->idfatura );
			
			// Add an item for this payment request
			$paymentRequest->addItem ( $venda->idplano, $plano->nomedoplano, 1, $venda->GetValorPagamento() );
			
			$paymentRequest->setShippingType ( 3 );
			$paymentRequest->setShippingAddress ( $empresa->Getcep()
				, $empresa->GetEnderecoPagamento()
				, $empresa->Getnumero()
				, $empresa->Getcomplemento()
				, $empresa->Getbairro()
				, $empresa->GetCidadePagamento()
				, $empresa->Getestadosigla(), 'BRA' );
			
			// Sets your customer information.
			$telefone = $empresa->GetFone();
			$paymentRequest->setSenderName($empresa->GetnomePagamento());
			$paymentRequest->setSenderEmail($empresa->email);
			$paymentRequest->setSenderPhone($empresa->CodigoArea($telefone), $empresa->NumTelefone($telefone));
			
			$paymentRequest->setRedirectUrl ( site_url('retornopagamento.php') );
			$paymentRequest->setMaxAge(86400 * 3);
			
			try
			{
				$credentials = new PagSeguroAccountCredentials( self::$pagseguroAccount, self::$pagseguroToken );
				
				$url = $paymentRequest->register( $credentials );//Componente::P($url, $credentials);exit();
				$venda->AtualizaPagamento();
				Componente::Redireciona( $url, true );
				
			}
			catch( PagSeguroServiceException $e )
			{//Componente::P($e);
				$this->data['hasError'] = true;
				$this->data['errorList'][] = array('message' => 'Ocorreu um erro ao comunicar com o Pagseguro.' .$e->getCode() . ' - ' .  $e->getMessage());
			}
		}
		//var_dump($this->data['errorList']);
	}	
	/**
	 * retornoPagamentoPagseguro
	 *
	 * Recebe o retorno de pagamento da promoção via pagseguro
	 * @access public
	 * @return void
	 */
	public function retornopagamento()
	{
		$transaction = false;
		$idTransacao = Componente::Request('idTransacao',0);
		// Verifica se existe a transação
		if(!empty($idTransacao))
		{
			$transaction = self::TransactionRetorno($idTransacao);
		}
		// Se a transação for um objeto
		if(is_object( $transaction ))
		{
			$id = self::setTransacaoPagseguro($transaction);
		}
		if(empty($id))
		{
			$user = wp_get_current_user();
			if(!empty($user))
			{
				$id = $user->ID;
				if(!empty($id))
				{
					$fatura = Componente::GetInstancia("fatura");
					$fatura = $fatura->UltimaFatura($id);
					if(!$fatura)
					{
						$id = $fatura->idfatura;
					}
				}
			}
		}
		$url = site_url('logar');
		Componente::Redireciona( $url, true );
	}	
	/**
	 * setTransacaoPagseguro
	 *
	 * Seta os status da transação vindas do Pagseguro
	 *
	 * @param array $transaction
	 * @return void
	 */
	private function setTransacaoPagseguro($transaction = null)
	{
		// Pegamos o objeto da transação
		$transactionObj = self::getTransaction( $transaction );
		
		// Buscamos a venda
		$venda = Componente::GetInstancia("fatura");
		
		$venda->idfatura = $transactionObj['reference'];
		
		// existindo a venda
		if($venda->Load())
		{
			// Aguardando pagamento
			if ($transactionObj['status'] == 1)
			{
				$dados = array(
					'status' => $venda->GetStatus(1)
				,'idtransacao' => $transaction->getCode()
				,'dataatualizacao' => date('Y-m-d H:i:s')
				);
				
				$venda->Atualizar($venda->idfatura, $dados);
			} // Aguardando aprovação
			elseif($transactionObj['status'] == 2)
			{
				$dados = array(
					'status' => $venda->GetStatus(2)
				,'idtransacao' => $transaction->getCode()
				,'dataatualizacao' => date('Y-m-d H:i:s')
				);
				
				$venda->Atualizar($venda->idfatura, $dados);
			}// Transação paga
			elseif($transactionObj['status'] == 3)
			{
				$lastEvent = strtotime($transaction->getLastEventDate());
				$dados = array(
					'status' => $venda->GetStatus(3)
				,'valorpago' =>  $transaction->getGrossAmount()
				,'taxas' => $transaction->getFeeAmount()
				,'idtransacao' => $transaction->getCode()
				,'dataatualizacao' => date('Y-m-d H:i:s')
				,'pagaem' => date('Y-m-d H:i:s', $lastEvent)
				);
				$venda->Atualizar($venda->idfatura, $dados);
				if($venda->Load($venda->idfatura))
					$venda->AtualizaPagamento();
			}// Pagamento cancelado
			elseif($transactionObj['status'] == 7 && $venda->status != "Pago")
			{
				$dados = array(
					'status' => $venda->GetStatus(7)
				,'taxas' => $transaction->getFeeAmount()
				,'idtransacao' => $transaction->getCode()
				,'dataatualizacao' => date('Y-m-d H:i:s')
				);
				$venda->Atualizar($venda->idfatura, $dados);
			} // Aguardando aprovação
			elseif($transactionObj['status'] != 7)
			{
				$dados = array(
					'status' => $venda->GetStatus($transactionObj['status'])
				,'idtransacao' => $transaction->getCode()
				,'dataatualizacao' => date('Y-m-d H:i:s')
				);
				
				$venda->Atualizar($venda->idfatura, $dados);
			}
			return $venda->idfatura;
		}
		return 0;
	}	
	/**
	 * getTransaction
	 *
	 * Método para buscar a transação no pag reguto
	 * @access public
	 * @param PagSeguroTransaction $transaction
	 * @return array
	 */
	public static function getTransaction(PagSeguroTransaction $transaction)
	{
		return array ('reference'=>$transaction->getReference(), 'status'=>$transaction->getStatus()->getValue() );
	}	
	/**
	 * NotificationListener
	 *
	 * Recebe as notificações do pagseguro sobre atualização de pagamento.
	 * @access public
	 * @return bool
	 */
	public function notificacoes()
	{
		
		$code = (isset( $_POST['notificationCode'] ) && trim( $_POST['notificationCode'] ) !== "" ? trim( $_POST['notificationCode'] ) : null);
		$type = (isset( $_POST['notificationType'] ) && trim( $_POST['notificationType'] ) !== "" ? trim( $_POST['notificationType'] ) : null);
		$transaction = false;
		
		if ($code && $type)
		{
			
			$notificationType = new PagSeguroNotificationType ( $type );
			$strType = $notificationType->getTypeFromValue ();
			
			switch ($strType)
			{
				
				case 'TRANSACTION' :
					$transaction = self::TransactionNotification ( $code );
					break;
				
				default :
					LogPagSeguro::error ( "Unknown notification type [" . $notificationType->getValue () . "]" );
				
			}
		}
		else
		{
			
			LogPagSeguro::error ( "Invalid notification parameters." );
			self::printLog ();
		}
		
		if (is_object ( $transaction ))
		{
			self::setTransacaoPagseguro($transaction);
		}
		return TRUE;
	}	
	/**
	 * TransactionNotification
	 *
	 * Recupera a transação através de uma notificação
	 * @access private
	 * @param unknown_type $notificationCode
	 * @return Ambigous <a, NULL, PagSeguroTransaction>
	 */
	
	private static function TransactionNotification($notificationCode)
	{
		$credentials = new PagSeguroAccountCredentials ( self::$pagseguroAccount, self::$pagseguroToken );
		try
		{
			$transaction = PagSeguroNotificationService::checkTransaction( $credentials, $notificationCode );
		}
		catch( PagSeguroServiceException $e )
		{
			die ( $e->getMessage () );
		}
		
		return $transaction;
	}
	private static function TransactionRetorno($notificationCode)
	{
		$credentials = new PagSeguroAccountCredentials ( self::$pagseguroAccount, self::$pagseguroToken);
		try
		{
			$transaction = PagSeguroTransactionSearchService::searchByCode( $credentials, $notificationCode );
		}
		catch( PagSeguroServiceException $e )
		{
			die ( $e->getMessage () );
		}
		
		return $transaction;
	}	
	/**
	 * Método que registra logs do pagseguro
	 * @access private
	 * @param String $strType
	 */
	private static function printLog($strType = null)
	{
		$count = 30;
		echo "<h2>Receive notifications</h2>";
		if ($strType) {
			echo "<h4>notifcationType: $strType</h4>";
		}
		echo "<p>Last <strong>$count</strong> items in <strong>log file:</strong></p><hr>";
		echo LogPagSeguro::getHtml ( $count );
	}
}
?>