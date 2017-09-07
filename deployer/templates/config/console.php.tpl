<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$snippets = require_once(__DIR__ . '/snippets.php');

$config = [
    'id' => 'bugitor-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => 'dmstr\console\controllers\MigrateController',
            'templateFile' => '@app/views/migration/migration.php',
        ],
        'rbac' => [
            'class' => 'app\commands\BugitorRbacCommand',
            'batchSize' => 1000,
            'assignmentsMap' => [
                'old' => 'new', // after next update all `frontend.old` will be replaced by `frontend.new`
            ],
        ],
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'dektrium\user\models\User',
            //'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
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
        ],
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
            'dsn' => 'mysql:host={{mysql_host}};dbname={{mysql_dbname}}',
            'username' => '{{mysql_username}}',
            'password' => '{{mysql_password}}',
            'charset' => 'utf8',
        ],
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
        'generate_page_toc' => true,
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
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
