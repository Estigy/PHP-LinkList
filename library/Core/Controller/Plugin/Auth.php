<?php

class Core_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		$uri = $request->getRequestUri();
		$uri = strtolower($uri);
		
		$module = $request->getModuleName();
		
		// (Im Frontend gibts keine Zugangsbeschränkungen)
		if ($module == 'frontend') {
			return;
		}
		
		$auth = Zend_Auth::getInstance();
		
		$isAllowed = false;
		if ($auth->hasIdentity()) {
			$user 		= $auth->getIdentity();
			$controller = $request->getControllerName();
			$action 	= $request->getActionName();
			
			// Eingeloggte User dürfen immer zum Dashboard (schließlich werden sie dorthin nach dem Login weitergeleitet)
			if ('admin_dashboard_index' == strtolower($module . '_' . $controller . '_' . $action)) {
				$isAllowed = true;
			} else {
				$isAllowed = Core_Service_Acl::getInstance()->isAllowed($user->role, $module, $controller, $action);
			}
		}
		
		if (!$isAllowed) {
			var_dump($request);
			die('Es ist ein Fehler aufgetreten. Möglicherweise keine Rechte?');
			$forwardAction = Zend_Auth::getInstance()->hasIdentity() ? 'deny' : 'login';
			
			$request->setModuleName('frontend')
					->setControllerName('Auth')
					->setActionName($forwardAction)
					->setDispatched(true);
		}
	}
}
