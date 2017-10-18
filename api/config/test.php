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
        'urlManager'           => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => true,
            'showScriptName'      => false,
        ],
        'user' => [
            'identityClass' => 'api\modules\v1\models\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],        
    ],
]; 
