<?php
class Frontend_IndexController extends Zend_Controller_Action {
	
	public function indexAction()
	{
		$this->view->sidebarLeft->setView('_sidebar/home-left.phtml');
		$this->view->sidebarRight->setView('_sidebar/home-right.phtml');
		
		Core_Service_Meta::getFromRegistry()->addTitlePart('Home');
		
		$this->view->activeMenuItem = 'home';
		
		$filter = array(
			'parent_id' => 0,
			'status'    => Model_Category::STATUS_ONLINE,
		);
		$categories = Model_Query_Category::find(0, PHP_INT_MAX, $filter);
		
		// Newest Links
		$latestLinkitems = Model_Query_LinkItem::getLatest(0, 5);
		$this->view->latestLinkitems = $latestLinkitems;
		
		$this->view->categories = $categories;
	}
	
	public function contactAction()
	{
		Core_Service_Meta::getFromRegistry()->addTitlePart('Kontakt');
		$this->view->activeMenuItem = 'contact';
		
		$request = $this->getRequest();
		$form = new Core_Form();
		
		// Feld-Elemente
		$form->addElement('text', 'subject', array(
			'label'      => 'Betreff',
			'required'   => true,
			'validators' => array( array(
				'StringLength', false, array(6, 255, 'messages' => array(
					Zend_Validate_StringLength::TOO_SHORT => 'Der Betreff ist zu kurz.',
					Zend_Validate_StringLength::TOO_LONG => 'Der Betreff ist zu lang.',
			)))),
		))->addElement('textarea', 'message', array(
			'label'      => 'Nachricht',
			'required'   => true,
			'rows'       => 5,
			'validators' => array( array(
				'StringLength', false, array(10, 2048, 'messages' => array(
					Zend_Validate_StringLength::TOO_SHORT => 'Die Nachricht ist zu kurz.',
					Zend_Validate_StringLength::TOO_LONG => 'Die Nachricht ist zu lang.',
			)))),
		))->addElement('text', 'name', array(
			'label'    => 'Ihr Name',
			'required' => true,
		))->addElement('text', 'email', array(
			'label'    => 'Ihre E-Mail',
			'required' => true,
		));
		
		$this->view->form = $form;
		
		if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				$config = Core_Config::getConfig();
				
				$values = $form->getValues();
				
				$text = <<<ENDE
Neue Kontaktanfrage von {$config->project->name}:

Name: {$values['name']}
E-Mail: {$values['email']}

Betreff: {$values['subject']}

{$values['message']}
ENDE;
				$mail = new Core_Mail();
				$mail->addTo($config->mail->newContactTo);
				$mail->setSubject('Kontaktanfrage');
				$mail->setBodyText($text);
				$mail->send();

				$this->_helper->FlashMessenger->addMessage('Ihre Kontaktanfrage wurde erfolgreich verschickt.');
			} 
		}
		
		$this->view->flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}	
	
}
