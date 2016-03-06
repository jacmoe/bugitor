<?php
return [
    'name' => 'Bugitor',
    //'language' => 'da-DK',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'layout' => 'main.jade',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ], // cache
        'view' => [
            'class' => 'common\components\View',
            'defaultExtension' => 'jade',
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
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'user' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'rbac' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],// i18n
        'mailqueue' => [
            'class' => 'nterms\mailqueue\MailQueue',
			'table' => '{{%mail_queue}}',
			'mailsPerRound' => 10,
			'maxAttempts' => 3,
        ],
    ],// components
    'modules' => [
        'rbac' => [
            'class' => 'dektrium\rbac\Module',
            'enableFlashMessages' => false,
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableFlashMessages' => false,
        ],
    ],
];// config
