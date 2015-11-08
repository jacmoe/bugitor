<?php
return [
  'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
  'layout' => 'main.jade',
  'components' => [
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ], // cache
    'authManager' => [
        'class' => 'yii\rbac\DbManager',
    ],
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'enableStrictParsing' => false,
        'rules' => [
            '/' => 'site/index',
            '/about' => 'site/about',
            '/contact' => 'site/contact',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        ],
    ],// urlManager
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
