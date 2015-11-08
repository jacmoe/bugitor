<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'authManager' => [
          'class' => 'yii\rbac\PhpManager',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],// user
        'assetManager' => [
            'bundles' => [
                //'yii\bootstrap\BootstrapAsset' => false,
                //'yii\validators\ValidationAsset' => false,
                //'yii\web\YiiAsset' => false,
                //'yii\widgets\ActiveFormAsset' => false,
                //'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\web\JqueryAsset' => [
                    'js' => [
                        //YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                        'jquery.min.js',
                    ]
                ],
            ],
        ],// assetManager
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],// log
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],// errorHandler
    ],// components
    'as access' => [
      'class' => 'mdm\admin\classes\AccessControl',
      'allowActions' => [
        'site/*',
        'admin/*',
      ],
    ],
    'params' => $params,
];
