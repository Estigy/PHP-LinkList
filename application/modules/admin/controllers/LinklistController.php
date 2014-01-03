<?php

class Admin_LinklistController extends Zend_Controller_Action {

	public function init()
	{
		$this->view->activeMenuItem = 'backend';
	}
	
	public function listAction()
	{
		$request 	= $this->getRequest();
		$pageIndex 	= $request->getParam('pageIndex', 1);
		$perPage    = 20;
		$offset     = ($pageIndex - 1) * $perPage;
		
		$exp = array();
		
		$q = null;
		
		if ($request->isPost()) {
			$filter = (array) $request->getPost('filter', array());
			
			$filterFields = array('status', 'title', 'id', 'category_id', 'contact_name');
			foreach ($filterFields as $field) {
				if ($filter[$field]) {
					$exp[$field] = $filter[$field];
				}
			}
			
			$q = base64_encode(Zend_Json::encode($exp));
			
			$this->_helper->Redirector->gotoRouteAndExit(array('q' => $q), 'admin_linklist_list');
		}
		
		$q = $request->getParam('q', '');
		if ($q != '') {
			$exp = base64_decode($q);
			$exp = Zend_Json::decode($exp);
		} else {
			$q = base64_encode(Zend_Json::encode($exp));
		}
		
		$items 	  = Model_Query_LinkItem::find($offset, $perPage, $exp);
		$numItems = Model_Query_LinkItem::count($exp);
		
		$this->view->exp      = $exp;
		$this->view->q        = $q;
		$this->view->items    = $items;
		$this->view->numItems = $numItems;
		
		$paginator = new Zend_Paginator(new Core_Utility_PaginatorAdapter($items, $numItems));
		$paginator->setCurrentPageNumber($pageIndex);
		$paginator->setItemCountPerPage($perPage);
		
		$this->view->paginator = $paginator;
		$this->view->paginatorOptions = array(
			'path' => $this->view->url(array('q' => $q), 'admin_linklist_list')
		);
	}

	
	public function editAction()
	{
		$request = $this->getRequest();
		
		$id = $request->getParam('id', 0);
		
		if ($id) {
			// Wenn wir eine ID haben, dann das zugehörige Objekt laden
			$linkItem = Model_Query_LinkItem::getById($id);
		} else {
			// Ansonsten einfach ein neues erstellen
			$linkItem = new Model_LinkItem();
		}
		
		if ($request->isPost()) {
			$linkItem->title             = $request->getParam('title', '');
			$linkItem->slug              = $request->getParam('slug', '');
			$linkItem->description       = $request->getParam('description', '');
			$linkItem->keywords          = $request->getParam('keywords', '');
			$linkItem->url               = $request->getParam('url', '');
			$linkItem->category_id       = $request->getParam('category_id', '');
			$linkItem->screenshot        = $request->getParam('screenshot', '');
			$linkItem->contact_name      = $request->getParam('contact_name', '');
			$linkItem->contact_address   = $request->getParam('contact_address', '');
			$linkItem->contact_zip       = $request->getParam('contact_zip', '');
			$linkItem->contact_city      = $request->getParam('contact_city', '');
			$linkItem->contact_country   = $request->getParam('contact_country', '');
			$linkItem->contact_phone     = $request->getParam('contact_phone', '');
			$linkItem->contact_person    = $request->getParam('contact_person', '');
			$linkItem->contact_email     = $request->getParam('contact_email', '');
			$linkItem->contact_forward   = $request->getParam('contact_forward', '');
			$linkItem->status            = $request->getParam('status', '');
			if ($linkItem->id == 0) {
				$linkItem->date_entry    = date('Y-m-d H:i:s');
			}
			$linkItem->date_update       = date('Y-m-d H:i:s');
			
			Model_Query_LinkItem::save($linkItem);
			
			$this->_helper->FlashMessenger->addMessage('Your changes were saved.');
			
			$this->_helper->Redirector->gotoRouteAndExit(array('id' => $linkItem->id), 'admin_linklist_edit');
		}
		
		$this->view->flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->item           = $linkItem;
	}
	
	public function sendActivationMailAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		
		$request = $this->getRequest();
		
		if (!$request->isPost()) {
			return;
		}
		
		$id = $request->getParam('id', 0);

		$linkItem = Model_Query_LinkItem::getById($id);

		$mail = new Core_Mail_Templated('link-activated');
		$mail->setSubject('Link freigeschaltet');
		$mail->addTo($linkItem->contact_email, $linkItem->contact_person);
		$mail->linkItem = $linkItem;
		$mail->send();
		
		$linkItem->date_activationmail = date('Y-m-d H:i:s');
		Model_Query_LinkItem::save($linkItem);

		echo 'OK';
	}
	
}
