<?php

	class Admin_DashboardController extends Zend_Controller_Action {
		
		public function init()
		{
			$this->view->fullContent    = true;
			$this->view->activeMenuItem = 'backend';
		}

		public function indexAction()
		{
			// Statistik Links
			$this->view->countLinksOnline = Model_Query_LinkItem::count(array('status' => Model_LinkItem::STATUS_ONLINE));
			$this->view->countLinksNew    = Model_Query_LinkItem::count(array('status' => Model_LinkItem::STATUS_NEW));
			$this->view->countLinksBanned = Model_Query_LinkItem::count(array('status' => Model_LinkItem::STATUS_BANNED));
			
			// Kategorien
			$this->view->countCategoriesOnline = Model_Query_Category::count(array('status' => Model_Category::STATUS_ONLINE));
			$this->view->countCategoriesOffline = Model_Query_Category::count(array('status' => Model_Category::STATUS_OFFLINE));
			
			// Meist angesehene Links
			$this->view->linksByViews = Model_Query_LinkItem::find(0, 10, array('status' => Model_LinkItem::STATUS_ONLINE, 'topViewed' => true));
		}
	}
