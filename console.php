<?php

$hostname = $_SERVER['SERVER_NAME'];
// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/framework/yii.php';

$config=dirname(__FILE__).'/protected/config/console.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
$app = Yii::createConsoleApplication($config);

Yii::app()->setTimeZone("UTC");
$app->run();
