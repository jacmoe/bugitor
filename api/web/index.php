<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

Yii::setAlias('api', __DIR__ . '/../');

$config = require(__DIR__ . '/../config/web.php');

$application = new yii\web\Application($config);
$application->run();
