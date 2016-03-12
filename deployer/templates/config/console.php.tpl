<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');

$params = require(__DIR__ . '/params.php');
$snippets = require(__DIR__ . '/snippets.php');

$config = [
    'id' => 'bugitor-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => 'dmstr\console\controllers\MigrateController'
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host={{app.mysql.host}};dbname={{app.mysql.dbname}}',
            'username' => '{{app.mysql.username}}',
            'password' => '{{app.mysql.password}}',
            'charset' => 'utf8',
        ],
    ],
    'modules' => [
      'docs' => [
        'class' => 'jacmoe\mdpages\Module',
        'repository_url' => 'https://github.com/{{app.github.owner}}/{{app.github.repo}}.git',
        'github_token' => '{{app.github.token}}',
        'github_owner' => '{{app.github.owner}}',
        'github_repo' => '{{app.github.repo}}',
        'github_branch' => '{{app.github.branch}}',
        'absolute_wikilinks' => true,
        'generate_page_toc' => true,
        'generate_contributor_data' => true,
        'snippets' => $snippets,
      ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
