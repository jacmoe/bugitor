#!/usr/bin/php
<?php
// $Id: xcvs-loginfo.php,v 1.30 2009/02/14 19:08:38 jpetso Exp $
/**
 * @file
 * Insert commit info into the Drupal database by processing command line input
 * and sending it to the Version Control API.
 *
 * Copyright 2005 by Kjartan Mannes ("Kjartan", http://drupal.org/user/2)
 * Copyright 2006, 2007 by Derek Wright ("dww", http://drupal.org/user/46549)
 * Copyright 2007 by Adam Light ("aclight", http://drupal.org/user/86358)
 * Copyright 2007, 2008 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 */

function xcvs_help($cli, $output_stream) {
  fwrite($output_stream, "Usage: $cli <config file> \$USER %p %{sVv}\n\n");
}

function xcvs_exit($status, $lastlog, $summary) {
  @unlink($lastlog);
  @unlink($summary);
  exit($status);
}

function xcvs_get_operation_item($file_entry) {
  if ($file_entry) {
    list($path, $old, $new) = explode(",", $file_entry);

    if ($old == 'dir') { // directories can only be added in CVS
      $item = array(
        'type' => VERSIONCONTROL_ITEM_DIRECTORY,
        'path' => $path,
        'revision' => '',
        'action' => VERSIONCONTROL_ACTION_ADDED,
      );
      return array($path, $item);
    }

    $item = array(
      'type' => VERSIONCONTROL_ITEM_FILE,
      'path' => $path,
      'revision' => $new,
      'source_items' => array(),
    );

    // If it's not a directory, it must be one of three possible file actions
    if ($old == 'NONE') {
      $item['action'] = VERSIONCONTROL_ACTION_ADDED;
    }
    else if ($new == 'NONE') {
      $item['action'] = VERSIONCONTROL_ACTION_DELETED;
      $item['type'] = VERSIONCONTROL_ITEM_FILE_DELETED;
      // $item['revision'] stores 'NONE' for the time being,
      // this is being fixed in xcvs_fix_operation_items().
    }
    else {
      $item['action'] = VERSIONCONTROL_ACTION_MODIFIED;
    }

    if ($old != 'NONE') {
      $item['source_items'][] = array(
        'type' => VERSIONCONTROL_ITEM_FILE,
        'path' => $path,
        'revision' => $old,
      );
    }

    return array($path, $item);
  }
}

/**
 * For deleted items, CVS doesn't tell us their new (deleted) revision,
 * although it exists and is recorded by CVS itself. We need that revision for
 * uniqueness of the item entry in the database, and for the CVS backend
 * recognizing the "modified file is actually an added file" case.
 * So let's get the revision identifier of the deleted file.
 */
function xcvs_fix_operation_items(&$operation_items, $branch_name) {
  foreach ($operation_items as $path => $item) {
    if ($item['revision'] != 'NONE') {
      continue;
    }

    $trimmed_path = trim($item['path'], '/');
    unset($output_lines);
    exec("cvs -Qn -d $_ENV[CVSROOT] rlog -N -r$old::$branch_name -s dead $trimmed_path", $output_lines);

    $matches = array();
    foreach ($output_lines as $line) {
      // 'revision 1.6.2.23'
      if (preg_match('/^revision ([\d\.]+).*$/', $line, $matches)) {
        $operation_items[$path]['revision'] = $matches[1];
        break;
      }
    }
  }
}

/**
 * Fetch the amount of added and removed lines for each file.
 */
function xcvs_fetch_item_line_changes(&$operation_items) {
  // Find out how many lines have been added and removed for each file.
  foreach ($operation_items as $path => $item) {
    if ($item['action'] == VERSIONCONTROL_ACTION_DELETED
        || $item['type'] == VERSIONCONTROL_ITEM_DIRECTORY) {
      continue;
    }

    $current_rev = $item['revision'];
    $trimmed_path = trim($item['path'], '/');
    unset($output_lines);
    exec("cvs -Qn -d $_ENV[CVSROOT] rlog -N -r$current_rev $trimmed_path", $output_lines);

    $matches = array();
    foreach ($output_lines as $line) {
      // 'date: 2004/08/20 07:51:22;  author: dries;  state: Exp;  lines: +2 -2'
      if (preg_match('/^date: .+;\s+lines: \+(\d+) -(\d+).*$/', $line, $matches)) {
        $operation_items[$path]['line_changes'] = array(
          'added'   => (int) $matches[1],
          'removed' => (int) $matches[2],
        );
        break;
      }
    }
  }
}

/**
 * Go through the log message on the given input stream (yeah, I mean STDIN)
 * in order to extract branch and commit message.
 */
function xcvs_parse_log($input_stream) {
  $branch = 'HEAD';
  do {
    $line = trim(fgets($input_stream));
    if (preg_match("/^Tag:\s+(.+)$/", $line, $matches)) {
      $branch = trim($matches[1]);
    }
  } while ($line != "Log Message:");

  $message = "";
  while (!feof($input_stream)) {
    $message .= fgets($input_stream);
  }
  $message = trim($message);

  return array($branch, $message);
}

/**
 * Main function and starting point of this script:
 * Bootstrap Drupal, gather commit data and pass it on to Version Control API.
 */
function xcvs_init($argc, $argv) {
  $date = time(); // remember the time of the current commit for later
  $this_file = array_shift($argv);   // argv[0]

  if ($argc < 7) {
    xcvs_help($this_file, STDERR);
    exit(3);
  }

  $config_file = array_shift($argv); // argv[1]
  $username = array_shift($argv);    // argv[2]
  $commitdir = '/'. array_shift($argv);   // argv[3]

  // Load the configuration file and bootstrap Drupal.
  if (!file_exists($config_file)) {
    fwrite(STDERR, "Error: failed to load configuration file.\n");
    exit(4);
  }
  include_once $config_file;

  // Check temporary file storage.
  $tempdir = xcvs_get_temp_directory($xcvs['temp']);

  // The commitinfo script wrote the lastlog file for us.
  // Its only contents is the name of the last directory that commitinfo
  // was invoked with, and that order is the same one as for loginfo.
  $lastlog = $tempdir .'/xcvs-lastlog.'. posix_getpgrp();
  $summary = $tempdir .'/xcvs-summary.'. posix_getpgrp();

  // Write the changed items to a temporary log file, one by one.
  if (!empty($argv)) {
    if ($argv[0] == '- New directory') {
      xcvs_log_add($summary, "$commitdir,dir\n", 'a');
    }
    else {
      while (!empty($argv)) {
        $filename = array_shift($argv);
        $old = array_shift($argv);
        $new = array_shift($argv);
        xcvs_log_add($summary, "$commitdir/$filename,$old,$new\n", 'a');
      }
    }
  }

  // Once all logs in a multi-directory commit have been gathered,
  // the currently processed directory matches the last processed directory
  // that commitinfo was invoked with, which means we've got all the
  // needed data in the summary file.
  if (xcvs_is_last_directory($lastlog, $commitdir)) {
    // Convert the previously written temporary log file
    // to Version Control API's commit action format.
    $fd = fopen($summary, "r");
    if ($fd === FALSE) {
      fwrite(STDERR, "Error: failed to open summary log at $summary.\n");
      xcvs_exit(5, $lastlog, $summary);
    }
    $operation_items = array();

    // Do a full Drupal bootstrap. We need it from now on at the latest,
    // starting with the action constants in xcvs_get_operation_item().
    xcvs_bootstrap($xcvs);

    while (!feof($fd)) {
      $file_entry = trim(fgets($fd));
      list($path, $item) = xcvs_get_operation_item($file_entry);
      if ($path) {
        $operation_items[$path] = $item;
      }
    }
    fclose($fd);

    // Integrate with the Drupal Version Control API.
    if (!empty($operation_items)) {
      // Get the remaining info from the commit log that we get from STDIN.
      list($branch_name, $message) = xcvs_parse_log(STDIN);

      // Add the real revision to deleted items.
      xcvs_fix_operation_items($operation_items, $branch_name);

      // Determine how many lines were added and removed for a given file.
      xcvs_fetch_item_line_changes($operation_items);

      // Prepare the data for passing it to Version Control API.
      $operation = array(
        'type' => VERSIONCONTROL_OPERATION_COMMIT,
        'repo_id' => $xcvs['repo_id'],
        'date' => $date,
        'username' => $username,
        'message' => $message,
        'revision' => '',
        'labels' => array(
          array(
            'type' => VERSIONCONTROL_OPERATION_BRANCH,
            'name' => $branch_name,
            'action' => VERSIONCONTROL_ACTION_MODIFIED,
          ),
        ),
      );
      _versioncontrol_cvs_fix_commit_operation_items($operation, $operation_items);
      $operation = versioncontrol_insert_operation($operation, $operation_items);

      if (!empty($operation)) {
        fwrite(STDERR, t("Recorded as commit !id.\n", array(
          '!id' => versioncontrol_format_operation_revision_identifier($operation),
        )));
      }
    }

    // Clean up
    xcvs_exit(0, $lastlog, $summary);
  }
  exit(0);
}

xcvs_init($argc, $argv);
