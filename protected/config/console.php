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
            'class' => 'RightsWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/user/login'),
        ),
        'textile' => array(
            'class' => 'application.components.textile.Textilizer',
        ),
        'mail' => array(
            'class' => 'ext.yii-mail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.mail',
            'dryRun' => false,
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
        'adminEmail' => 'jacmoe@mail.dk',
    ),
);
