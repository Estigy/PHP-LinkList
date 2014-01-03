<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Register auto loader
	 * 
	 * @return void
	 */
	protected function _initAutoload()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		
		// Eigene Bibliothek
		$autoloader->registerNamespace('Core_');
		
		// Model-Klassen
		$autoloader->pushAutoloader(array('Core_Model_Loader', 'autoload'), 'Model');
		
		// Module
		new Zend_Loader_Autoloader_Resource(array('basePath'  => APPLICATION_PATH . '/modules/admin', 'namespace' => 'Admin'));
		new Zend_Loader_Autoloader_Resource(array('basePath'  => APPLICATION_PATH . '/modules/frontend', 'namespace' => 'Frontend'));
		
		return $autoloader;
	}

	/**
	 * Routen initialisieren
	 * 
	 * @return void
	 */
	protected function _initRoutes()
	{
		$this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');
        
		$routes = Core_Module_Loader::getInstance()->getRoutes();
		
		$front->setRouter($routes);
		
		// Default-Route deaktivieren
		$front->getRouter()->removeDefaultRoutes();
	}
	
	protected function _initPermissions()
	{
		$this->bootstrap('Db');
		
		$acl = Core_Service_Acl::getInstance();
		
		$res = Core_Module_Loader::getInstance()->getAclResources();
		$acl->setResources($res);
		
		$aclConfig = new Zend_Config_Ini(APPLICATION_PATH . '/config/acl.ini');
		
		$roles = $aclConfig->roles->toArray();
		$acl->setRoles($roles);
		
		$perms = $aclConfig->perms->toArray();
		$acl->setPermissions($perms);
		
		$acl->build();
	}
	
	/**
	 * Init session
	 * 
	 * @return void
	 */
	protected function _initSession()
	{
		/** 
		 * Registry session handler 
		 */
		if (isset($_GET['PHPSESSID'])) {
			session_id($_GET['PHPSESSID']);
		} else if (isset($_POST['PHPSESSID'])) {
			session_id($_POST['PHPSESSID']);
		}
	}
	
	protected function _initDb()
	{
		$config = Core_Config::getConfig();
		
		$db = Zend_Db::factory($config->dbAdapter, array(
			'host'     => $config->dbHost,
			'username' => $config->dbUsername,
			'password' => $config->dbPassword,
			'dbname'   => $config->dbName,
			'port'     => $config->dbPort,
			'charset'  => $config->dbCharset
		));
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		Core_Model_Query::setAdapter($db);
	}
	
	/**
	 * Register plugins
	 * 
	 * @return void
	 */
	protected function _initPlugins()
	{
		$this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');
        
		$front->registerPlugin(new Core_Controller_Plugin_Init())
		      ->registerPlugin(new Core_Controller_Plugin_I18n())
		 	  ->registerPlugin(new Core_Controller_Plugin_Auth());
	}    
}
