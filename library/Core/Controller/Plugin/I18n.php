<?php

class Core_Controller_Plugin_I18n extends Zend_Controller_Plugin_Abstract 
{
	public function routeShutdown(Zend_Controller_Request_Abstract $request) 
	{
		$config = Core_Config::getConfig();
		
		$language = $request->getParam('lang');
		
		// Wenn die Sprache nicht übergeben wurde oder nicht in der Liste der möglichen Sprachen vorkommt,
		// dann nehmen wir die Default-Sprache und speisen die zurück in den Request. Dadurch haben wir ab
		// hier immer eine gültige Sprache im Request 
		if (!in_array($language, $config->i18n->langs->toArray())) {
			$language = $config->i18n->default;
			$request->setParam('lang', $language);
		}
		
		$options = array(
			'adapter'        => 'array',
			'content'        => APPLICATION_PATH . '/languages',
			'scan'           => Zend_Translate::LOCALE_DIRECTORY,
			'locale'         => $language,
			'disableNotices' => true,
			'ignore'         => '===', // standardmäßig ein Punkt, was manchmal zu Problemen führt
		);

		$translate = new Zend_Translate($options);
		Zend_Registry::set('Zend_Translate', $translate);
		
		$locale = new Zend_Locale($translate->getLocale());
		Zend_Registry::set('Zend_Locale', $locale);
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		$view->assign('translate', Zend_Registry::get('Zend_Translate'));
	}
	
}
