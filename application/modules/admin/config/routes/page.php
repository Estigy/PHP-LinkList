<?php
       	
return array(
	'admin_page_list' => new Zend_Controller_Router_Route_Regex(
		'admin/page/list/?(.*)',
		array(
			'module'     => 'admin',
			'controller' => 'Page',
			'action'     => 'list'
		),
		array(
			1 => 'q'
		),
		'admin/page/list/%s'
	),
	'admin_page_edit' => new Zend_Controller_Router_Route_Regex(
		'admin/page/edit/([0-9]+)',
		array(
			'module'     => 'admin',
			'controller' => 'Page',
			'action'     => 'edit'
		),
		array(
			1 => 'id'
		),
		'admin/page/edit/%d'
	),
);