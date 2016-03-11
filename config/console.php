<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'bugitor-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
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
            'dsn' => 'mysql:host=localhost;dbname=yii2basic',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
    'modules' => [
      'mdpages' => [
        'class' => 'jacmoe\mdpages\Module',
        'repository_url' => 'https://github.com/jacmoe/bugitor-pages.git',
        'github_token' => '104b4836c4a8545972d32990b5b06fa894f738f9',
        'github_owner' => 'jacmoe',
        'github_repo' => 'bugitor-pages',
        'github_branch' => 'master',
        'absolute_wikilinks' => true,
        'generate_page_toc' => true,
        'generate_contributor_data' => false,
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
