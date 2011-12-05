#!/usr/bin/php
<?php
// $Id: xcvs-generate-passwd.php,v 1.2 2008/01/08 22:18:50 thehunmonkgroup Exp $
/**
 * @file
 * Connects to the Drupal database of a site using the Version Control API
 * and generates the CVSROOT/passwd file necessary for users to authenticate to
 * the CVS repository using "cvs login".
 *
 * This script should be periodically run from cron (outside of the Drupal
 * site) and the output sent to the CVSROOT/passwd file for the CVS repository
 * (or repositories) where your Drupal users with approved CVS accounts should
 * have access. You need to set the DRUPAL_SITE constant if you want the output
 * to have nice links to the accounts' corresponding user pages.
 *
 * Copyright 2007 by Derek Wright ("dww", http://drupal.org/user/46549)
 * Copyright 2007 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 */

/**
 * The base of your Drupal site (used to generate comments with links to user
 * accounts).  For example "http://drupal.org", or "https://example.com/versioncontrol".
 */
define('DRUPAL_SITE', 'http://example.com');

function xcvs_help($cli, $output_stream) {
  fwrite($output_stream, "Usage: $cli <config file> > <CVSROOT>/passwd\n\n");
}

function xcvs_init($argc, $argv) {
  $this_file = array_shift($argv);   // argv[0]
  $config_file = array_shift($argv); // argv[1]

  if ($argc < 2) {
    xcvs_help($this_file, STDERR);
    exit(3);
  }

  // Load the configuration file and bootstrap Drupal.
  if (!file_exists($config_file)) {
    fwrite(STDERR, "Error: failed to load configuration file.\n");
    exit(4);
  }
  include_once $config_file;

  // Do a full Drupal bootstrap.
  xcvs_bootstrap($xcvs);

  $repository = versioncontrol_get_repository($xcvs['repo_id']);
  if (!isset($repository)) {
    fwrite(STDERR, "The repository corresponding to the configured repo id could not be loaded.\n");
    exit(1);
  }

  // Set the Drupal base path, so that url() returns a proper URL.
  global $base_url;
  $base_url = DRUPAL_SITE;

  // Do the export!
  fwrite(STDOUT, versioncontrol_export_accounts($repository));
  exit(0);
}

xcvs_init($argc, $argv);
