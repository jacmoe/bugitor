<?php

return [
    'adminEmail' => 'admin@bugitor.jacmoe.dk',
    'supportEmail' => 'admin@bugitor.jacmoe.dk',
    'user.passwordResetTokenExpire' => 3600,
    "yii.migrations"=> [
        "@dektrium/user/migrations",
        //"@vendor/nterms/yii2-mailqueue/migrations",
    ],
];
