<?php

define('PROJECT_PATH',     realpath(dirname(__FILE__)));
define('APPLICATION_PATH', PROJECT_PATH . '/application');
define('LIBRARY_PATH',     PROJECT_PATH . '/library');
define('MODEL_PATH',       PROJECT_PATH . '/application/models');

// Library und Models in den Include-Path aufnehmen
set_include_path(implode(PATH_SEPARATOR, array(LIBRARY_PATH, MODEL_PATH, '.')));

// Zeitzone einstellen
date_default_timezone_set('Europe/Vienna');

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    'production',
    APPLICATION_PATH . '/config/application.ini'
);

$options = array(
	'bootstrap' => array(
    	'path' 	=> APPLICATION_PATH . '/Bootstrap.php',
		'class' => 'Bootstrap',
    ),
	'resources' => array(
		'frontController' => array(
			'controllerDirectory' => APPLICATION_PATH . '/controllers',
			'moduleDirectory' 	  => APPLICATION_PATH . '/modules',
		),
	),
);
$options = $application->mergeOptions($application->getOptions(), $options);

header('Content-Type: text/html; charset=utf-8');

$application->setOptions($options)
            ->bootstrap()
            ->run();
