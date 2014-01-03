<?php
	
class Core_Service_Acl extends Zend_Acl
{
	protected static $instance = null;
	
	protected $resources = array();
	
	protected $roles = array();
	
	protected $perms = array();
	
	protected $isBuilt = false;
	
	
	/**
	* Gibt die Singleton-Instanz zurück
	*/
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new Core_Service_Acl();
		}
		
		return self::$instance;
	}
	/**
	* Konstruktur - private wegen Singleton
	*/
	private function __construct() { }
	
	/**
	* Setter für Ressourcen-Array
	* 
	* @param array $resources
	*/
	public function setResources($resources)
	{
		$this->resources = $resources;
	}
	
	/**
	* Setter für Rollen-Array
	* 
	* @param array $roles
	*/
	public function setRoles($roles)
	{
		$this->roles = $roles;
	}
	
	/**
	* Setter für Permissions-Array
	* 
	* @param array $perms
	*/
	public function setPermissions($perms)
	{
		$this->perms = $perms;
	}
	
	/**
	* Baut aus den übergebenen Ressourcen, Rollen und PErmissions die tatsächlichen
	* Zend_Acl-Regeln zusammen
	* 
	*/
	public function build()
	{
		// Resourcen anlegen
		foreach ($this->resources as $resource => $privileges) {
			$this->addResource(new Zend_Acl_Resource($resource));
		}
		// Rollen anlegen
		foreach ($this->roles as $role => $roleName) {
			$this->addRole(new Zend_Acl_Role($role));
		}
		// Berechtigungen erzeugen
		foreach ($this->perms as $role => $permissions) {
			foreach ($permissions as $resource => $privileges) {
				$this->allow($role, $resource, $privileges);
			}
		}
		
		$this->isBuilt = true;
	}
	
	/**
	* Darf eine Rolle eine gewisse Action bzw. ein gewisses Privileg aufrufen?
	* 
	* @param mixed $role
	* @param mixed $module
	* @param mixed $controller
	* @param mixed $action Action bzw. Privileg
	*/
	public function isAllowed($role, $module, $controller, $action = null) 
	{
		// Wenn die Rechte noch nicht korrekt erzeugt wurden, werfen wir eine Exception.
		if (!$this->isBuilt) {
			throw new Zend_Acl_Exception('Object has not yet been built');
		}
		
		if ($action != null) {
			$action = strtolower($action);
		}
		$resource = strtolower($module . '_' . $controller);
		
		if ($resource == 'default_error') {
			return true;
		}
		
		// Wenn es die Ressource gar nicht gibt, ist Schluss
		if (!$this->has($resource)) {
			throw new Zend_Acl_Exception('Resource ' . $resource . ' does not exist');
		}
		
		// Wenn es die Action bzw. das Privileg für die Ressource garnicht gibt, werfen wir eine Exception
		if (!array_key_exists($action, $this->resources[$resource])) {
			throw new Zend_Acl_Exception('Privilege ' . $name . ' does not exist in resource ' . $resource);
		}
		
		// Eine unbekannte Rolle? Ende.
		if (!$this->hasRole($role)) {
			throw new Zend_Acl_Exception('Role ' . $role . ' does not exist');
		}
		
		// Die tatsächliche Beantwortung der Frage übernimmt schlussendlich der Zend_Acl-Vorfahre
		return parent::isAllowed($role, $resource, $action);
	}
}