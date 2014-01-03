<?php

return array(
	'page' => new Zend_Controller_Router_Route_Regex(
		'p/([a-z0-9_-]+)',
		array(
			'module'     => 'frontend',
			'controller' => 'Page',
			'action'     => 'index'
		),
		array(
			1 => 'slug'
		),
		'p/%s'
	),
);