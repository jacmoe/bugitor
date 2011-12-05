#!/usr/bin/php
<?php
// $Id: xgit-loginfo.php,v 1.2 2008/02/03 19:48:44 boombatower Exp $
/**
 * @file
 * Insert commit info into the Drupal database by loading Git backend and
 * executing repository update.
 *
 * Copyright 2008 by Jimmy Berry ("boombatower", http://drupal.org/user/214218)
 */

function xgit_help() {
  echo "Usage: Should be called by Git.\n\n";
}

/**
 * Main function and starting point of this script:
 * Bootstrap Drupal, gather commit data and pass it on to Version Control API.
 */
function xgit_init($argc, $argv) {
  $date = time(); // Remember the time of the current commit for later.
  array_shift($argv); // argv[0]

  if ($argc < 2) {
    xgit_help();
    exit(3);
  }

  $config_file = array_shift($argv); // argv[1]

  // Load the configuration file and bootstrap Drupal.
  if (!file_exists($config_file)) {
    echo "Error: failed to load configuration file.\n";
    exit(4);
  }

  include_once $config_file;

  // Do a full Drupal bootstrap.
  xgit_bootstrap($xgit);

  // Execute update.
  $repository = versioncontrol_get_repository($xgit['repo_id']);
  if (!isset($repository)) {
    echo "The repository corresponding to the configured repo id could not be loaded.\n";
    exit(1);
  }
  if (!ini_get('safe_mode')) {
    set_time_limit(3600);
  }
  _versioncontrol_git_update_repository($repository);
  exit(0);
}

xgit_init($argc, $argv);
