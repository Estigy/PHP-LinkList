<?php
       	
return array(
	'admin_linklist_list' => new Zend_Controller_Router_Route_Regex(
		'admin/linklist/list/?(.*)',
		array(
			'module'     => 'admin',
			'controller' => 'Linklist',
			'action'     => 'list'
		),
		array(
			1 => 'q'
		),
		'admin/linklist/list/%s'
	),
	'admin_linklist_edit' => new Zend_Controller_Router_Route_Regex(
		'admin/linklist/edit/([0-9]+)',
		array(
			'module'     => 'admin',
			'controller' => 'Linklist',
			'action'     => 'edit'
		),
		array(
			1 => 'id'
		),
		'admin/linklist/edit/%d'
	),
	'admin_linklist_sendactivationmail' => new Zend_Controller_Router_Route_Static(
		'admin/linklist/activation-mail',
		array(
			'module'     => 'admin',
			'controller' => 'Linklist',
			'action'     => 'send-activation-mail'
		)
	),

);