<?php

$params = require(__DIR__ . '/params.php');
$snippets = require(__DIR__ . '/snippets.php');

$config = [
    'name' => 'Bugitor',
    'id' => 'bugitor',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    //'layout' => 'main.jade',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'authManager' => [
            'class' => 'dektrium\rbac\components\DbManager',
        ],
        'assetManager' => [
            'bundles' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '{{app.recaptcha.siteKey}}',
            'secret' => '{{app.recaptcha.secret}}',
        ],//recaptcha
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'twitter' => [
                    'class' => 'dektrium\user\clients\Twitter',
                    'consumerKey' => '{{app.auth.twitter.consumerKey}}',
                    'consumerSecret' => '{{app.auth.twitter.consumerSecret}}',
                ],
                'google' => [
                    'class'        => 'dektrium\user\clients\Google',
                    'clientId'     => '{{app.auth.google.clientId}}',
                    'clientSecret' => '{{app.auth.google.clientSecret}}',
                ],
            ],// clients
        ],// authClientCollection
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'useFileTransport' => {{app.mailer.useFileTransport}},
            'transport' => [
                'class' => '{{app.mailer.transport.class}}',
                'host' => '{{app.mailer.transport.host}}',
                'username' => '{{app.mailer.transport.username}}',
                'password' => '{{app.mailer.transport.password}}',
                'port' => '{{app.mailer.transport.port}}',
                'encryption' => '{{app.mailer.transport.encryption}}',
            ],
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
            'dsn' => 'mysql:host={{app.mysql.host}};dbname={{app.mysql.dbname}}',
            'username' => '{{app.mysql.username}}',
            'password' => '{{app.mysql.password}}',
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
        'view' => [
            'class' => 'jacmoe\mdpages\components\View',
            'defaultExtension' => 'jade',
            'theme' => [
                'pathMap' => [
                    '@jacmoe/mdpages/views' => '@app/views/docs',
                ],
            ],
            'renderers' => [
                'jade' => [
                    'class' => 'jacmoe\talejade\JadeViewRenderer',
                    'cachePath' => '@runtime/Jade/cache',
                    'options' => [
                        'pretty' => true,
                        'lifeTime' => 0,//3600 -> 1 hour
                    ],
                ],// jade
            ],// renderers
        ],// view
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
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableFlashMessages' => false,
            'admins' => ['{{app.user.admins.admin1}}', '{{app.user.admins.admin2}}'],
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
