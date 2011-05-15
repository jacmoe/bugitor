<?php
// change the following paths if necessary
$yii=dirname(__FILE__).'/../../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

if(!file_exists($yii))
{
    die("<font color=\"red\">Error:</font> Cannot find Yii!<br/>
            Please check and correct the path to Yii in <em>{$_SERVER['SERVER_NAME']}/installer/index.php</em> and <em>{$_SERVER['SERVER_NAME']}/index.php</em>");
}
if(file_exists(dirname(__FILE__).'/lock')) {
    die("<font color=\"red\">Attention:</font> Installer has been locked.<br/>
        Please remove the lock file in the installer directory and try again.");
}
require_once($yii);
Yii::createWebApplication($config)->run();
