<?php
$params = array_merge(
require(__DIR__ . '/../../common/config/params.php'),
require(__DIR__ . '/../../common/config/params-local.php'),
require(__DIR__ . '/params.php'),
require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name' => 'Bugitor Admin',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'admin/index',
    'bootstrap' => ['log'],
    'components' => [
        'authManager' => [
            'class' => 'dektrium\rbac\components\DbManager',
        ],
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-red',
            ],
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '/' => 'admin/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],// urlManager
        'view' => [
            'theme' => [
                    'pathMap' => [
                        '@dektrium/user/views' => '@common/views/user',
                        '@backend/views/layouts' => '@common/views/layouts',
                    ],
            ],
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
            'errorAction' => 'admin/error',
        ],
    ],// components
    'modules' => [
        'user' => [
            'enableRegistration' => false,
            //'as backend' => 'dektrium\user\filters\BackendFilter',
            'mailer' => [
                'viewPath' => '@common/views/mail',
            ],
        ],
    ],
    'params' => $params,
];
