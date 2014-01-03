<?php

class Core_Controller_Plugin_Init extends Zend_Controller_Plugin_Abstract 
{
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		$config = Core_Config::getConfig();
		
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		$view->doctype('HTML5');
		$view->addHelperPath(LIBRARY_PATH . '/Core/View/Helper', 'Core_View_Helper');
		
		// Das Core-Modul laden
		$view->addHelperPath(APPLICATION_PATH . '/modules/core/views/helpers', 'Core_View_Helper');
		$view->addScriptPath(APPLICATION_PATH . '/modules/core/views/scripts');
		
		$view->headLink()->appendStylesheet('//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css');
		$view->headScript()->appendFile('http://code.jquery.com/jquery-2.0.3.min.js')
		                   ->appendFile('//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js')
		                   ->appendFile('/js/main.js');
		
		$view->getHelper('BaseUrl')->setBaseUrl($config->url);
		
		$view->config = $config;
		
		Core_Service_Meta::getFromRegistry()->addTitlePart($config->projectName);
		Core_Service_Meta::getFromRegistry()->setDefaultDescription($config->metaDescription);
		Core_Service_Meta::getFromRegistry()->setDefaultKeywords($config->metaKeywords);
		
		// Sidebar
		$module = $request->getModuleName();
		if ($module == 'admin') {
			
		} else {
			$view->headLink()->appendStylesheet('/css/style.css');
			if ($config->customCssFile) {
				$view->headlink()->appendStylesheet($config->customCssFile);
			}
			
			Zend_Registry::set('sidebarLeft', new Core_Service_Sidebar('_sidebar/navigation.phtml'));
			Zend_Registry::set('sidebarRight', new Core_Service_Sidebar('_sidebar/empty.phtml'));
			$view->sidebarLeft  = Zend_Registry::get('sidebarLeft');
			$view->sidebarRight = Zend_Registry::get('sidebarRight');
			// Standardmäßig ist der Content nicht übergroß
			$view->fullContent = false;
		}
			
		// Layout wird nur initialisiert, wenn es kein Ajax-Request ist
		// (dann wollen wir ja nur den Haupt-Content)
		if (!$request->isXmlHttpRequest()) {
			Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH . '/layouts'));
			Zend_Layout::getMvcInstance()->setLayout($module == 'admin' ? 'admin' : 'index');
		}
	}
}
