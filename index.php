<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from .env if present
if (is_readable(__DIR__ . '/.env')) {
	Dotenv::create(__DIR__)->load();
}

// Enable error reporting and display errors eventually
error_reporting(E_ALL);

if (getenv('APP_ENV') === 'local') {
	ini_set('display_errors', 1);
}

// Load the configuration and start the application
$config = require __DIR__ . '/protected/config/main.php';
Yii::createWebApplication($config)->run();
