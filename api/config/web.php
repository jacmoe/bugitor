<?php

$params = array_merge(
    require(__DIR__ . '/default-params.php')
);

return [
    'id'        => 'app-id',
    'basePath'  => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules'   => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class'    => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'log'                  => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => \yii\log\FileTarget::className(),
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'authManager' => [
            'class' => 'Da\User\Component\AuthDbManagerComponent',
        ],
        'urlManager'           => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => true,
            'showScriptName'      => false,
            'rules'               => [
                [
                    'class'      => \yii\rest\UrlRule::className(),
                    'pluralize'  => false,
                    'controller' => [
                        'v1/hello'
                    ],
                    'tokens'     => [
                        '{id}' => '<id:\\w+>',
                    ],
                ],
                'v1/hello/<action:\\w+>' => 'v1/hello/<action>',
                'GET v1/hello/{id}'      => 'v1/hello/view',
            ],
        ],
        'as contentNegotiator' => [
            'class'   => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ],
        'request'              => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCookieValidation' => false,
            'enableCsrfValidation'   => false,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=bugitor',
            'username' => 'dbuser',
            'password' => 'jake2383',
            'charset' => 'utf8',
        ],
        'response'             => [
            'format' => \yii\web\Response::FORMAT_JSON,
        ],
        'user' => [
            'identityClass' => 'api\modules\v1\models\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],        
    ],
    'params' => $params,
    'as beforeAction' => [
        'class' => \yii\filters\Cors::className(),
        'cors'  => [
            'Origin'                         => ['*'],
            'Access-Control-Request-Method'  => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
            'Access-Control-Request-Headers' => ['*'],
        ],
    ],
]; 
