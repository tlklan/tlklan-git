<?php

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'LAN-klubben',

	// preloading 'log' component
	'preload'=>array(
		'log', 
		'bootstrap',
		'less',
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.forms.*',
		'application.components.*',
		'application.widgets.*',
		'application.widgets.registration.*',
		'application.widgets.ArchiveListWidget.*',
		'ext.localuser.*',
		'ext.bootstrap.widgets.*',
		'application.modules.cms.CmsModule',
		'application.modules.image.components.*',
        'application.modules.image.models.Image',
	),

	'modules'=>array(
		'cms',
		'admin'=>array(
			'layout'=>'main',
		),
		'image'=>array(
			'createOnDemand'=>true,
        ),
	),

	// application components
	'components'=>array(
		'bootstrap'=>array(
			'class'=>'ext.bootstrap.components.Bootstrap',
			'responsiveCss'=>true,
		),
		'clientScript'=>array(
			'class'=>'ext.minify.EClientScript',
			'combineScriptFiles'=>true,
			'combineCssFiles'=>true,
			'optimizeScriptFiles'=>true,
			'optimizeCssFiles'=>false, // breaks things apparently
		),
		'less'=>array(
			'class'=>'ext.less.components.LessCompiler',
			'forceCompile'=>true, // indicates whether to force compiling
			'paths'=>array(
				'css/less/styles.less'=>'css/styles.css',
				'css/less/small-screen.less'=>'css/small-screen.css',
			),
		),
		'user'=>array(
			'class'=>'WebUser',
			'allowAutoLogin'=>true,
			'gid'=>1042, // lanklubben
		),
		'localUser'=>array(
			'class'=>'ext.localuser.LocalUser',
			'hostname'=>'werket.tlk.fi',
			'port'=>22,
		),
		'hasher'=>array(
			'class'=>'ext.phpass.Phpass',
			'hashPortable'=>false,
			'hashCostLog2'=>10,
		),
		'image'=>array(
			'class'=>'ImgManager',
			'versions'=>array(
				'small'=>array('width'=>170, 'height'=>240),
			),
		),
		'session'=>array(
			'autoStart'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'page/<name>-<id:\d+>.html'=>'cms/node/page',
			),
		),
		'cms'=>array(
			'class'=>'TLKCms',	
			'languages'=>array('sv'=>'Svenska'),
			'allowedFileTypes'=>'pdf',
			'allowedFileSize'=>2097152,
			'attachmentPath'=>'/files/cms/attachments/',
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=tlk_lan_test',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
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
			),
		),
	),

	// Site language
	'language'=>'sv',
	
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// Minimum penis size required to register
		'minimumPenisLength'=>9,
		
		// Location for uploaded submissions
		'submissionPath'=>'/media/Storage/submissions',
		
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);
