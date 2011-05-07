<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Wizard Behavior',
	'id'=>'Wizard Behavior',
        'theme' => 'classic',
	'sourceLanguage'=>'en_gb',
	'language'=>'en_gb',
	'defaultController'=>'installer/install',

	// preloading 'log' component
	'preload'=>array('log'),

	'modules' => array(
            'installer',
        ),
        // application components
	'components'=>array(
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
                            '<controller:\w+>/<id:\d+>' => '<controller>/view',
                            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			),
			'showScriptName'=>false
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
				/*array(
					'class'=>'CWebLogRoute',
				),*/

			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);