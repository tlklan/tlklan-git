<?php

require_once __DIR__.'/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from .env if present
if (is_readable(__DIR__ . '/../.env')) {
	Dotenv::create(__DIR__.'/../')->load();
}

$yiic= __DIR__ .'/../vendor/yiisoft/yii/framework/yiic.php';

$config = require __DIR__.'/config/console.php';

require_once($yiic);
