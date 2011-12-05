#!/usr/bin/php
<?php
// $Id: xcvs-posttag.php,v 1.13 2009/02/14 15:02:02 jpetso Exp $
/**
 * @file
 * Insert branch/tag info into the Drupal database by processing
 * command line input and sending it to the Version Control API.
 *
 * Copyright 2005 by Kjartan Mannes ("Kjartan", http://drupal.org/user/2)
 * Copyright 2006, 2007 by Derek Wright ("dww", http://drupal.org/user/46549)
 * Copyright 2007, 2008 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 */

function xcvs_help($cli, $output_stream) {
  fwrite($output_stream, "Usage: $cli <config file> \$USER %t %b %o %p %{sTVv}\n\n");
}

function xcvs_exit($status, $lastlog, $summary, $taginfo) {
  @unlink($lastlog);
  @unlink($summary);
  @unlink($taginfo);
  exit($status);
}

function xcvs_init($argc, $argv) {
  $this_file = array_shift($argv);   // argv[0]

  if ($argc < 7) {
    xcvs_help($this_file, STDERR);
    exit(3);
  }

  $config_file = array_shift($argv); // argv[1]

  // Load the configuration file and bootstrap Drupal.
  if (!file_exists($config_file)) {
    fwrite(STDERR, "Error: failed to load configuration file.\n");
    exit(4);
  }
  include_once $config_file;

  // Check temporary file storage.
  $tempdir = xcvs_get_temp_directory($xcvs['temp']);

  $username = array_shift($argv); // argv[2]
  $tag_name = array_shift($argv); // argv[3]
  $type = array_shift($argv);     // argv[4]
  $cvs_op = array_shift($argv);   // argv[5]
  $dir = array_shift($argv);      // argv[6]

  // Do a full Drupal bootstrap.
  xcvs_bootstrap($xcvs);

  // The commitinfo script wrote the lastlog file for us.
  // Its only contents is the name of the last directory that commitinfo
  // was invoked with, and that order is the same one as for loginfo.
  $lastlog = $tempdir .'/xcvs-lastlog.'. posix_getpgrp();
  $summary = $tempdir .'/xcvs-summary.'. posix_getpgrp();
  $taginfo = $tempdir .'/xcvs-taginfo.'. posix_getpgrp();

  // Write the tagged/branched items to a temporary log file, one by one.
  while (!empty($argv)) {
    $filename = array_shift($argv);
    $source_branch = array_shift($argv);
    $source_branch = empty($source_branch) ? 'HEAD' : $source_branch;
    $old = array_shift($argv);
    $new = array_shift($argv);
    xcvs_log_add($summary, "/$dir/$filename,$source_branch,$old,$new\n", 'a');
  }

  // Once all logs in a multi-directory tagging/branching operation have been
  // gathered, the currently processed directory matches the last processed
  // directory that taginfo was invoked with, which means we've got all the
  // needed data in the summary file (and the taginfo file that xcvs-taginfo
  // has written before).
  if (xcvs_is_last_directory($lastlog, $dir)) {
    // The taginfo script was nice enough to determine the label type for all
    // files where the branch or tag was deleted.
    if ($cvs_op == 'del') {
      $fd = fopen($taginfo, 'r');
      if ($fd === FALSE) {
        fwrite(STDERR, "Error: failed to open taginfo log at $summary.\n");
        xcvs_exit(5, $lastlog, $summary, $taginfo);
      }
      $label_types = array();

      while (!feof($fd)) {
        $tag_entry = trim(fgets($fd));
        list($path, $ltype) = explode(',', $tag_entry);
        $label_types[$path] = ($ltype == 'N')
                              ? VERSIONCONTROL_OPERATION_TAG
                              : VERSIONCONTROL_OPERATION_BRANCH;
      }
      fclose($fd);
    }

    // Convert the previously written temporary log file
    // to Version Control API's item format.
    $fd = fopen($summary, 'r');
    if ($fd === FALSE) {
      fwrite(STDERR, "Error: failed to open summary log at $summary.\n");
      xcvs_exit(6, $lastlog, $summary, $taginfo);
    }
    $items = array();

    while (!feof($fd)) {
      $file_entry = trim(fgets($fd));
      if (!$file_entry) {
        continue;
      }
      list($path, $source_branch, $old, $new) = explode(',', $file_entry);

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
          if (!isset($label_types[$path])) {
            fwrite(STDERR, "Could not determine the label type for $path. Not recording into database.\n");
            continue;
          }
          $label_type = $label_types[$path];
        }
        $items[VERSIONCONTROL_ACTION_DELETED][$label_type][$path] = $item;
      }
    }
    fclose($fd);

    if (empty($items)) {
      // If nothing is being tagged, we don't need to log anything.
      xcvs_exit(0, $lastlog, $summary, $taginfo);
    }

    // All data gathered, now let's insert it into the database.
    foreach ($items as $action => $items_by_action) {
      foreach ($items_by_action as $label_type => $operation_items) {
        $operation = array(
          'type' => $label_type,
          'date' => time(),
          'username' => $username,
          'repo_id' => $xcvs['repo_id'],
        );
        $label = array(
          'type' => $label_type,
          'name' => $tag_name,
          'action' => $action,
        );
        $operation['labels'] = array($label);

        // A word of warning to the user, because it's easy to miss this fact.
        if ($label['type'] == VERSIONCONTROL_OPERATION_BRANCH
            && $label['action'] == VERSIONCONTROL_ACTION_ADDED) {
          fwrite(STDERR, t("
** NOTE: Don't forget that creating a branch does NOT
** automatically update your workspace to use that branch.
** If you want to commit to this new branch, you must run:
** cvs update -r !branch\n\n",
            array('!branch' => $label['name'])
          ));
        }

        versioncontrol_insert_operation($operation, $operation_items);
      }
    }

    // Clean up.
    xcvs_exit(0, $lastlog, $summary, $taginfo);
  }
  exit(0);
}

xcvs_init($argc, $argv);
