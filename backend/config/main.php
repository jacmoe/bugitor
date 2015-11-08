<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'admin'],
    'modules' => [
      'admin' => [
        'class' => 'mdm\admin\Module',
      ],
    ],// modules
    'components' => [
        'authManager' => [
          'class' => 'yii\rbac\PhpManager',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],// components
    'as access' => [
      'class' => 'mdm\admin\classes\AccessControl',
      'allowActions' => [
        'site/*',
        'admin/*',
        'gii/*',
        'debug/*',
      ],
    ],
    'params' => $params,
];
