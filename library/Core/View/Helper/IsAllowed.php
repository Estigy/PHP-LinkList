<?php

class Core_View_Helper_IsAllowed extends Zend_View_Helper_Abstract
{
	/**
	* Gibt zurück, ob der aktuelle User auf eine bestimmte Action (bzw. ein Privileg) zugreifen darf
	* 
	* @param mixed $action
	* @param mixed $controller
	* @param mixed $module
	* 
	* @return bool
	*/
	public function isAllowed($action, $controller = null, $module = null) 
	{
		// Wenn ein User eingeloggt ist, arbeiten mir mit der Rolle des Users.
		// Ansonsten nehmen wir die Gast-Rolle "guest".
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$role = Zend_Auth::getInstance()->getIdentity()->role;
		} else {
			$role = 'guest';
		}
		
		$action = strtolower($action);
		
		// Modul und Controller nachladen, falls nicht eh übergeben
		if ($controller == null) {
			$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
		}
		if ($module == null) {
			$module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
		}
		
		$isAllowed = Core_Service_Acl::getInstance()->isAllowed($role, $module, $controller, $action);
		
		return $isAllowed;
	}	
}
