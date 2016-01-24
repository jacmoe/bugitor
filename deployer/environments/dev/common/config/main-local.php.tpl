<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host={{app.mysql.host}};dbname={{app.mysql.dbname}}',
            'username' => '{{app.mysql.username}}',
            'password' => '{{app.mysql.password}}',
            'charset' => 'utf8',
        ],
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
            'viewPath' => '@common/mail',
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
    ],
];
