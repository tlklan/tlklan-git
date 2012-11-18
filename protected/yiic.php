<?php

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../framework/yiic.php';

require_once($yiic);

// Join the template and local config file. Abort if local config file is missing
$templateFile = dirname(__FILE__).'/config/console.template.php';
$localFile = dirname(__FILE__).'/config/console.php';

if(!file_exists($localFile))
	die("Could not locate local configuration file (main.php).");

// Merge the configs
$template = require($templateFile);
$local = require($localFile);
$config = CMap::mergeArray($template, $local);