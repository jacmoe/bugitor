<?php
return array(
    'class' => 'ext.yii-mail.YiiMail',
    'transportType' => '{transportType_in}',
    'transportOptions' => array(
        'host' => '{host_in}',
        'username' => '{username_in}',
        'password' => '{password_in}',
        'port' => '{port_in}',
        'viewPath' => 'application.views.mail',
        'dryRun' => false,
    ),
);
