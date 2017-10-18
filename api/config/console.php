<?php

$params = array_merge(
    require(__DIR__ . '/default-params.php')
);

return [
    'id'                  => 'api-console',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'api\modules\v1\commands',
    'components'          => [
        'log' => [
            'targets' => [
                [
                    'class'  => \yii\log\FileTarget::className(),
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params'              => $params,
];
