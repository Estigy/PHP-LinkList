<?php
       	
return array(
	'login' => new Zend_Controller_Router_Route_Static(
		'login',
		array(
			'module'     => 'frontend',
			'controller' => 'Auth',
			'action'     => 'login'
		)
	),
	'logout' => new Zend_Controller_Router_Route_Static(
		'logout',
		array(
			'module'     => 'frontend',
			'controller' => 'Auth',
			'action'     => 'logout'
		)
	),
);