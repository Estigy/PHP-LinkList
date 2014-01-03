<?php
       	
return array(
	'admin_user_list' => new Zend_Controller_Router_Route_Regex(
		'user/list/?(.*)',
		array(
			'module'     => 'admin',
			'controller' => 'User',
			'action'     => 'list'
		),
		array(
			1 => 'q'
		),
		'user/list/%s'
	),
	'admin_user_list' => new Zend_Controller_Router_Route_Regex(
		'user/list/?(.*)',
		array(
			'module'     => 'admin',
			'controller' => 'User',
			'action'     => 'list'
		),
		array(
			1 => 'q'
		),
		'user/list/%s'
	),
	'admin_user_edit' => new Zend_Controller_Router_Route_Regex(
		'user/edit/([0-9]+)',
		array(
			'module'     => 'admin',
			'controller' => 'User',
			'action'     => 'edit'
		),
		array(
			1 => 'id'
		),
		'user/edit/%d'
	),
);