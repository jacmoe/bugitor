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
        <title>Pyrus SVN test</title>
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

require_once 'protected/vendors/pyrus/vendor/php/VersionControl/SVN.php';

// Setup error handling -- always a good idea!
$svnstack = &PEAR_ErrorStack::singleton('VersionControl_SVN');

// Set up runtime options.
$options = array('fetchmode' => VERSIONCONTROL_SVN_FETCHMODE_ASSOC);
// Request log class from factory
$svn = VersionControl_SVN::factory('log', $options);

// Define any switches and aguments we may need
$switches = array('verbose' => 'true');
$args = array('http://phpsvnclient.googlecode.com/svn');

// Run command
if ($output = $svn->run($args, $switches)) {
    echo "<pre>";
    print_r($output);
    echo "</pre>";
} else {
    if (count($errs = $svnstack->getErrors())) {
        foreach ($errs as $err) {
            echo '<br />'.$err['message']."<br />\n";
            echo "Command used: " . $err['params']['cmd'];
        }
    }
}

$options = array('fetchmode' => VERSIONCONTROL_SVN_FETCHMODE_RAW);

$switches = array('r' => '113:131');

$args = array('http://phpsvnclient.googlecode.com/svn/trunk/phpsvnclient.php');

$svn = VersionControl_SVN::factory(array('diff'), $options);

if ($diffs = $svn->diff->run($args, $switches)) {
//echo "<pre>$diffs</pre>";
$diffs = implode("\n", array_slice(explode("\n", $diffs), 2));
echo "<div class=diff>";
echo "diff --git\n";
echo htmlspecialchars($diffs);
echo "</div>";
} else {
    if (count($errs = $svnstack->getErrors())) {
        foreach ($errs as $err) {
            echo '<br />'.$err['message']."<br />\n";
            echo "Command used: " . $err['params']['cmd'];
        }
    }
}

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