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
?>