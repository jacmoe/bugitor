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
    'bootstrap' => ['log'],
    'components' => [
        /*'authManager' => [
        'class' => 'yii\rbac\PhpManager',
    ],*/
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
    /*'as access' => [
    'class' => 'mdm\admin\classes\AccessControl',
    'allowActions' => [
    'site/*',
    'admin/*',
    'gii/*',
    'debug/*',
],
],// as access */
'params' => $params,
];
