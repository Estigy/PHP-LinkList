<?php

class Frontend_PageController extends Zend_Controller_Action {
	
	public function indexAction()
	{
		$slug = $this->getRequest()->getParam('slug');
		
		if ($slug == 'home') {
			$sidebar = Zend_Registry::get('sidebar');
			$sidebar->setView('_sidebar/home.phtml');
		}
		
		$page = Model_Query_Page::getBySlug($slug);
		
		$this->view->activeMenuItem = 'page-' . $slug;
		
		Core_Service_Meta::getFromRegistry()->addTitlePart($page->title);		
		
		$this->view->page = $page;
	}
	
}
