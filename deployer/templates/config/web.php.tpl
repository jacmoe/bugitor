<?php

$params = require(__DIR__ . '/params.php');
$snippets = require(__DIR__ . '/snippets.php');

$config = [
    'id' => 'bugitor',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'assetManager' => [
            'bundles' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => '\yii\web\IdentityInterface',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2basic',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        //'assetManager' => [
        //    'linkAssets' => true,
        //    'appendTimestamp' => true,
        //],
    ],
    'modules' => [
        'wiki' => [
            'class' => 'jacmoe\mdpages\Module',
            'repository_url' => 'https://github.com/{{app.github.owner}}/{{app.github.repo}}.git',
            'github_token' => '{{app.github.token}}',
            'github_owner' => '{{app.github.owner}}',
            'github_repo' => '{{app.github.repo}}',
            'github_branch' => '{{app.github.branch}}',
            'absolute_wikilinks' => true,
            'generate_page_toc' => false,
            'feed_title' => 'Blog posts',
            'feed_description' => 'Jacmoes Cyber Soapbox Rss Feed',
            'feed_author_email' => 'jacmoe.dk@gmail.com',
            'feed_author_name' => 'Jacob Moen',
            'feed_ordering' => 'datetime DESC',
            'feed_filtering' => true,
            'generate_contributor_data' => false,
            'snippets' => $snippets,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
