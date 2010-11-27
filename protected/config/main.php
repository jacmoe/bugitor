<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2010 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Bugitor',
    'theme' => 'classic',
    'defaultController' => 'site',
    'preload' => array('log', 'maintenanceMode'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.textile.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        'application.modules.rights.components.*',
        'application.helpers.*',
        'ext.simpleWorkflow.*',
        'ext.scm.*',
        'ext.yii-mail.YiiMailMessage',
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
        'mail' => array(
            'class' => 'ext.yii-mail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.mail',
            'dryRun' => false,
        ),
        'textile' => array(
            'class' => 'application.components.textile.Textilizer',
        ),
        'mutex' => array(
            'class' => 'ext.EMutex',
        'file' => array(
            'class' => 'ext.CFile',
        ),
        'scm' => array(
            'class' => 'ext.scm.ESCM',
        ),
        'maintenanceMode' => array(
            'class' => 'ext.MaintenanceMode.MaintenanceMode',
            'enabledMode' => false,
            'message' => 'This site is currently undergoing maintenance. It should be up and running pretty soon.<br/>Thanks for your patience.',
            // or
            //'capUrl' => 'site/contact',
            // allowed users
            'users' => array('jacmoe', ),
            // allowed roles
            'roles' => array('Administrator', ),
            // allowed urls
            'urls' => array('/user/login', '/login', ),
        ),
        'config' => array(
            'class' => 'application.extensions.EConfig',
            'configTableName' => 'bug_config',
            'autoCreateConfigTable' => true,
            'strictMode' => false,
        ),
        'cache' => array(
            'class' => 'system.caching.CFileCache',
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
                '/welcome/' => 'site/index',
                '/projects/' => 'project/index',
                '/projects/<identifier>' => 'project/view',
                '/projects/<identifier>/issues' => 'issue/index',
                '/projects/<identifier>/issue/<_a:(create)>'   => 'issue/<_a>',
                '/projects/<identifier>/member/<_a:(create)>'   => 'member/<_a>',
                '/projects/<identifier>/version/<_a:(create)>'   => 'version/<_a>',
                '/projects/<identifier>/repository/<_a:(create)>'   => 'repository/<_a>',
                '/projects/<identifier>/issueCategory/<_a:(create)>'   => 'issueCategory/<_a>',
                '/projects/<identifier>/issue/<action:\w+>/<id:\d+>'   => 'issue/<action>',
                '/projects/<identifier>/member/<action:\w+>/<id:\d+>'   => 'member/<action>',
                '/projects/<identifier>/version/<action:\w+>/<id:\d+>'   => 'version/<action>',
                '/projects/<identifier>/repository/<action:\w+>/<id:\d+>'   => 'repository/<action>',
                '/projects/<identifier>/issueCategory/<action:\w+>/<id:\d+>'   => 'issueCategory/<action>',
                '/projects/<identifier>/<_a:(activity|roadmap|issues|newissue|code|settings)>'   => 'project/<_a>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'comment/feed'=>array('comment/feed', 'urlSuffix'=>'.xml', 'caseSensitive'=>false),
                '/issues/' => 'issue/index',
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