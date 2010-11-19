<?php

$hostname = $_SERVER['SERVER_NAME'];
// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/framework/yii.php';

switch ( strtolower($hostname))
{
case 'localhost':
case '127.0.0.1':
    $config=dirname(__FILE__).'/protected/config/local.php';
    // remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    // specify how many levels of call stack should be shown in each log message
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',1);
    break;
default:
    $config=dirname(__FILE__).'/protected/config/main.php';
    defined('YII_DEBUG') or define('YII_DEBUG',false);
    break;
}


require_once($yii);
$app = Yii::createWebApplication($config);

//Yii::app()->config->set('defaultPagesize', 20);

Yii::app()->setTimeZone("UTC");
$app->run();
