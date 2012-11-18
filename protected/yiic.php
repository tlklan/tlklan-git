<?php

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../framework/yiic.php';

/**
 * Copied from the Yii framework (CMap::mergeArray())
 */
function mergeArray($a, $b)
{
	foreach ($b as $k=> $v)
	{
		if (is_integer($k))
			$a[] = $v;
		else if (is_array($v) && isset($a[$k]) && is_array($a[$k]))
			$a[$k] = mergeArray($a[$k], $v);
		else
			$a[$k] = $v;
	}
	return $a;
}

// Join the template and local config file. Abort if local config file is missing
$templateFile = dirname(__FILE__).'/config/console.template.php';
$localFile = dirname(__FILE__).'/config/console.php';

if(!file_exists($localFile))
	die("Could not locate local configuration file (main.php).");

// Merge the configs
$template = require($templateFile);
$local = require($localFile);
$config = mergeArray($template, $local);

require_once($yiic);