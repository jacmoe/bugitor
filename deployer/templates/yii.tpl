#!/usr/bin/env php
<?php
/**
 * Yii console bootstrap file.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
date_default_timezone_set('UTC');

defined('YII_DEBUG') or define('YII_DEBUG', {{app_debug}});
defined('YII_ENV') or define('YII_ENV', '{{app_stage}}');

require('{{release_path}}/vendor/autoload.php');
require('{{release_path}}/vendor/yiisoft/yii2/Yii.php');

$config = require('{{release_path}}/config/console.php');

$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);
