<?php

class Admin_PageController extends Zend_Controller_Action {

	public function init()
	{
		$this->view->activeMenuItem = 'backend';
	}
	
	public function listAction()
	{
		// Marker-Kategorien auslesen (gleich geflattet)
		$pages = Model_Query_Page::getTree(Model_Query_Page::TREE_TYPE_FLAT);
		
		$this->view->pages = $pages;
	}

	public function editAction()
	{
		$request = $this->getRequest();
		
		$id = $request->getParam('id', 0);
		
		if ($id) {
			// Wenn wir eine ID haben, dann das zugehörige Objekt laden
			$page = Model_Query_Page::getById($id);
		} else {
			// Ansonsten einfach ein neues erstellen
			$page = new Model_Page();
		}
		
		if ($request->isPost()) {
			$page->title       = $request->getParam('title', '');
			$page->slug        = $request->getParam('slug', '');
			$page->status      = $request->getParam('status', '');
			$page->parent_id   = $request->getParam('parent_id', 0);
			$page->text        = $request->getParam('text', '');
			$page->description = $request->getParam('description', '');
			$page->keywords    = $request->getParam('keywords', '');
			
			Model_Query_Page::save($page);
			
			$this->_helper->FlashMessenger->addMessage('Your changes were saved.');
			
			$this->_helper->Redirector->gotoRouteAndExit(array('id' => $page->id), 'admin_page_edit');
		}
		
		$this->view->flashMessenger = $this->_helper->getHelper('FlashMessenger');
		
		$this->view->page = $page;
	}
}
