<?php

$params = require(__DIR__ . '/params.php');
$snippets = require_once(__DIR__ . '/snippets.php');

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
            'class' => 'yii\rbac\DbManager',
        ],
        'assetManager' => [
            //'bundles' => false,
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\validators\ValidationAsset' => false,
                'yii\web\YiiAsset' => false,
                'yii\widgets\ActiveFormAsset' => false,
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\web\JqueryAsset' => false,
                //'yii\authclient\widgets\AuthChoiceAsset' => false, //authchoice.js
                //'yii\authclient\widgets\AuthChoiceStyleAsset' => false, //authchoice.css
            ],
            'linkAssets' => true,
            'appendTimestamp' => true,
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
            'siteKey' => '{{recaptcha_siteKey}}',
            'secret' => '{{recaptcha_secret}}',
        ],//recaptcha
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'twitter' => [
                    'class' => 'dektrium\user\clients\Twitter',
                    'consumerKey' => '{{auth_twitter_consumerKey}}',
                    'consumerSecret' => '{{auth_twitter_consumerSecret}}',
                ],
                'google' => [
                    'class'        => 'dektrium\user\clients\Google',
                    'clientId'     => '{{auth_google_clientId}}',
                    'clientSecret' => '{{auth_google_clientSecret}}',
                ],
            ],// clients
        ],// authClientCollection
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'useFileTransport' => {{mailer_useFileTransport}},
            'transport' => [
                'class' => '{{mailer_transport_class}}',
                'host' => '{{mailer_transport_host}}',
                'username' => '{{mailer_transport_username}}',
                'password' => '{{mailer_transport_password}}',
                'port' => '{{mailer_transport_port}}',
                'encryption' => '{{mailer_transport_encryption}}',
            ],
        ],
        'mailqueue' => [
            'class' => 'nterms\mailqueue\MailQueue',
            'table' => '{{%mail_queue}}',
            'mailsPerRound' => 10,
            'maxAttempts' => 3,
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
            'dsn' => 'mysql:host={{mysql_host}};dbname={{mysql_dbname}}',
            'username' => '{{mysql_username}}',
            'password' => '{{mysql_password}}',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                '/projects/' => 'project/index',
                '/issues/' => 'issue/index',
                '/activity/' => 'activity/index',
                '/projects/<identifier>/<action>' => 'project/<action>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'view' => [
            'class' => 'jacmoe\mdpages\components\View',
            'defaultExtension' => 'jade',
            'theme' => [
                'pathMap' => [
                    '@jacmoe/mdpages/views' => '@app/views/docs',
                    '@dektrium/user/views' => '@app/views/user',
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
            'repository_url' => 'https://github.com/{{github_owner}}/{{github_repo}}.git',
            'github_token' => '{{github_token}}',
            'github_owner' => '{{github_owner}}',
            'github_repo' => '{{github_repo}}',
            'github_branch' => '{{github_branch}}',
            'absolute_wikilinks' => true,
            'generate_page_toc' => false,
            'feed_title' => 'Blog posts',
            'feed_description' => 'Jacmoes Cyber Soapbox Rss Feed',
            'feed_author_email' => 'jacmoe.dk@gmail.com',
            'feed_author_name' => 'Jacob Moen',
            'feed_ordering' => 'datetime DESC',
            'feed_filtering' => true,
            'generate_contributor_data' => true,
            'snippets' => $snippets,
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableFlashMessages' => false,
            'admins' => ['{{user_admins_admin1}}', '{{user_admins_admin2}}'],
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
