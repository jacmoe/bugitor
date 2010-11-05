<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
    array(
        'import' => array(
            'application.extensions.yiidebugtb.*',
        ),
        'modules' => array(
            'gii' => array(
                'class' => 'system.gii.GiiModule',
                'password' => 'letmein',
            ),
        ),
        // application components
        'components' => array(
            'db' => array(
                'connectionString' => 'mysql:host=localhost;dbname=ogitorbugs',
                'emulatePrepare' => true,
                'username' => 'superadmin',
                'tablePrefix' => 'bug_',
                'password' => 'jake2383',
                'charset' => 'utf8',
            ),
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning, trace',
                    ),
                    array(// configuration for the toolbar
                        'class' => 'XWebDebugRouter',
                        'config' => 'alignRight, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
                        'levels' => 'error, warning, trace, profile, info',
                        'allowedIPs' => array('127.0.0.1', '192.168.1.54'),
                    ),
                ),
            ),
        ),
    )
);
