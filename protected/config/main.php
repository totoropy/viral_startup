<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'JoeBo Viral',
	//'theme'=>'pepper',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=plzentv_viral',
			'emulatePrepare' => true,
			'username' => 'plzentv_user1',
			'password' => 'Passw0rd1',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed Yii::app()->params['webSite']
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'admin@joebo.info',
		'adminIds'=>'122345,123456',
		'appid'=>'304809456224027',
		'api'=>'29e43537f5bad60b1dfe0293b4ef4a32',
		'secret'=>'0ed2b1d12112d42ab35b9834354a4a20',
		'appCanvasUrl'=>'https://apps.facebook.com/joebo_viral/',
		'appBaseUrl'=>'https://www.akceplzen.cz/fb/joebo/viral/',
		'appName'=>'JoeBo Viral Startup',
		'permissions'=>'',
		'webSite'=>'http://www.joebo.info/',
		'fbPage'=>'http://www.facebook.com/pages/JoeBo/160698883995181',
		'fbPageID'=>'160698883995181',
		'isAppClosed'=>false,
		'imagesLimit'=>'30',
		'invitationsPerDayLimit'=>'10',
		'kuponLimit'=>'10',
	),
);