<?php
date_default_timezone_set('UTC');
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require('/home/jacmoe/webdev/vhosts/bugitor/vendor/autoload.php');
require('/home/jacmoe/webdev/vhosts/bugitor/vendor/yiisoft/yii2/Yii.php');

$config = require('/home/jacmoe/webdev/vhosts/bugitor/config/web.php');

(new yii\web\Application($config))->run();
