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
			'connectionString' => 'mysql:host=localhost;dbname=tlk_lan',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
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