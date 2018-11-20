<?php

require_once __DIR__.'/vendor/autoload.php';

// Join the template and local config file. Abort if local config file is missing
$templateFile = dirname(__FILE__).'/protected/config/main.template.php';
$localFile = dirname(__FILE__).'/protected/config/main.php';

if(!file_exists($localFile))
	die("Could not locate local configuration file (main.php).");

// Merge the configs
$template = require($templateFile);
$local = require($localFile);
$config = CMap::mergeArray($template, $local);

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);W

// Enable all errors in PHP
if(YII_DEBUG) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}

Yii::createWebApplication($config)->run();
