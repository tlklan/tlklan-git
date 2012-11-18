<?php

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	
	'components'=>array(
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=tlk_lan',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
	),
);