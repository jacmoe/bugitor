<?php
// $Id: xcvs-config.php,v 1.20 2009/02/14 19:10:45 jpetso Exp $
/**
 * @file
 * Configuration variables and bootstrapping code for all CVS hook scripts.
 *
 * Copyright 2005 by Kjartan Mannes ("Kjartan", http://drupal.org/user/2)
 * Copyright 2006, 2007 by Derek Wright ("dww", http://drupal.org/user/46549)
 * Copyright 2007 by Adam Light ("aclight", http://drupal.org/user/86358)
 * Copyright 2007, 2008, 2009 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 * Copyright 2008 by Chad Phillips ("hunmonk", http://drupal.org/user/22079)
 */

// ------------------------------------------------------------
// Required customization
// ------------------------------------------------------------

// Base path of drupal directory (no trailing slash)
$xcvs['drupal_path'] = '/home/username/public_html';

// File location where to store temporary files.
$xcvs['temp'] = '/tmp';

// Drupal repository id that this installation of scripts is going to
// interact with. In order to find out the repository id, go to the
// "VCS repositories" administration page, then click on the "edit" link of
// the concerned repository, and notice the final number in the resulting URL.
$xcvs['repo_id'] = 1;


// ------------------------------------------------------------
// Optional customization
// ------------------------------------------------------------

// These users are always allowed full access, even if we can't
// connect to the DB. This optional list should contain the CVS
// usernames (not the Drupal username if they're different).
$xcvs['allowed_users'] = array();

// If you run a multisite installation, specify the directory
// name that your settings.php file resides in (ex: www.example.com)
// If you use the default settings.php file, leave this blank.
$xcvs['multisite_directory'] = '';


// ------------------------------------------------------------
// Shared code
// ------------------------------------------------------------

// Store the current working directory at include time,
// because it's being changed when Drupal is bootstrapped.
$xcvs['cwd'] = getcwd();

/**
 * Bootstrap all of Drupal (DRUPAL_BOOTSTRAP_FULL phase) and set the
 * current working directory to the Drupal root path.
 */
function xcvs_bootstrap($xcvs) {
  chdir($xcvs['drupal_path']);

  // Bootstrap Drupal so we can use Drupal functions to access the databases, etc.
  if (!file_exists('./includes/bootstrap.inc')) {
    fwrite(STDERR, "Error: failed to load Drupal's bootstrap.inc file.\n");
    exit(1);
  }

  // Set up a few variables, Drupal might not bootstrap without those.
  // Copied from scripts/drupal.sh.
  $_SERVER['HTTP_HOST']       = 'default';
  $_SERVER['PHP_SELF']        = '/index.php';
  $_SERVER['REMOTE_ADDR']     = '127.0.0.1';
  $_SERVER['SERVER_SOFTWARE'] = 'PHP CLI';
  $_SERVER['REQUEST_METHOD']  = 'GET';
  $_SERVER['QUERY_STRING']    = '';
  $_SERVER['PHP_SELF']        = $_SERVER['REQUEST_URI'] = '/';

  // Set up the multisite directory if necessary.
  if ($xcvs['multisite_directory']) {
    $_SERVER['HTTP_HOST'] = $xcvs['multisite_directory'];
    // Set a dummy script name, so the multisite configuration
    // file search will always trigger.
    $_SERVER['SCRIPT_NAME'] = '/foo';
  }

  require_once './includes/bootstrap.inc';
  drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
}

function xcvs_get_temp_directory($temp_path) {
  $tempdir = preg_replace('/\/+$/', '', $temp_path); // strip trailing slashes
  if (!(is_dir($tempdir) && is_writeable($tempdir))) {
    fwrite(STDERR, "Error: failed to access the temporary directory ($tempdir).\n");
    exit(2);
  }
  return $tempdir;
}

function xcvs_log_add($filename, $dir, $mode = 'w') {
  $fd = fopen($filename, $mode);
  fwrite($fd, $dir);
  fclose($fd);
}

function xcvs_is_last_directory($logfile, $dir) {
  if (file_exists($logfile)) {
    $fd = fopen($logfile, 'r');
    $last = fgets($fd);
    fclose($fd);
    return $dir == $last ? TRUE : FALSE;
  }
  return TRUE;
}
