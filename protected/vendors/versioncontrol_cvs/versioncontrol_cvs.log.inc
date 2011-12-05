<?php
// $Id: versioncontrol_cvs.log.inc,v 1.12 2009/01/11 02:27:30 jpetso Exp $
/**
 * @file
 * CVS backend for Version Control API - Provides CVS commit information and
 * account management as a pluggable backend.
 *
 * This file provides functionality to parse the output of 'cvs rlog'
 * and transform it into Version Control API commits.
 *
 * Copyright 2005 by Kjartan Mannes ("Kjartan", http://drupal.org/user/2)
 * Copyright 2006, 2007 by Derek Wright ("dww", http://drupal.org/user/46549)
 * Copyright 2007, 2008, 2009 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 */

/**
 * Actually update the repository by fetching commits and other stuff
 * directly from the repository, invoking the cvs executable.
 *
 * @return
 *   TRUE if the logs were updated, or FALSE if fetching and updating the logs
 *   failed for whatever reason.
 */
function _versioncontrol_cvs_log_update_repository(&$repository) {
  _versioncontrol_cvs_init_cvslib();

  // Remember the current time for the "updated" value that is stored later on.
  $date_updated = time();

  $file_revisions = cvslib_log(
    $repository['root'], $repository['cvs_specific']['modules'],
    array('date_lower' => $repository['cvs_specific']['updated'])
  );
  if ($file_revisions === FALSE) {
    drupal_set_message(cvslib_last_error_message(), 'error');
    return FALSE;
  }

  // Having retrieved the file revisions, insert those into the database
  // as Version Control API commits.
  _versioncontrol_cvs_log_process($repository, $file_revisions);
  $repository['cvs_specific']['updated'] = $date_updated;

  // Everything's done, remember the time when we updated the log (= now).
  db_query('UPDATE {versioncontrol_cvs_repositories}
            SET updated = %d WHERE repo_id = %d',
            $repository['cvs_specific']['updated'], $repository['repo_id']);

  return TRUE;
}

/**
 * Update the database by processing and inserting the previously retrieved
 * file revision objects.
 *
 * @param $repository
 *   The repository array, as given by the Version Control API.
 * @param $file_revisions
 *   A simple, flat list of file revision objects, as returned by cvslib_log().
 */
function _versioncontrol_cvs_log_process($repository, $file_revisions) {
  $operation_items_by_commitid = array();
  $operation_items_by_user = array();

  foreach ($file_revisions as $file_revision) {
    // Don't insert the same revision twice.
    $count = db_result(db_query(
      "SELECT COUNT(*)
       FROM {versioncontrol_item_revisions} ir
          INNER JOIN {versioncontrol_operation_items} opitem
            ON ir.item_revision_id = opitem.item_revision_id
          INNER JOIN {versioncontrol_operations} op
            ON opitem.vc_op_id = op.vc_op_id
       WHERE op.repo_id = %d AND op.type = %d
        AND ir.path = '%s' AND ir.revision = '%s'",
      $repository['repo_id'], VERSIONCONTROL_OPERATION_COMMIT,
      $file_revision->path, $file_revision->revision
    ));
    if ($count > 0) { // Item revision has been recorded already.
      continue;
    }

    // We might only pick one of those (depending if the file
    // has been added, modified or deleted) but let's add both
    // current and source items for now.
    $operation_item = array(
      'type' => VERSIONCONTROL_ITEM_FILE,
      'path' => $file_revision->path,
      'revision' => $file_revision->revision,
      'action' => VERSIONCONTROL_ACTION_MODIFIED, // default, might be changed
      'source_items' => array(
        array(
          'type' => VERSIONCONTROL_ITEM_FILE,
          'path' => $file_revision->path,
          'revision' => versioncontrol_cvs_get_previous_revision_number($file_revision->revision),
        ),
      ),
      'line_changes' => array(
        'added' => $file_revision->lines_added,
        'removed' => $file_revision->lines_removed,
      ),
      'cvs_specific' => array(
        'file_revision' => $file_revision, // temporary
      ),
    );

    if ($file_revision->dead) {
      $operation_item['action'] = VERSIONCONTROL_ACTION_DELETED;
      $operation_item['type'] = VERSIONCONTROL_ITEM_FILE_DELETED;
    }
    else {
      if ($file_revision->revision === '1.1') {
        $operation_item['action'] = VERSIONCONTROL_ACTION_ADDED;
        $operation_item['source_items'] = array();
        unset($operation_item['line_changes']);
      }
    }

    if (isset($file_revision->commitid)) {
      $operation_items_by_commitid[$file_revision->commitid][$file_revision->path] = $operation_item;
    }
    else {
      $operation_items_by_user[$file_revision->username]
        [$file_revision->date][$file_revision->path] = $operation_item;
    }
  }

  $commit_infos_by_date = array();

  // Part one: revisions with commitid - these are cool & easy.
  foreach ($operation_items_by_commitid as $commitid => $operation_items) {
    _versioncontrol_cvs_log_construct_commit_operation(
      $repository, $operation_items, $commit_infos_by_date
    );
  }

  // Part two: revisions without commitid - need to apply heuristics
  // in order to get whole commits instead of separate file-by-file stuff.
  foreach ($operation_items_by_user as $username => $operation_items_by_date) {
    // Iterating through the date sorted array is a bit complicated
    // as we need to delete file revision elements that are determined to be
    // in the same commit as the reference item.
    while ($date = key($operation_items_by_date)) {
      while ($path = key($operation_items_by_date[$date])) {
        $reference_item = array_shift($operation_items_by_date[$date]);

        $operation_items = _versioncontrol_cvs_log_group_items(
          $operation_items_by_date, $reference_item
        );
        _versioncontrol_cvs_log_construct_commit_operation(
          $repository, $operation_items, $commit_infos_by_date
        );
      }
      unset($operation_items_by_date[$date]); // Done with this date, next one.
      reset($operation_items_by_date); // Set the array pointer to the start.
    }
  }

  // Ok, we've got all commit operations gathered and in a nice array with
  // the commit date as key. So the only thing that's left is to sort them
  // and then send each commit to the API function for inserting into the db.
  ksort($commit_infos_by_date);
  foreach ($commit_infos_by_date as $date => $date_commit_infos) {
    foreach ($date_commit_infos as $commit_info) {
      _versioncontrol_cvs_fix_commit_operation_items(
        $commit_info->operation, $commit_info->operation_items
      );
      versioncontrol_insert_operation(
        $commit_info->operation, $commit_info->operation_items
      );
    }
  }
}

/**
 * Extract (and delete) items from the given $operation_items_by_date array
 * that belong to the same commit as the $reference_action.
 * This function is what provides heuristics for grouping file revisions
 * (that lack a commitid) together into one commit operation.
 *
 * @return
 *   One or more items grouped into an $operation_items array,
 *   complete with file paths as keys as required by the Version Control API.
 */
function _versioncontrol_cvs_log_group_items(&$operation_items_by_date, $reference_item) {
  $file_revision = $reference_action['cvs_specific']['file_revision'];

  $operation_items = array();
  $operation_items[$file_revision->path] = $reference_item;

  // Try all file revisions in the near future (next 30 seconds) to see if
  // they belong to the same commit or not. If they do, extract and delete.
  // Commits that take longer than half a minute are unlikely enough to be
  // disregarded here.
  for ($date = $file_revision->date; $date < ($file_revision->date + 30); $date++) {
    if (!isset($operation_items_by_date[$date])) {
      continue;
    }

    foreach ($operation_items_by_date[$date] as $path => $current_item) {
      $current_file_revision = $current_item['cvs_specific']['file_revision'];

      // Check for message and branch to be similar. We know that the username
      // is similar because we sorted by that one, and the date is near enough
      // to be regarded as roughly the same time.
      if ($current_file_revision->message == $file_revision->message
          && $current_file_revision->branch == $file_revision->branch) {
        // So, sure enough, we have a file from the same commit here.
        $operation_items[$path] = $current_item;
        unset($operation_items_by_date[$date][$path]); // Don't process this revision twice.
      }
    }
  }
  return $operation_items;
}

/**
 * Use the additional file revision information that has been stored in each
 * operation item array in order to assemble the associated commit operation.
 * That commit information is then stored as a list item in the given
 * $commit_operations array as an object with 'operation' and 'operation_items'
 * properties.
 */
function _versioncontrol_cvs_log_construct_commit_operation($repository, $operation_items, &$commit_infos_by_date) {
  $date = 0;

  // Get any of those commit properties, they should all be the same anyways
  // (apart from the date which may vary in large commits).
  foreach ($operation_items as $path => $item) {
    $file_revision = $item['cvs_specific']['file_revision'];
    unset($operation_items[$path]['cvs_specific']['file_revision']);

    if ($file_revision->date > $date) {
      $date = $file_revision->date;
    }
    $username = $file_revision->username;
    $message = $file_revision->message;
    $branch_name = $file_revision->branch;
  }

  // Yay, we have all operation items and all information. Ready to go!
  $operation = array(
    'type' => VERSIONCONTROL_OPERATION_COMMIT,
    'repo_id' => $repository['repo_id'],
    'date' => $date,
    'username' => $username,
    'message' => $message,
    'revision' => '',
    'labels' => array(
      array(
        'name' => $branch_name,
        'type' => VERSIONCONTROL_OPERATION_BRANCH,
        'action' => VERSIONCONTROL_ACTION_MODIFIED,
      ),
    ),
  );

  $commit_info = new stdClass();
  $commit_info->operation = $operation;
  $commit_info->operation_items = $operation_items;
  $commit_infos_by_date[$date][] = $commit_info;
}
