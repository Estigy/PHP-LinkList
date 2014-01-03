<?php

/**
* Eigene Mail-Klasse, basierend auf Zend_Mail
* 
* Setzt automatisch das Encoding auf UTF-8 und lädt den in der application.ini angegebenen Transport-Layer.
*/

class Core_Mail extends Zend_Mail
{
	protected $transport = null;
	
	public function __construct()
	{
		parent::__construct('UTF-8'); // Wir versenden alles als UTF-8
		
		$config = Core_Config::getConfig();
		
		if ($config->mailTransport == 'smtp') {
			$this->mailTransport = new Zend_Mail_Transport_Smtp($config->smtpServer, array(
				'auth'     => 'login',
				'username' => $config->smtpUser,
				'password' => $config->smtpPass
			));
		}
		
		$this->setFrom($config->outMailsSenderEmail, $config->outMailsSenderName);
	}
	
	public function send()
	{
		parent::send($this->transport); // Immer den eigenen Transport-Layer verwenden
	}
}