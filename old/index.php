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
 * Copyright (C) 2009 - 2013 Bugitor Team
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

$message = '';

if (!file_exists(dirname(__FILE__) . '/protected/config/db.php')) {
    $message .= '<h1>Error</h1>';
    $message .= '<h3>Database connection does not seem to have been configured</h3>';
    $message .= '<p><tt>Please run the <a href="installer">Bugitor Installer</a> to configure database instance</tt></p>';
    die($message);
}

$hostname = $_SERVER['SERVER_NAME'];

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/framework/yii.php';
if (!file_exists($yii)) {
    $message .= '<h1>Error</h1>';
    $message .= '<h3>Yii Framework cannot be found</h3>';
    $message .= "<p><tt><i>\"{$yii}\"</i> is not a valid path.</tt></p>";
    $message .= "<p><tt>Please review - and edit - the following files:</tt></p>";
    $message .= "<ul>";
    $message .= "<li>BUGITOR/index.php</li>";
    $message .= "<li>BUGITOR/protected/yiic.php</li>";
    $message .= "<li>BUGITOR/installer/index.php</li>";
    $message .= "<li>BUGITOR/installer/protected/yiic.php</li>";
    $message .= "</ul>";
    die($message);
}

switch ( strtolower($hostname))
{
case 'localhost':
case 'bugitor.localhost':
case '127.0.0.1':
    $config=dirname(__FILE__).'/protected/config/local.php';
    // remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    // specify how many levels of call stack should be shown in each log message
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',2);
    break;
default:
    $config=dirname(__FILE__).'/protected/config/main.php';
    break;
}

require_once($yii);
$app = Yii::createWebApplication($config);

// we need to set this to UTC, regardless of default timezone 
// which is only for display. UTC is what timestamps etc. are using.
Yii::app()->setTimeZone("UTC");

$app->run();