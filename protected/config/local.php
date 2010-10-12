<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Bugitor',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'application.modules.user.models.*',
                'application.modules.user.components.*',
                'application.modules.rights.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
                'rights' => array(
                    'install' => false,
                ),
                'user',
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'letmein',
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
                        'class' => 'RightsWebUser',
			'allowAutoLogin'=>true,
                        'loginUrl' => array('/user/login'),
		),
		'config' => array(
			'class' => 'application.extensions.EConfig',
			'configTableName' => 'bug_Config',
			'autoCreateConfigTable' => false,
			'strictMode' => false,
		),
		'authManager'=>array(
				// The authorization manager (default: CDbAuthManager)
				'class'=>'RightsAuthManager',
				// The database component used
				'connectionID'=>'db',
				// The itemTable name (default: AuthItem)
				'itemTable'=>'bug_auth_item',
				// The assignmentTable name (default: AuthAssignment)
				'assignmentTable'=>'bug_auth_assignment',
				// The itemChildTable name (default: AuthItemChild)
				'itemChildTable'=>'bug_auth_item_child',
				// The itemWeightTable (default: AuthItemWeight)
				'itemWeightTable'=>'bug_auth_item_weight',
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=ogitorbugs',
			'emulatePrepare' => true,
			'username' => 'superadmin',
                        'tablePrefix' => 'bug_',
			'password' => 'jake2383',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
//		'log'=>array(
//			'class'=>'CLogRouter',
//			'routes'=>array(
//				array(
//					'class'=>'CFileLogRoute',
//					'levels'=>'error, warning',
//				),
//				// uncomment the following to show log messages on web pages
//				array(
//					'class'=>'CWebLogRoute',
//				),
//			),
//		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'jacmoe@mail.dk',
	),
);