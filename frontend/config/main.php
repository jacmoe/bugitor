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
        /*'authManager' => [
          'class' => 'yii\rbac\DbManager',
      ],*/
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],// user
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '/' => 'site/index',
                '/about' => 'site/about',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],// urlManager
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOpenId'
                ],
                /*'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => 'facebook_client_id',
                    'clientSecret' => 'facebook_client_secret',
                ],*/
            ],
        ],// authClientCollection
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
    /*'as access' => [
      'class' => 'mdm\admin\classes\AccessControl',
      'allowActions' => [
        'site/*',
        'admin/*',
        'gii/*',
        'debug/*',
      ],
  ], // as access */
    'params' => $params,
];
