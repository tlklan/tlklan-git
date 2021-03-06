<?php

// Define bootstrap alias
Yii::setPathOfAlias('bootstrap', __DIR__.'/../../vendor/tlklan/yii-bootstrap');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'LAN-klubben',

	'aliases' => [
		'cms' => __DIR__ . '/../../vendor/tlklan/yii-cms',
		'vendor' => __DIR__ . '/../../vendor',
	],

	// preloading 'log' component
	'preload'=>array(
		'log',
		'less',
	),
	
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.forms.*',
		'application.models.badges.*',
		'application.components.*',
		'application.components.behaviors.*',
		'application.components.widgets.*',
		'application.components.widgets.grid.*',
		'ext.localuser.*',
		'vendor.tlklan.yii-bootstrap.widgets.*',
		'vendor.tlklan.yii-cms.CmsModule',
		'vendor.tlklan.yii-img.components.*',
		'vendor.tlklan.yii-img.models.Image',
	),

	'modules'=>array(
		'cms',
		'admin'=>array(
			'layout'=>'main',
		),
		'image'=>array(
			'class' => 'vendor.tlklan.yii-img.ImageModule',
			'createOnDemand'=>true,
        ),
	),
	
	// behaviors
	'behaviors'=>array(
		'ApplicationLanguageBehavior',
	),

	// application components
	'components'=>array(
		'bootstrap'=>array(
			'class'=>'vendor.tlklan.yii-bootstrap.components.Bootstrap',
		),
		'clientScript'=>array(
			'class'=>'ext.minify.EClientScript',
			'combineScriptFiles'=>true,
			'combineCssFiles'=>true,
			'optimizeScriptFiles'=>true,
			'optimizeCssFiles'=>true,
		),
		'less'=>array(
			'class'=>'vendor.tlklan.yii-less.components.LessCompiler',
			'forceCompile'=>true, // indicates whether to force compiling
			'paths'=>array(
				'css/less/styles.less'=>'css/styles.css',
			),
		),
		'user'=>array(
			'class'=>'WebUser',
			'allowAutoLogin'=>true,
			'boardGid'=>getenv('USER_BOARD_GID'),
			'adminGid'=>getenv('USER_ADMIN_GID'),
		),
		'localUser'=>array(
			'class'=>'ext.localuser.LocalUser',
			'hostname'=>getenv('USER_SSH_HOSTNAME'),
			'port'=>getenv('USER_SSH_PORT'),
		),
		'hasher'=>array(
			'class'=>'ext.phpass.Phpass',
			'hashPortable'=>false,
			'hashCostLog2'=>10,
		),
		'image'=>array(
			'class'=>'ImgManager',
			'versions'=>array(
				'small'=>array('width'=>170, 'height'=>170),
				'profile'=>array('width'=>264, 'height'=>264),
			),
		),
		'session'=>array(
			'autoStart'=>true,
		),
		'urlManager'=>array(
			'class'=>'UrlManager',
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				// Handle the language parameter. It is important that these 
				// rules appear in this order for everything to work correctly.
				'<language:(en|sv)>/page/<name>-<id:\d+>.html'=>'cms/node/page',

				'<language:(en|sv)>/<module>'=>'<module>/',
				'<language:(en|sv)>/<module:\w+>/<controller:\w+>/<id:\d+>'=>'<module>/<controller>/view',
				'<language:(en|sv)>/<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<module>/<controller>/<action>',
				'<language:(en|sv)>/<module:\w+>/<controller:\w+>/<action:\w+>/*'=>'<module>/<controller>/<action>',
				
				'<language:(en|sv)>/' => 'site/index',
				'<language:(en|sv)>/<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<language:(en|sv)>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<language:(en|sv)>/<controller:\w+>/<action:\w+>/*'=>'<controller>/<action>',
				
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'page/<name>-<id:\d+>.html'=>'cms/node/page',
			),
		),
		'cms'=>array(
			'class'=>'TLKCms',	
			'languages'=>array('sv'=>'Svenska', 'en'=>'English'),
			'allowedFileTypes'=>'pdf',
			'allowedFileSize'=>2097152,
			'attachmentPath'=>'/files/cms/attachments/',
		),
		'db'=>array(
			'connectionString' => sprintf('mysql:host=%s;dbname=%s', getenv('MYSQL_HOSTNAME'), getenv('MYSQL_DATABASE')),
			'emulatePrepare' => true,
			'username' => getenv('MYSQL_USERNAME'),
			'password' => getenv('MYSQL_PASSWORD'),
			'charset' => 'utf8',
			'schemaCachingDuration'=>getenv('SCHEMA_CACHING_DURATION'),
		),
		'cache'=>array(
			'class'=>'CFileCache',
		),
		'errorHandler'=>array(
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
//				array(
//					'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
//					'ipFilters'=>array('127.0.0.1'),
//				),
			),
		),
		'messages'=>array(
			'class'=>'CDbMessageSource',
			'sourceMessageTable'=>'tlk_source_messages',
			'translatedMessageTable'=>'tlk_translated_messages',
			'cachingDuration'=>86400,
		),
	),

	// Source language (the language the application is written in)
	'sourceLanguage'=>'sv',
	
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// Minimum penis size required to register
		'minimumPenisLength'=>9,
		
		// Location for uploaded submissions
		'submissionPath'=>getenv('SUBMISSION_PATH'),
		
		// e-mail settings
		'mail'=>array(
			'noreply'=>'root@werket.tlk.fi',
			'committee'=>'lanklubben@tlk.fi',
			// only these e-mails can be used to register
			'validDomains'=>array(
				'arcada.fi',
				'cc.hut.fi',
				'metropolia.fi',
				'aalto.fi',
				'helsinki.fi',
			)
		)
	),
);
