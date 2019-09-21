<?php

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.vendors.*',
	),
	
	'components'=>array(
		'db'=>array(
			'connectionString' => sprintf('mysql:host=%s;dbname=%s', getenv('MYSQL_HOSTNAME'), getenv('MYSQL_DATABASE')),
			'emulatePrepare' => true,
			'username' => getenv('MYSQL_USERNAME'),
			'password' => getenv('MYSQL_PASSWORD'),
			'charset' => 'utf8',
			'schemaCachingDuration'=>getenv('SCHEMA_CACHING_DURATION'),
		),
		'hasher'=>array(
			'class'=>'ext.phpass.Phpass',
			'hashPortable'=>false,
			'hashCostLog2'=>10,
		),
	),
	
	'params'=>array(
		'mail'=>array(
			'from'=>'lanklubben@tlk.fi',
		)
	)
);
