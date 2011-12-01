<?php
$url = 'http://phpsvnclient.googlecode.com/svn/';

require_once('protected/vendors/phpsvnclient/phpsvnclient.php');
$phpsvnclient = new phpsvnclient($url);

$version = $phpsvnclient->getVersion();
echo "Version: " . $version . "<br/>";

// Lets get an array of the contents within /trunk/
//$logs = $phpsvnclient->getRepositoryLogs('/trunk/');
$logs = $phpsvnclient->getRepositoryLogs();

echo "<pre>\n";
print_r($logs);
echo "</pre>\n";