#!/usr/bin/php
<?php
// $Id: xcvs-taginfo.php,v 1.15 2009/01/04 14:10:29 jpetso Exp $
/**
 * @file
 * Provides access checking for 'cvs tag' commands, and prepares data for
 * the xcvs-posttag.php script.
 *
 * Copyright 2005 by Kjartan Mannes ("Kjartan", http://drupal.org/user/2)
 * Copyright 2006, 2007 by Derek Wright ("dww", http://drupal.org/user/46549)
 * Copyright 2007, 2008 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 */

function xcvs_help($cli, $output_stream) {
  fwrite($output_stream, "Usage: $cli <config file> \$USER %t %b %o %p %{sTVv}\n\n");
}

/**
 * Determine whether the given tag name corresponds to a tag or branch
 * for the given (file) item. Don't even try to pass a directory item here,
 * because unbelievably bad things will happen when you do.
 *
 * @return
 *   Either VERSIONCONTROL_OPERATION_BRANCH or VERSIONCONTROL_OPERATION_TAG,
 *   depending on the outcome. In the unlikely case that this script has made
 *   an error when retrieving the file information, it exits program execution
 *   with a non-zero error code.
 */
function xcvs_branch_or_tag($tag_name, $item) {
  $trimmed_path = trim($item['path'], '/');
  exec("cvs -Qn -d $_ENV[CVSROOT] rlog -h $trimmed_path", $output_lines);

  $scanning = TRUE;
  $matches = array();

  foreach ($output_lines as $line) {
    if ($scanning) {
      if (trim($line) == 'symbolic names:') {
        $scanning = FALSE;
      }
    }
    else {
      if (strpos($line, 'keyword substitution:') !== FALSE) {
        // No branches and tags anymore, should not happen.
        // If it does, fail hard.
        break;
      }
      $parts = explode(':', trim($line)); // e.g. "DRUPAL-5--2-0: 1.4"
      $symbolic_name = trim($parts[0]);

      if ($symbolic_name != $tag_name) { // We're searching for the given name.
        continue;
      }
      // Found the specified tag!
      $revision = trim($parts[1]);

      // If the revision ends with "0.N", we know this is a branch.
      if (preg_match('/\.0\.\d+$/', $revision)) {
        return VERSIONCONTROL_OPERATION_BRANCH;
      }
      // Otherwise, it's a real revision, and the symbolic name is a tag.
      return VERSIONCONTROL_OPERATION_TAG;
    }
  }
  // Just in case we made an error - should not happen.
  fwrite(STDERR, "Error: symbolic name not found.\n");
  exit(6);
}

function xcvs_init($argc, $argv) {
  $this_file = array_shift($argv);   // argv[0]

  if ($argc < 7) {
    xcvs_help($this_file, STDERR);
    exit(3);
  }

  $config_file = array_shift($argv); // argv[1]
  $username = array_shift($argv);    // argv[2]
  $tag_name = array_shift($argv);    // argv[3]
  $type = array_shift($argv);        // argv[4]
  $cvs_op = array_shift($argv);      // argv[5]
  $dir = array_shift($argv);         // argv[6]

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

    // Gather info for each tagged/branched file.
    $items = array();

    while (!empty($argv)) {
      $filename = array_shift($argv);
      $source_branch = array_shift($argv);
      $old = array_shift($argv);
      $new = array_shift($argv);
      $path = '/'. $dir .'/'. $filename;

      if ($type == 'N') { // is a tag
        $label_type = VERSIONCONTROL_OPERATION_TAG;
      }
      else if ($type == 'T') { // is a branch
        $label_type = VERSIONCONTROL_OPERATION_BRANCH;
      }

      if (in_array($cvs_op, array('add', 'mov'))) {
        $items[VERSIONCONTROL_ACTION_ADDED][$label_type][$path] = array(
          'type' => VERSIONCONTROL_ITEM_FILE,
          'path' => $path,
          'revision' => $new,
        );
        // $source_branch is not currently being used by Version Control API.
      }
      if (in_array($cvs_op, array('del', 'mov'))) {
        $item = array(
          'type' => VERSIONCONTROL_ITEM_FILE,
          'path' => $path,
          'revision' => $old,
        );
        if ($type == '?') {
          $label_type = xcvs_branch_or_tag($tag_name, $item);
        }
        $items[VERSIONCONTROL_ACTION_DELETED][$label_type][$path] = $item;
      }
    }

    if (empty($items)) {
      fwrite(STDERR, "Operation doesn't affect any item, aborting.\n");
      exit(7);
    }

    foreach ($items as $action => $items_by_action) {
      foreach ($items_by_action as $label_type => $operation_items) {
        $operation = array(
          'type' => $label_type,
          'username' => $username,
          'repo_id' => $xcvs['repo_id'],
        );
        $label = array(
          'type' => $label_type,
          'name' => $tag_name,
          'action' => $action,
        );
        $operation['labels'] = array($label);

        $access = versioncontrol_has_write_access($operation, $operation_items);

        // Fail and print out error messages if branch/tag access has been denied.
        if (!$access) {
          fwrite(STDERR, implode("\n\n", versioncontrol_get_access_errors()) ."\n\n");
          exit(7);
        }
      }
    }
  }
  // If we get as far as this, the tagging/branching operation may happen.

  // Remember this directory so that posttag can combine tags/branches
  // from different directories in one tag/branch entry.
  $lastlog = $tempdir .'/xcvs-lastlog.'. posix_getpgrp();
  xcvs_log_add($lastlog, $dir);

  // Also remember the label type per item - posttag won't be able to determine
  // this by itself because the action has already been executed. Only needed
  // for branch/tag deletions - 'add' and 'mov' don't need this kind of crap.
  if ($cvs_op == 'del') {
    $taginfo = $tempdir .'/xcvs-taginfo.'. posix_getpgrp();

    foreach ($items as $action => $items_by_action) {
      foreach ($items_by_action as $label_type => $operation_items) {
        foreach ($operation_items as $path => $item) {
          $ltype = ($label_type == VERSIONCONTROL_OPERATION_TAG) ? 'N' : 'T';
          xcvs_log_add($taginfo, "$path,". $ltype ."\n", 'a');
        }
      }
    }
  }

  exit(0);
}

xcvs_init($argc, $argv);
