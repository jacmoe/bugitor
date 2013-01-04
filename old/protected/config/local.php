<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2013 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
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
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning, trace',
                    ),
                    array(
                        'class'=>'CWebLogRoute',
                        'categories'=>'system.db.CDbCommand',
                        'showInFireBug'=>true,
                    ),
//                    array(
//                        'class'=>'CProfileLogRoute',
//                        'levels'=>'profile',
//                        'enabled'=>true,
//                    ),
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'info, error, warning, trace',
                        'categories' => 'bugitor',
                        'logFile' => 'bugitor.log',
                    ),
//                    array(
//                        'class'=>'ext.dbprofiler.DbProfileLogRoute',
//                        'countLimit' => 1, // How many times the same query should be executed to be considered inefficient
//                        'slowQueryMin' => 0.01, // Minimum time for the query to be slow
//                    ),
//                    array(// configuration for the toolbar
//                        'class' => 'XWebDebugRouter',
//                        'config' => 'alignRight, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
//                        'levels' => 'error, warning, trace, profile, info',
//                        'allowedIPs' => array('127.0.0.1'),
//                    ),
                ),
            ),
            'db'=>array(
                'enableProfiling'=>true,
                'enableParamLogging'=>true,
            ),
        ),
    )
);