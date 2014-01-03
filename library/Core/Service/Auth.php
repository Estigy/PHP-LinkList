<?php

class Core_Service_Auth implements Zend_Auth_Adapter_Interface
{
	private $username;
	private $password;
	
	public function __construct($username, $password) 
	{
		$this->username = $username;
		$this->password = $password;
	}
	
	/**
     * Versucht eine Authentifizierung mit den angegebenen Daten
     *
     * @return Zend_Auth_Result
     */
    public function authenticate() 
    {    	
		$user = Model_Query_User::findByLogin($this->username, $this->password);
		
		if (null == $user) {
			// Kein User gefunden? Also Fehler zurückgeben
			return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null);
		}
		
		// Wenn wir den User gefunden haben, dann korrektes Result zurückliefern
    	return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user);
    }		
	
}