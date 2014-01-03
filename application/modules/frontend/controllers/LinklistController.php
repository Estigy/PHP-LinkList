<?php

class Frontend_LinklistController extends Zend_Controller_Action {
	
	public function init()
	{
		//$this->view->activeMenuItem = 'home';
	}
	
	public function categoryAction()
	{
		$this->view->sidebarLeft->setView('_sidebar/kategorie-left.phtml');
		$this->view->sidebarRight->setView('_sidebar/kategorie-right.phtml');
		
		$request = $this->getRequest();
	
		$pageIndex = $request->getParam('pageIndex', 1);
		$perPage   = 10;
		$offset    = ($pageIndex - 1) * $perPage;
	
		// Kategorie
		$slug = $request->getParam('slug');
		$category = Model_Query_Category::getBySlug($slug);
		$this->view->category = $category;
		
		Core_Service_Meta::getFromRegistry()->addTitlePart($category->title);
		
		// Unterkategorien
		$exp = array(
			'parent_id' => $category->id,
			'status'    => Model_Category::STATUS_ONLINE,
		);
		$categories = Model_Query_Category::find($offset, $perPage, $exp);
		$this->view->categories = $categories;
		
		// Links
		$exp = array(
			'category_id' => $category->id,
			'status'      => Model_LinkItem::STATUS_ONLINE,
		);
		$items 	  = Model_Query_LinkItem::find($offset, $perPage, $exp);
		$numItems = Model_Query_LinkItem::count($exp);
		
		$this->view->linkItems = $items;
		
		$paginator = new Zend_Paginator(new Core_Utility_PaginatorAdapter($items, $numItems));
		$paginator->setCurrentPageNumber($pageIndex);
		$paginator->setItemCountPerPage($perPage);

		$this->view->paginator = $paginator;
		$this->view->paginatorOptions = array(
			'routeName' => 'linklist_category_pager',
			'routeParams' => array('slug' => $category->slug)
		);
	}
	
	public function detailAction()
	{
		$this->view->sidebarLeft->setView('_sidebar/link-left.phtml');
		$this->view->sidebarRight->setView('_sidebar/link-right.phtml');
		
		$request = $this->getRequest();
		
		// Link-Item
		$id = $request->getParam('id');
		$linkitem = Model_Query_LinkItem::getById($id);
		$this->view->linkitem = $linkitem;
		
		// Stats erhöhen (wenn nicht der Admin eingeloggt ist)
		if (!Zend_Auth::getInstance()->hasIdentity()) {
			Model_Query_LinkStats::increase($linkitem->id);
		}
		
		Core_Service_Meta::getFromRegistry()->addTitlePart($linkitem->title);
		Core_Service_Meta::getFromRegistry()->addKeywords($linkitem->keywords);
		Core_Service_Meta::getFromRegistry()->setDescription($linkitem->description);

		// Kategorie
		/*$slug = $request->getParam('slug');
		$category = Model_Query_Category::getBySlug($slug);
		$this->view->category = $category;*/
	}
	
	public function newAction()
	{
		$this->view->sidebarLeft->setView('_sidebar/linknew-left.phtml');
		$this->view->fullContent    = true;
		$this->view->activeMenuItem = 'newlink';
		
		Core_Service_Meta::getFromRegistry()->addTitlePart('Neuen Link eintragen');
		
		$request = $this->getRequest();
		$categoryId = $request->getParam('category_id');
		
		$category = $categoryId == 0 ? new Model_Category() : Model_Query_Category::getById($categoryId);
		$this->view->category = $category;
		
		$config = Core_Config::getConfig();
		
		$form = new Core_Form();
		
		// Feld-Elemente
		$form->addElement('text', 'title', array(
			'label' => 'Titel',
			'validators' => array( array(
                'StringLength', false, array(6, 255,'messages' => array(
                    Zend_Validate_StringLength::TOO_SHORT => 'Your password is too short.',
                    Zend_Validate_StringLength::TOO_LONG => 'Your password is too long.',
            )))),
			'required' => true,
		))->addElement('text', 'url', array(
			'label'    => 'URL',
			'required' => true,
			'value'    => 'http://',
		))->addElement('select', 'category_id', array(
			'label'        => 'Kategorie',
			'multiOptions' => array(0 => '') + Model_Query_Category::getTree(Model_Query_Category::TREE_TYPE_LABELS),
			'required'     => true,
			'value'        => $category->id,
		))->addElement('textarea', 'description', array(
			'label'      => 'Beschreibung',
			'required'   => true,
			'style'      => 'height:180px',
			'validators' => array( array(
                'StringLength', false, array($config->minLengthDescription, 2048, 'messages' => array(
                    Zend_Validate_StringLength::TOO_SHORT => 'Die Beschreibung ist zu kurz.',
                    Zend_Validate_StringLength::TOO_LONG => 'Die Beschreibung ist zu lang.',
            )))),
		))->addElement('textarea', 'keywords', array(
			'label'      => 'Keywords / Tags',
			'required'   => true,
			'style'      => 'height:180px',
			'validators' => array( array(
                'StringLength', false, array(1, $config->maxLengthKeywords, 'messages' => array(
                    Zend_Validate_StringLength::TOO_SHORT => 'Die Keywords sind zu kurz.',
                    Zend_Validate_StringLength::TOO_LONG  => 'Die Keywords sind lang.',
            )))),
		))->addElement('text', 'contact_name', array(
			'label'    => 'Name',
			'required' => true,
		))->addElement('text', 'contact_address', array(
			'label'    => 'Adresse',
			'required' => true,
		))->addElement('text', 'contact_zip', array(
			'label'    => 'PLZ',
			'required' => true,
		))->addElement('text', 'contact_city', array(
			'label'    => 'Ort',
			'required' => true,
		))->addElement('text', 'contact_country', array(
			'label'    => 'Land',
			'required' => true,
		))->addElement('text', 'contact_phone', array(
			'label' => 'Telefon',
		))->addElement('text', 'contact_person', array(
			'label'    => 'Name',
			'required' => true,
		))->addElement('text', 'contact_email', array(
			'label'    => 'E-Mail',
			'required' => true,
		))->addElement('checkbox', 'contact_forward', array(
			'label' => 'Anfragen weiterleiten',
		));
		
		$this->view->form = $form;

		if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				$linkItem = new Model_LinkItem();
				// Standardwerte übernehmen
				foreach ($form->getValues() as $fieldname => $value) {
					$linkItem->$fieldname = $value;
				}
				// zusätzliche Werte ergänzen
				$linkItem->slug       = Core_Utility_String::makeSlug($linkItem->title);
				$linkItem->date_entry = date('Y-m-d H:i:s');
				$linkItem->status     = Model_LinkItem::STATUS_NEW;

				Model_Query_LinkItem::save($linkItem);

				$config = Core_Config::getConfig();

				$mail = new Core_Mail();
				$mail->addTo($config->mail->newLinkAlertTo);
				$mail->setSubject('Neuer Link eingetragen');
				$mail->setBodyText('Neuer Link auf ' . $config->project->name . ":\n\n" . $linkItem->title);
				$mail->send();

				$this->_helper->Redirector->gotoRouteAndExit(array(), 'linklist_new_success');
			} 
		}
	}
	
	public function newsuccessAction()
	{
		$this->view->sidebarLeft->setView('_sidebar/linknew-left.phtml');
		$this->view->fullContent    = true;
		$this->view->activeMenuItem = 'newlink';
		
		// ansonsten nichts zu tun
	}
	
	public function latestAction()
	{
		Core_Service_Meta::getFromRegistry()->addTitlePart('Neueste Links');
		
		$this->view->activeMenuItem = 'latestlinks';
		
		// Links
		$linkItems = Model_Query_LinkItem::getLatest(0, PHP_INT_MAX);
		$this->view->linkItems = $linkItems;
	}
	
	public function searchAction()
	{
		Core_Service_Meta::getFromRegistry()->addTitlePart('Suche');		
		
		$request    = $this->getRequest();
		$searchterm = $request->getParam('searchterm');
		$pageIndex  = $request->getParam('pageIndex', 1);
		$perPage    = 10;
		$offset     = ($pageIndex - 1) * $perPage;
		
		$exp = array(
			'searchterm' => $searchterm,
			'status'     => Model_LinkItem::STATUS_ONLINE,
		);
		$items 	  = Model_Query_LinkItem::find($offset, $perPage, $exp);
		$numItems = Model_Query_LinkItem::count($exp);
		
		$this->view->linkItems = $items;
		$this->view->searchterm = $searchterm;
		
		$paginator = new Zend_Paginator(new Core_Utility_PaginatorAdapter($items, $numItems));
		$paginator->setCurrentPageNumber($pageIndex);
		$paginator->setItemCountPerPage($perPage);

		$this->view->paginator = $paginator;
		$this->view->paginatorOptions = array(
			'routeName' => 'linklist_search_pager',
			'routeParams' => array('searchterm' => $searchterm)
		);
	}
	
}
