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
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-purple',
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
        'view' => [
            'theme' => [
                    'pathMap' => [
                        '@dektrium/user/views' => '@frontend/views/user',
                    ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                '/' => 'site/index',
                '/about' => 'site/about',
                '/projects/' => 'project/index',
                '/projects/<identifier>/<action>' => 'project/<action>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],// urlManager
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
    'modules' => [
        'user' => [
            'as frontend' => 'dektrium\user\filters\FrontendFilter',
            'modelMap' => [
                'RegistrationForm' => 'frontend\models\RegistrationForm',
            ],
        ],// user
    ], //modules
    'params' => $params,
];
