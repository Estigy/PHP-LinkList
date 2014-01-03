<?php

class Frontend_AuthController extends Zend_Controller_Action {
	
	public function loginAction() 
	{
		$auth = Zend_Auth::getInstance();
		
		// Wenn der User eh eingeloggt ist, gleich zur Startseite weiterleiten
		if ($auth->hasIdentity()) {
			$this->_redirect($this->view->serverUrl() . $this->view->url(array(), 'index'));
		}
		
		$form = new Core_Form();
		
		// Feld-Elemente
		$form->addElement('text', 'username', array(
			'label'       => 'Benutzername',
			'required'    => true,
			'class'       => 'form-control',
		))->addElement('password', 'password', array(
			'label'      => 'Passwort',
			'required'   => true,
			'class'      => 'form-control',
		));
		
		$this->view->form = $form;		
		
		$this->view->error = false;
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				$values = $form->getValues();
				
				$adapter  = new Core_Service_Auth($values['username'], $values['password']);
				$result   = $auth->authenticate($adapter);
				
				switch ($result->getCode()) {
					 // Erfolgreich eingeloggt
					case Zend_Auth_Result::SUCCESS:
						$user = $auth->getIdentity();
						$this->_redirect($this->view->serverUrl() . $this->view->url(array(), 'dashboard_index'));
						break;
						
					// User nicht gefunden, Passwort falsch oder sonstiges
					case Zend_Auth_Result::FAILURE:
					default:
						$this->view->error = true;
						break;
				}
			}
		}
	}
	
	public function logoutAction() 
	{
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$user = $auth->getIdentity();
			
			// User ausloggen
			$auth->clearIdentity();
			
			// Session leeren
			Zend_Session::destroy(false, false);
		}
		$this->_redirect($this->view->baseUrl() . $this->view->url(array(), 'index'));
	}
	
	public function denyAction()
	{
		
	}
}