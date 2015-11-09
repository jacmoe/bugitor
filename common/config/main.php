<?php
return [
    'name' => 'Bugitor',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'layout' => 'main.jade',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ], // cache
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'view' => [
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
    ],// components
];// config
