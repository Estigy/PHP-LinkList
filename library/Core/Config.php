<?php

class Core_Config extends Zend_Config_Ini
{
	protected $sysConfig = array(
		'projectName'              => '[Project Name]',
		'metaKeywords'             => array(),
		'metaDescription'          => 'Alle Infos rund um',
		'headerClaim'              => '[Header Claim]',
		'footerClaim'              => '[Footer Claim]',
		'domain'                   => '[Domain]',
		'googleAdsensePublisherId' => '',
		'adDefinition'             => array(),
		'customCssFile'            => '',
		
		'minLengthDescription'     => 400,
		'maxLengthKeywords'        => 200,
		
		'dbAdapter'                => 'Pdo_Mysql',
		'dbHost'                   => 'localhost',
		'dbUsername'               => '',
		'dbPassword'               => '',
		'dbName'                   => '',
		'dbPort'                   => 3306,
		'dbCharset'                => 'utf8',
		
		'outMailsSenderName'      => '[Sender Name]',
		'outMailsSenderEmail'     => 'office@domain.at',
		'newLinkAlertTo'          => 'office@domain.at',
		'newContactTo'            => 'office@domain.at',
		'mailDefaultLayout'       => 'mail',
		'mailTransport'           => 'sendmail',
		'smtpServer'              => '',
		'smtpUsername'            => '',
		'smtpPassword'            => '',
	);
	
	/**
	 * Gibt die Applikations-Konfiguration als Config-Objekt zurück
	 * 
	 * @return Zend_Config
	 */
	public static function getConfig() 
	{
		if (!Zend_Registry::isRegistered('Core_Config')) {
			$configFile = APPLICATION_PATH . '/config/application.ini';
			$config = new Core_Config($configFile);
			
			Zend_Registry::set('Core_Config', $config);
		}
		
		return Zend_Registry::get('Core_Config');
	}
	
	public function __construct($filename, $section = null, $options = null)
	{
		parent::__construct($filename, $section, $options);
		
		$this->loadLocalConfig();
	}
	
	public function loadLocalConfig()
	{
		if (!file_exists(APPLICATION_PATH . '/../localConfig.php')) {
			return;
		}
		
		include APPLICATION_PATH . '/../localConfig.php';
		
		foreach (array_keys($this->sysConfig) as $variable) {
			if (isset($$variable)) {
				$this->sysConfig[$variable] = $$variable;
			}
		}
	}
	
	public function __get($name)
	{
		return isset($this->sysConfig[$name]) ? $this->sysConfig[$name] : parent::__get($name);
	}
	
	public function __set($name, $value)
	{
		throw new ExceptioN('Setting SysConfig Values is not permitted.');
	}
}
