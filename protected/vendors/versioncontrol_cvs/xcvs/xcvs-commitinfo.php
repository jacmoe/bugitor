#!/usr/bin/php
<?php
// $Id: xcvs-commitinfo.php,v 1.10 2009/02/14 19:10:45 jpetso Exp $
/**
 * @file
 * Provides access checking for 'cvs commit' commands.
 *
 * Copyright 2005 by Kjartan Mannes ("Kjartan", http://drupal.org/user/2)
 * Copyright 2006, 2007 by Derek Wright ("dww", http://drupal.org/user/46549)
 * Copyright 2007, 2008 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 */

function xcvs_help($cli, $output_stream) {
  fwrite($output_stream, "Usage: $cli <config file> \$USER /%p %{s}\n\n");
}

function xcvs_get_operation_item($filename, $dir, $cwd) {
  // Determine if the committed files were added, deleted or modified,
  // and construct an appropriate commit action entry for each file.
  // It checks for the existence of the file in the repository and/or
  // the working copy - see the commitinfo page of the CVS info manual
  // for a more detailed description of how this stuff works. Ugly, imho.

  $repository_path = $dir .'/'. $filename;

  $filepath_repository = $_ENV['CVSROOT'] . $dir .'/'. $filename .',v';
  $filepath_attic = $_ENV['CVSROOT'] . $dir .'/Attic/'. $filename .',v';
  $exists_in_repository = (is_file($filepath_repository) || is_file($filepath_attic));

  $filepath_workingcopy = $cwd .'/'. $filename;
  $exists_in_workingcopy = is_file($filepath_workingcopy);

  $item = array(
    'type' => VERSIONCONTROL_ITEM_FILE,
    'path' => $repository_path,
    'source_items' => array(),
  );

  if (!$exists_in_repository) {
    $item['action'] = VERSIONCONTROL_ACTION_ADDED;
  }
  else if (!$exists_in_workingcopy) {
    $item['action'] = VERSIONCONTROL_ACTION_DELETED;
    $item['type'] = VERSIONCONTROL_ITEM_FILE_DELETED;
  }
  else {
    $item['action'] = VERSIONCONTROL_ACTION_MODIFIED;
  }

  if ($exists_in_repository) {
    $action['source_items'][] = array(
      'type' => VERSIONCONTROL_ITEM_FILE,
      'path' => $repository_path,
    );
  }

  return array($repository_path, $action);
}

/**
 * See if the current commit has a sticky tag, and if so, add it as
 * operation label so that it can be validated to be a valid branch.
 *
 * @param $cwd
 *   The original current working directory when the script was called.
 *   (Bootstrapping Drupal changes getcwd() to the Drupal root directory.)
 */
function xcvs_commit_labels($cwd) {
  if (!is_dir($cwd .'/CVS')) {
    fwrite(STDERR, "** ERROR: No local CVS directory during commit, aborting.\n\n");
    exit(5);
  }
  $labels = array();

  if (file_exists($cwd .'/CVS/Tag')) {
    // There's a sticky tag, validate it.
    $sticky_tag = '';
    $tag_file = trim(file_get_contents($cwd .'/CVS/Tag'));
    if (!empty($tag_file)) {
      // Get the sticky tag for this commit: strip off the leading 'T or N'.
      $sticky_tag = preg_replace('@^(T|N)@', '', $tag_file);
    }
    if (!empty($sticky_tag)) {
      $labels[$sticky_tag] = array(
        'type' => VERSIONCONTROL_OPERATION_BRANCH,
        'name' => $sticky_tag,
        'action' => VERSIONCONTROL_ACTION_MODIFIED,
      );
    }
  }
  // To be extra paranoid, check everything in CVS/Entries, too.
  if (file_exists($cwd .'/CVS/Entries')) {
    $entries = file($cwd .'/CVS/Entries');

    if (!empty($entries)) {
      foreach ($entries as $entry) {
        $parts = explode('/', trim($entry));
        if (empty($parts[5])) {
          continue;
        }
        $sticky_tag = preg_replace('@^(T|N)@', '', trim($parts[5]));

        if (isset($labels[$sticky_tag])) {
          continue;
        }
        $labels[$sticky_tag] = array(
          'type' => VERSIONCONTROL_OPERATION_BRANCH,
          'name' => $sticky_tag,
          'action' => VERSIONCONTROL_ACTION_MODIFIED,
        );
      }
    }
  }
  $labels = array_values($labels);

  if (empty($labels)) {
    $labels[] = array(
      'type' => VERSIONCONTROL_OPERATION_BRANCH,
      'name' => 'HEAD',
      'action' => VERSIONCONTROL_ACTION_MODIFIED,
    );
  }
  return $labels;
}

function xcvs_init($argc, $argv) {
  $this_file = array_shift($argv);   // argv[0]

  if ($argc < 5) {
    xcvs_help($this_file, STDERR);
    exit(3);
  }

  $files = array_slice($argv, 4);

  $config_file = array_shift($argv); // argv[1]
  $username = array_shift($argv);    // argv[2]
  $dir = array_shift($argv);         // argv[3]
  $filenames = $argv; // the rest of the command line arguments

  // Load the configuration file and bootstrap Drupal.
  if (!file_exists($config_file)) {
    fwrite(STDERR, "Error: failed to load configuration file.\n");
    exit(4);
  }
  include_once $config_file;

  // Check temporary file storage.
  $tempdir = xcvs_get_temp_directory($xcvs['temp']);

  // Admins and other privileged users don't need to go through any checks.
  if (!in_array($username, $xcvs['allowed_users'])) {
    // Do a full Drupal bootstrap.
    xcvs_bootstrap($xcvs);

    // Construct a minimal commit operation array.
    $operation = array(
      'type' => VERSIONCONTROL_OPERATION_COMMIT,
      'repo_id' => $xcvs['repo_id'],
      'username' => $username,
      'labels' => xcvs_commit_labels($xcvs['cwd']),
    );

    $operation_items = array();
    foreach ($filenames as $filename) {
      list($path, $item) = xcvs_get_operation_item($filename, $dir, $xcvs['cwd']);
      $operation_items[$path] = $item;
    }
    $access = versioncontrol_has_write_access($operation, $operation_items);

    // Fail and print out error messages if commit access has been denied.
    if (!$access) {
      fwrite(STDERR, implode("\n\n", versioncontrol_get_access_errors()) ."\n\n");
      exit(6);
    }
  }
  // If we get as far as this, the commit may happen.

  // Remember this directory so that loginfo can combine commits
  // from different directories in one commit entry.
  $lastlog = $tempdir .'/xcvs-lastlog.'. posix_getpgrp();
  xcvs_log_add($lastlog, $dir);

  exit(0);
}

xcvs_init($argc, $argv);
