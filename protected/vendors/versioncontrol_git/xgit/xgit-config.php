<?php
// $Id: xgit-config.php,v 1.2 2008/02/03 19:48:44 boombatower Exp $
/**
 * @file
 * Configuration variables and bootstrapping code for all Git hook scripts.
 *
 * Copyright 2008 by Jimmy Berry ("boombatower", http://drupal.org/user/214218)
 */

// ------------------------------------------------------------
// Required customization
// ------------------------------------------------------------

// Base path of drupal directory (no trailing slash)
$xgit['drupal_path'] = '';

// File location where to store temporary files.
$xgit['temp'] = "";

// Drupal repository id that this installation of scripts is going to
// interact with. In order to find out the repository id, go to the
// "VCS repositories" administration page, then click on the "edit" link of
// the concerned repository, and notice the final number in the resulting URL.
$xgit['repo_id'] = -1;


// ------------------------------------------------------------
// Optional customization
// ------------------------------------------------------------

// These users are always allowed full access, even if we can't
// connect to the DB. This optional list should contain the Git
// usernames (not the Drupal username if they're different).
$xgit['allowed_users'] = array();

// If you run a multisite installation, specify the directory
// name that your settings.php file resides in (ex: www.example.com)
// If you use the default settings.php file, leave this blank.
$xgit['multisite_directory'] = '';

// ------------------------------------------------------------
// Access control
// ------------------------------------------------------------

// Boolean to specify if users should be allowed to delete tags (= branches).
$xgit['allow_tag_removal'] = TRUE;

// Error message for the above permission.
$xgit['tag_delete_denied_message'] = <<<EOF
** ERROR: You are not allowed to delete tags.

EOF;


// ------------------------------------------------------------
// Shared code
// ------------------------------------------------------------

function xgit_bootstrap($xgit) {

  // Add $drupal_path to current value of the PHP include_path.
  set_include_path(get_include_path() . PATH_SEPARATOR . $xgit['drupal_path']);

  $current_directory = getcwd();
  chdir($xgit['drupal_path']);

  // Bootstrap Drupal so we can use drupal functions to access the databases, etc.
  if (!file_exists('./includes/bootstrap.inc')) {
    fwrite(STDERR, "Error: failed to load Drupal's bootstrap.inc file.\n");
    exit(1);
  }

  // Set up the multisite directory if necessary.
  if ($xgit['multisite_directory']) {
    $_SERVER['HTTP_HOST'] = $xgit['multisite_directory'];
    // Set a dummy script name, so the multisite configuration
    // file search will always trigger.
    $_SERVER['SCRIPT_NAME'] = '/foo';
  }

  require_once './includes/bootstrap.inc';
  drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

  chdir($current_directory);
}

function xgit_get_temp_directory($temp_path) {
  $tempdir = preg_replace('/\/+$/', '', $temp_path); // Strip trailing slashes.
  if (!(is_dir($tempdir) && is_writeable($tempdir))) {
    fwrite(STDERR, "Error: failed to access the temporary directory ($tempdir).\n");
    exit(2);
  }
  return $tempdir;
}

function xgit_log_add($filename, $dir, $mode = 'w') {
  $fd = fopen($filename, $mode);
  fwrite($fd, $dir);
  fclose($fd);
}

function xgit_is_last_directory($logfile, $dir) {
  if (file_exists($logfile)) {
    $fd = fopen($logfile, "r");
    $last = fgets($fd);
    fclose($fd);
    return $dir == $last ? TRUE : FALSE;
  }
  return TRUE;
}
