<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
	<!--<![endif]-->
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/assets/f105b38b/css/chaw.css" />
<link rel="stylesheet" type="text/css" href="/assets/cad80fdb/styles/github.css" />
<script type="text/javascript" src="/assets/a87bfd91/jquery.min.js"></script>
<script type="text/javascript" src="/assets/f105b38b/jquery.highlight_diff.min.js"></script>
<title>phpsvnclient SVN test</title>
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<link rel="stylesheet" type="text/css" href="/themes/new/css/screen.css" />
		<!-- Favicons
		================================================== -->
		<link rel="shortcut icon" href="/themes/new/images/favicon.ico">
		<link rel="apple-touch-icon" href="/themes/new/images/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/themes/new/images/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/themes/new/images/apple-touch-icon-114x114.png">
	</head>
	<body>
<?php
$url = 'http://phpsvnclient.googlecode.com/svn/';

$path = 'protected/vendors/phpsvnclient';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
$path = 'protected/vendors/phpsvnclient/ext';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
echo get_include_path() . "<br/><hr/>";
require_once('protected/vendors/phpsvnclient/phpsvnclient.php');
$phpsvnclient = new phpsvnclient($url);

$version = $phpsvnclient->getVersion();
echo "Version: " . $version . "<br/>";

// Lets get an array of the contents within /trunk/
//$logs = $phpsvnclient->getRepositoryLogs('/trunk/');
/*$logs = $phpsvnclient->getRepositoryLogs();

echo "<pre>\n";
print_r($logs);
echo "</pre>\n";*/

$diff = $phpsvnclient->diffVersions("trunk/phpsvnclient.php", 113, 131);
echo "<div class=diff>\n";
echo $diff;
echo "</div>\n";


?>
	<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
$(".diff").highlight_diff();
});
/*]]>*/
</script>
</body>
</html>