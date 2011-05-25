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
 * Copyright (C) 2009 - 2011 Bugitor Team
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
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        'application.modules.rights.components.*',
        'application.helpers.Time',
        'application.components.textile.*',
        'application.helpers.Bugitor',
        'application.behaviors.ActiveRecordLogableBehavior',
        'application.extensions.simpleWorkflow.*',
        'ext.yii-mail.YiiMailMessage',
    ),
    'commandMap'=>array(
        'migrate'=>array(
            'class'=>'system.cli.commands.MigrateCommand',
            'migrationPath'=>'application.migrations',
            'migrationTable'=>'{{migration}}',
            'connectionID'=>'db',
        ),
    ),
    
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'rights' => array(
            'install' => false,
        ),
        'user',
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'class' => 'RWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/user/login'),
        ),
        'mutex' => array(
            'class' => 'ext.EMutex',
        ),
        'textile' => array(
            'class' => 'application.components.textile.Textilizer',
        ),
        'mail' => require(dirname(__FILE__) . '/mail.php'),
        'config' => array(
            'class' => 'application.extensions.EConfig',
            'configTableName' => '{{config}}',
            'autoCreateConfigTable' => true,
            'strictMode' => false,
        ),
        'swSource' => array(
            'class' => 'application.extensions.simpleWorkflow.SWPhpWorkflowSource',
        ),
        'authManager' => array(
            // The authorization manager (default: CDbAuthManager)
            'class' => 'RDbAuthManager',
            // The database component used
            'connectionID' => 'db',
            // The itemTable name (default: AuthItem)
            'itemTable' => '{{auth_item}}',
            // The assignmentTable name (default: AuthAssignment)
            'assignmentTable' => '{{auth_assignment}}',
            // The itemChildTable name (default: AuthItemChild)
            'itemChildTable' => '{{auth_item_child}}',
            // The itemWeightTable (default: AuthItemWeight)
            'rightsTable' => '{{auth_item_weight}}',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '/projects/' => 'project/index',
                '/projects/<identifier>' => 'project/view',
                '/projects/<identifier>/issues' => 'issue/index',
                '/projects/<identifier>/changesets' => 'changeset/index',
                '/projects/<identifier>/issue/<_a:(create)>'   => 'issue/<_a>',
                '/projects/<identifier>/member/<_a:(create)>'   => 'member/<_a>',
                '/projects/<identifier>/version/<_a:(create)>'   => 'version/<_a>',
                '/projects/<identifier>/repository/<_a:(create)>'   => 'repository/<_a>',
                '/projects/<identifier>/issueCategory/<_a:(create)>'   => 'issueCategory/<_a>',
                '/projects/<identifier>/issue/<action:\w+>/<id:\d+>'   => 'issue/<action>',
                '/projects/<identifier>/issues/<action:\w+>/<id:\d+>'   => 'issue/<action>',
                '/projects/<identifier>/member/<action:\w+>/<id:\d+>'   => 'member/<action>',
                '/projects/<identifier>/version/<action:\w+>/<id:\d+>'   => 'version/<action>',
                '/projects/<identifier>/repository/<action:\w+>/<id:\d+>'   => 'repository/<action>',
                '/projects/<identifier>/issueCategory/<action:\w+>/<id:\d+>'   => 'issueCategory/<action>',
                '/projects/<identifier>/changeset/<action:\w+>/<id:\d+>'   => 'changeset/<action>',
                '/projects/<identifier>/<_a:(activity|roadmap|code|settings)>'   => 'project/<_a>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'projects/<identifier>/feed'=>array('project/feed', 'urlSuffix'=>'.xml', 'caseSensitive'=>false),
                '/issues/' => 'issue/index',
                ),
        ),
        'db' => require(dirname(__FILE__) . '/db.php'),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'tracker@tracker.ogitor.org',
        'adminEmailText' => 'Bugitor Issue Tracker',
    ),
);
