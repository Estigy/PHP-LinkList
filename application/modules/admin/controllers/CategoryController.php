<?php

class Admin_CategoryController extends Zend_Controller_Action {

	public function init()
	{
		$this->view->fullContent    = true;
		$this->view->activeMenuItem = 'backend';
	}
		
	public function listAction()
	{
		// Marker-Kategorien auslesen (gleich geflattet)
		$categories = Model_Query_Category::getTree(Model_Query_Category::TREE_TYPE_FLAT);
		
		$this->view->categories = $categories;
	}

	public function editAction()
	{
		$request = $this->getRequest();
		
		$id = $request->getParam('id', 0);
		
		if ($id) {
			// Wenn wir eine ID haben, dann das zugehörige Objekt laden
			$item = Model_Query_Category::getById($id);
		} else {
			// Ansonsten einfach ein neues erstellen
			$item = new Model_Category();
		}

		if ($request->isPost()) {
			$item->parent_id   = $request->getParam('parent_id', 0);
			$item->sort        = $request->getParam('sort', 0);
			$item->title       = $request->getParam('title', '');
			$item->slug        = $request->getParam('slug', '');
			$item->subtitle    = $request->getParam('subtitle', '');
			$item->description = $request->getParam('description', '');
			$item->keywords    = $request->getParam('keywords', '');
			$item->status      = $request->getParam('status', '');
			
			Model_Query_Category::save($item);
			
			$this->_helper->FlashMessenger->addMessage('Your changes were saved.');
			//$this->_helper->FlashMessenger->addMessage('There was an Error.', 'error');
			
			$this->_helper->Redirector->gotoRouteAndExit(array('id' => $item->id), 'admin_category_edit');
		}
		
		$this->view->flashMessenger = $this->_helper->getHelper('FlashMessenger');
		
		$this->view->item = $item;
	}
}
