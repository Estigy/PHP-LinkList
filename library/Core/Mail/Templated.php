<?php

/**
* Mail-Klasse, welche den Content aus Layout und View zusammensetzt
*/

class Core_Mail_Templated extends Core_Mail
{
	protected $layoutName = null;
	protected $viewName   = null;
	protected $layout     = null;
	protected $view       = null;
	
	public function __construct($templateName)
	{
		parent::__construct();
		
		$config = Core_Config::getConfig();
		
		$this->layoutName = $config->mailDefaultLayout;
		$this->viewName   = $templateName;
		
		$this->layout = new Zend_Layout();
		$this->layout->setLayoutPath(APPLICATION_PATH . '/layouts');
		$this->layout->config = Core_Config::getConfig();
		
		$this->view = new Zend_View();
		$this->view->setScriptPath(APPLICATION_PATH . '/mails');
		$this->view->config = $config;
	}
	
	public function setLayout($layoutName)
	{
		$this->layoutName = $layoutName;
	}
	
	public function __set($name, $value)
	{
		$this->view->$name = $value;
	}
	
	public function send()
	{
		$this->renderTemplates();
		
		parent::send();
	}
	
	protected function renderTemplates()
	{
		// HTML-Inhalte berechnen, Layout rendern und dem Mail zuweisen
		$this->layout->content = $this->view->render($this->viewName . '-html.phtml');
		$html = $this->layout->render($this->layoutName . '-html');
		$this->setBodyHtml($html);
		
		// PlainText-Inhalte berechnen, Layout rendern und dem Mail zuweisen
		$this->layout->content = $this->view->render($this->viewName . '-txt.phtml');
		$text = $this->layout->render($this->layoutName . '-txt');
		$this->setBodyText($text);
	}
}