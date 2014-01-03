<?php

return array(
	'imageResized' => new Zend_Controller_Router_Route_Regex(
		'cache/files/([a-zA-Z0-9_./% -]+)\.([a-z]+)\.([a-z]+)',
		array(
			'module'     => 'frontend',
			'controller' => 'Pic',
			'action'     => 'standard'
		),
		array(
			1 => 'fileBasename',
			2 => 'size',
			3 => 'extension'
		),
		'cache/files/%s.%s.%s'
	),
);