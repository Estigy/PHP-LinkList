<?php

class Core_Module_Loader 
{
	/**
	 * @var Core_Module_Loader
	 */
	private static $_instance;
	
	/**
	 * @var array
	 */
	private $_moduleNames = array('admin', 'frontend');
	
	/**
	 * @return Core_Module_Loader
	 */
	public static function getInstance() 
	{
		if (self::$_instance == null) {
			self::$_instance = new self();
		}
		return self::$_instance;		
	}
	
	public function getModuleNames() 
	{
		return $this->_moduleNames;
	}
	
	/**
	 * @return Zend_Controller_Router_Interface
	 */
	public function getRoutes() 
	{
		$router = new Zend_Controller_Router_Rewrite();
		
		foreach ($this->_moduleNames as $name) {
			$configFiles = $this->_loadRouteConfigs($name);
			
			foreach ($configFiles as $file) {
				$routes = include $file;
				$router->addRoutes($routes);
			}
		}
		
		return $router;
	}
	
	/**
	 * @return array
	 */
	public function getAclResources()
	{
		$res = array();
		
		foreach ($this->_moduleNames as $module) {
			$configFile = APPLICATION_PATH . '/modules/' . $module . '/config/permissions.ini';
			$config = new Zend_Config_Ini($configFile);
			foreach ($config as $resource => $privileges) {
				foreach ($privileges as $privilege => $name) {
					$res[$module . '_' . $resource][$privilege] = $name;
				}
			}
		}
		
		return $res;
	}
	
	/**
	 * @return array
	 */
	protected function _loadRouteConfigs($moduleName) 
	{
		$dir = APPLICATION_PATH . '/modules/' . $moduleName . '/config/routes';
		if (!is_dir($dir)) {
			return array();
		}
		
		$configFiles = array();
		
		$dirIterator = new DirectoryIterator($dir);
		foreach ($dirIterator as $file) {
            if ($file->isDot() || $file->isDir() || !preg_match('#\.php$#', $file->getFilename())) {
                continue;
            }
            $configFiles[] = $dir . '/' . $file->getFilename();
        }
		
		return $configFiles;
	}	
}
