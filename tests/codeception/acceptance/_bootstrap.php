<?php
new yii\web\Application(require(dirname(__DIR__) . '/config/acceptance.php'));

\Codeception\Util\Autoload::registerSuffix('Steps', __DIR__.DIRECTORY_SEPARATOR.'_steps');