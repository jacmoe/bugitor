<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Bugitor',
    'theme' => 'classic',
    'defaultController' => 'project',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        'application.modules.rights.components.*',
        'application.helpers.*',
        'application.behaviors.ActiveRecordLogableBehavior',
        'application.extensions.simpleWorkflow.*',
    ),
    'modules' => array(
        'admin',
        'rights' => array(
            'install' => false,
        ),
        'user',
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'class' => 'RightsWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/user/login'),
        ),
        'config' => array(
            'class' => 'application.extensions.EConfig',
            'configTableName' => 'bug_config',
            'autoCreateConfigTable' => true,
            'strictMode' => false,
        ),
        'swSource' => array(
            'class' => 'application.extensions.simpleWorkflow.SWPhpWorkflowSource',
        ),
        'timezonekeeper' => array (
            'class' => 'application.components.TimeZoneKeeper',
        ),
        'gravatar' => array (
            'class' => 'application.helpers.Gravatar',
        ),
        'authManager' => array(
            // The authorization manager (default: CDbAuthManager)
            'class' => 'RightsAuthManager',
            // The database component used
            'connectionID' => 'db',
            // The itemTable name (default: AuthItem)
            'itemTable' => 'bug_auth_item',
            // The assignmentTable name (default: AuthAssignment)
            'assignmentTable' => 'bug_auth_assignment',
            // The itemChildTable name (default: AuthItemChild)
            'itemChildTable' => 'bug_auth_item_child',
            // The itemWeightTable (default: AuthItemWeight)
            'itemWeightTable' => 'bug_auth_item_weight',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '/projects/' => 'project/index',
                '/projects/<name>' => 'project/view',
                '/issues/' => 'issue/index',
                '/projects/<name>/issues' => 'issue/index',
                '/projects/<name>/issue/<_a:(create)>'   => 'issue/<_a>',
                '/projects/<name>/issue/<action:\w+>/<id:\d+>'   => 'issue/<action>',
                '/projects/<name>/<_a:(activity|roadmap|issues|newissue|code|settings)>'   => 'project/<_a>',
                '/projects/<name>/issue/<action:\w+>/<id:\d+>/' => 'issue/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'comment/feed'=>array('comment/feed', 'urlSuffix'=>'.xml', 'caseSensitive'=>false),
                ),
        ),
        'db' => array(
            'connectionString' => 'mysql:host=mysql.ogitor.org;dbname=ogitorbugs',
            'emulatePrepare' => true,
            'username' => 'ogitordbadmin',
            'tablePrefix' => 'bug_',
            'password' => 'Pevum2383',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'jacmoe@mail.dk',
    ),
);