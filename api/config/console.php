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
        'authManager' => [
            'class' => 'Da\User\Component\AuthDbManagerComponent',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=bugitor',
            'username' => 'dbuser',
            'password' => 'jake2383',
            'charset' => 'utf8',
        ],
        'log' => [
            'targets' => [
                [
                    'class'  => \yii\log\FileTarget::className(),
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                '@app/migrations',
                '@yii/rbac/migrations', // Just in case you forgot to run it on console (see next note),
            ],
            'migrationNamespaces' => [
                'Da\User\Migration',
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => Da\User\Module::class,
        ]
        ],
        'params'              => $params,
];
