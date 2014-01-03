<?php
       	
return array(
	'admin_category_list' => new Zend_Controller_Router_Route_Static(
		'admin/category/list',
		array(
			'module'     => 'admin',
			'controller' => 'Category',
			'action'     => 'list'
		)
	),
	'admin_category_edit' => new Zend_Controller_Router_Route_Regex(
		'admin/category/edit/([0-9]+)',
		array(
			'module'     => 'admin',
			'controller' => 'Category',
			'action'     => 'edit'
		),
		array(
			1 => 'id'
		),
		'admin/category/edit/%d'
	),
);