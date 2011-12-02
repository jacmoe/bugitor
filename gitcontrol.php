<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
	<!--<![endif]-->
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/testassets/chaw.css" />
        <script type="text/javascript" src="/testassets/jquery.min.js"></script>
        <script type="text/javascript" src="/testassets/jquery.highlight_diff.min.js"></script>
        <title>Bitbucket API test</title>
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
$path = 'protected/vendors/pyrus/vendor/php';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'protected/vendors/pyrus/vendor/php/VersionControl/Git.php';

$git = new VersionControl_Git('C:/wamp/www/foundation');
$git->setGitCommandPath("C:/PROGRA~1/Git/bin/git.exe");
$commits = $git->getCommits();

print_r($commits[0]->getTree());
echo "<hr/>";
/*
results:

array(100) {
  [0]=>
  object(VersionControl_Git_Object_Commit)#3 (9) {
    :
  }
  [1]=>
  object(VersionControl_Git_Object_Commit)#6 (9) {
    :
  }

  :
*/
$git1 = new VersionControl_Git('C:/wamp/www/foundation');
$git1->setGitCommandPath("C:/PROGRA~1/Git/bin/git.exe");
$commits1 = $git1->getCommits();
$tree = $git1->getTree($commits1[0]->getTree());
$tree->fetch();
$blob = $tree->current()->fetch();
//var_dump($blob->getContent());


$result = $git1->getCommand('show')
    ->execute();
    echo "<pre>";
    print_r( $result );
    echo "</pre>";
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