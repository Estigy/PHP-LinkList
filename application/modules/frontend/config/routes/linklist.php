<?php

return array(
	'linklist_category' => new Zend_Controller_Router_Route_Regex(
		'katalog/([a-z0-9_-]+)',
		array(
			'module'     => 'frontend',
			'controller' => 'Linklist',
			'action'     => 'category'
		),
		array(
			1 => 'slug',
		),
		'katalog/%s'
	),
	'linklist_category_pager' => new Zend_Controller_Router_Route_Regex(
		'katalog/([a-z0-9_-]+)/seite-([0-9]+)',
		array(
			'module'     => 'frontend',
			'controller' => 'Linklist',
			'action'     => 'category'
		),
		array(
			1 => 'slug',
			2 => 'pageIndex'
		),
		'katalog/%s/seite-%d'
	),
	'linklist_details' => new Zend_Controller_Router_Route_Regex(
		'katalog/([a-z0-9_-]+)/([a-z0-9_-]+)--([0-9]+)\.html',
		array(
			'module'     => 'frontend',
			'controller' => 'Linklist',
			'action'     => 'detail'
		),
		array(
			1 => 'category_slug',
			2 => 'link_slug',
			3 => 'id'
		),
		'katalog/%s/%s--%d.html'
	),
	'linklist_new' => new Zend_Controller_Router_Route_Regex(
		'link-eintragen/([0-9]+)',
		array(
			'module'     => 'frontend',
			'controller' => 'Linklist',
			'action'     => 'new'
		),
		array(
			1 => 'category_id'
		),
		'link-eintragen/%d'
	),
	'linklist_new_success' => new Zend_Controller_Router_Route_Static(
		'link-eintragen-erfolg',
		array(
			'module'     => 'frontend',
			'controller' => 'Linklist',
			'action'     => 'newsuccess'
		)
	),
	'linklist_latest' => new Zend_Controller_Router_Route_Static(
		'neueste-links',
		array(
			'module'     => 'frontend',
			'controller' => 'Linklist',
			'action'     => 'latest'
		)
	),
	'linklist_search' => new Zend_Controller_Router_Route_Static(
		'suche',
		array(
			'module'     => 'frontend',
			'controller' => 'Linklist',
			'action'     => 'search'
		)
	),
	'linklist_search_pager' => new Zend_Controller_Router_Route_Regex(
		'suche/([a-z0-9_-]+)/seite-([0-9]+)',
		array(
			'module'     => 'frontend',
			'controller' => 'Linklist',
			'action'     => 'search'
		),
		array(
			1 => 'searchterm',
			2 => 'pageIndex'
		),
		'suche/%s/seite-%d'
	),
	
);