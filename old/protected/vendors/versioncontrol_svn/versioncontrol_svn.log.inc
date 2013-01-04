<?php
// $Id: versioncontrol_svn.log.inc,v 1.4 2009/01/02 19:29:54 jpetso Exp $
/**
 * @file
 * Subversion backend for Version Control API - Provides Subversion commit
 * information and account management as a pluggable backend.
 *
 * This file provides functionality to parse the output of 'svn log' (with
 * support from 'svn info') and transform it into Version Control API commits.
 *
 * Copyright 2007, 2008 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 */
include_once(drupal_get_path('module', 'versioncontrol_svn') .'/svnlib/svnlib-deluxe.inc');

/**
 * Actually update the repository by fetching commits and other stuff
 * directly from the repository, invoking the svn executable.
 *
 * @return
 *   TRUE if the logs were updated, NULL if no new entries were fetched
 *   (without errors, though), or FALSE if fetching and updating the logs
 *   failed because of an error.
 */
function _versioncontrol_svn_log_update_repository(&$repository) {
  static $svnlib_mapping = NULL;
  if (!isset($svnlib_mapping)) {
    $svnlib_mapping = array(
      'actions' => array(
        'A' => VERSIONCONTROL_ACTION_ADDED,
        'M' => VERSIONCONTROL_ACTION_MODIFIED,
        'AD' => VERSIONCONTROL_ACTION_MOVED,
        'C' => VERSIONCONTROL_ACTION_COPIED,
        'D' => VERSIONCONTROL_ACTION_DELETED,
      ),
      'types' => array(
        'file' => VERSIONCONTROL_ITEM_FILE,
        'dir' => VERSIONCONTROL_ITEM_DIRECTORY,
      ),
    );
  }

  _versioncontrol_svn_init_svnlib($repository);
  $last_revision = $repository['svn_specific']['last_revision'];

  $info_items = svnlib_info($repository['root']);
  if (!$info_items) {
    return t('The repository at %url could not be accessed. Error message from \'svn info\': !error', array(
      '%url' => $repository['root'],
      '!error' => '<pre>'. check_plain(svnlib_last_error_message()) .'</pre>',
    ));
  }
  // By retrieving the repository root, we can even allow
  // non-root repository URLs to be specified in the admin UI.
  $url_item = reset($info_items); // first array element
  $real_root = $url_item['repository_root'];
  $youngest_repository_revision = $url_item['rev'];

  // Don't try to update if there's nothing to update.
  if ($last_revision == $youngest_repository_revision) {
    // Anyways, we want to remember the time when we tried that.
    $repository['svn_specific']['updated'] = time();
    db_query('UPDATE {versioncontrol_svn_repositories}
              SET updated = %d WHERE repo_id = %d',
              $repository['svn_specific']['updated'], $repository['repo_id']);

    return t('No new log entries to fetch.');
  }

  $revisions = svnlib_log(
    $repository['root'], ($last_revision + 1) .':HEAD'
  );
  $revisions = svnlib_more_log_info($revisions, $real_root);
  if (!isset($revisions)) {
    return t('Error when fetching new log entries.');
  }

  foreach ($revisions as $rev_id => $revision) { // processing the oldest revision first
    $operation = array(
      'type' => VERSIONCONTROL_OPERATION_COMMIT,
      'repository' => $repository,
      'date' => $revision['time_t'],
      'username' => $revision['author'],
      'message' => $revision['msg'],
      'revision' => (string) $rev_id,
      'labels' => array(), // no branch/tag emulation support yet
    );
    if (empty($operation['username'])) { // commit inserted by cvs2svn, for example
      $operation['username'] = '(no author)';
      $operation['uid'] = 0;
    }
    $operation_items = array();

    foreach ($revision['actions'] as $path => $rev_action) {
      $item = array(
        'type' => $svnlib_mapping['types'][$rev_action['current_item']['type']],
        'path' => $rev_action['current_item']['path'],
        'revision' => (string) $rev_action['current_item']['rev'],
        'action' => $svnlib_mapping['actions'][$rev_action['action']],
        'source_items' => array(),
      );
      if (isset($rev_action['source_item'])) {
        $item['source_items'][] = array(
          'type' => $svnlib_mapping['types'][$rev_action['source_item']['type']],
          'path' => $rev_action['source_item']['path'],
          'revision' => (string) $rev_action['source_item']['rev'],
        );
      }
      if (isset($rev_action['replaced_item'])) {
        $item['replaced_item'] = array(
          'type' => $svnlib_mapping['types'][$rev_action['replaced_item']['type']],
          'path' => $rev_action['replaced_item']['path'],
          'revision' => (string) $rev_action['replaced_item']['rev'],
        );
      }
      $operation_items[$item['path']] = $item;
    }

    // Now that was easy, wasn't it? :P
    $operation = versioncontrol_insert_operation($operation, $operation_items);
    if (isset($operation) && $rev_id > $last_revision) {
      $last_revision = $rev_id;
    }
  }

  if ($last_revision > $repository['svn_specific']['last_revision']) {
    $repository['svn_specific']['last_revision'] = $last_revision;
    $repository['svn_specific']['updated'] = time();

    // Everything's done, remember the last revision that was captured.
    db_query('UPDATE {versioncontrol_svn_repositories}
              SET last_revision = %d, updated = %d WHERE repo_id = %d',
              $repository['svn_specific']['last_revision'],
              $repository['svn_specific']['updated'], $repository['repo_id']);
  }
  return t('Fetched !count new log entries.', array('!count' => count($revisions)));
}
