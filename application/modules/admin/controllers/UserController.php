<?php

	class Admin_UserController extends Zend_Controller_Action {

		public function init()
		{
			$this->view->activeMenuItem = 'backend';
		}
		
		public function listAction()
		{
			$request 	= $this->getRequest();
			$pageIndex 	= $request->getParam('pageIndex', 1);
			$perPage    = 2;
			$offset     = ($pageIndex - 1) * $perPage;

			$exp = array();

			$q = null;

			if ($request->isPost()) {
				$filter = (array) $request->getPost('filter', array());

				$filterFields = array('name', 'username', 'role');
				foreach ($filterFields as $field) {
					if ($filter[$field]) {
						$exp[$field] = $filter[$field];
					}
				}

				$q = base64_encode(Zend_Json::encode($exp));

				$this->_helper->Redirector->gotoRouteAndExit(array('q' => $q), 'admin_user_list');
			}

			$q = $request->getParam('q', '');
			if ($q != '') {
				$exp = base64_decode($q);
				$exp = Zend_Json::decode($exp);
			} else {
				$q = base64_encode(Zend_Json::encode($exp));
			}

			$items 	  = Model_Query_User::find($offset, $perPage, $exp);
			$numItems = Model_Query_User::count($exp);

			$this->view->exp      = $exp;
			$this->view->items    = $items;
			$this->view->numItems = $numItems;

			$paginator = new Zend_Paginator(new Core_Utility_PaginatorAdapter($items, $numItems));
			$paginator->setCurrentPageNumber($pageIndex);
			$paginator->setItemCountPerPage($perPage);

			$this->view->paginator = $paginator;
			$this->view->paginatorOptions = array(
				'path' => $this->view->url(array('q' => $q), 'admin_user_list'),
			);
		}

		public function editAction()
		{
			$request = $this->getRequest();

			$id = $request->getParam('id', 0);

			if ($id) {
				// Wenn wir eine ID haben, dann das zugehörige Objekt laden
				$item = Model_Query_User::getById($id);
			} else {
				// Ansonsten einfach ein neues erstellen
				$item = new Model_User();
			}

			if ($request->isPost()) {
				$item->username  = $request->getParam('username', '');
				$item->firstname = $request->getParam('firstname', '');
				$item->lastname  = $request->getParam('lastname', 0);
				$item->email     = $request->getParam('email', '');
				$item->role      = $request->getParam('role', '');
				if ($p = $request->getParam('password', '')) {
					$item->password  = md5($p); // Passwort nur neu zuweisen, wenn tatsächlich übergeben
				}
				
				Model_Query_User::save($item);

				$this->_helper->FlashMessenger->addMessage('Your changes were saved.');

				$this->_helper->Redirector->gotoRouteAndExit(array('id' => $item->id), 'admin_user_edit');
			}

			$this->view->flashMessenger = $this->_helper->getHelper('FlashMessenger');
			$this->view->item           = $item;
		}
	}
