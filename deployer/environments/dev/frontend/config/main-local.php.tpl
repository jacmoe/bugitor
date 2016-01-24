<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '{{app.recaptcha.siteKey}}',
            'secret' => '{{app.recaptcha.secret}}',
        ],//recaptcha
    ],
];

if (!YII_ENV_TEST) {
  // configuration adjustments for 'dev' environment
  $config['bootstrap'][] = 'rest-client';
  $config['modules']['rest-client'] = [
      'class' => 'zhuravljov\yii\rest\Module',
      'baseUrl' => 'http://bugitor.dev/api/v1',
  ];

  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = 'yii\debug\Module';

  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    'allowedIPs' => {{app.gii.allowedIPs}},
    'generators' => [
    'jadecrud' => [
        'class' => 'jacmoe\giijade\crud\Generator',
        'templates' => [
          'myCrud' => '@jacmoe/giijade/crud/default',
        ]
      ]
    ],
  ];
}

return $config;
