<?php
       	
return array(
	'dashboard_index' => new Zend_Controller_Router_Route_Static(
		'admin/dashboard',
		array(
			'module'     => 'admin',
			'controller' => 'Dashboard',
			'action'     => 'index'
		)
	),
);