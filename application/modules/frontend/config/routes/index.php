<?php

return array(
	'index' => new Zend_Controller_Router_Route_Static(
		'/',
		array(
			'module'     => 'frontend',
			'controller' => 'Index',
			'action'     => 'index'
		)
	),
	'contact' => new Zend_Controller_Router_Route_Static(
		'kontakt',
		array(
			'module'     => 'frontend',
			'controller' => 'Index',
			'action'     => 'contact'
		)
	),
);
