<?php
// $Id: svnlib-deluxe.inc,v 1.2 2009/01/02 19:29:54 jpetso Exp $
/**
 * @file
 * A few functions that add some more smartness to the basic Subversion
 * functionality from svnlib.inc, at the price of more command line calls.
 * The goal of these functions is to link together some of the information
 * that is provided by svnlib.inc so that it can be used more easily
 * by the Subversion backend (or any other PHP program), but still keeping
 * everything independent from Drupal so that we don't need to care about
 * porting the functions to newer Drupal versions.
 *
 * @note
 *    As mentioned, everything in here can be run *without* Drupal.
 *
 * Copyright 2008 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 */

include_once(dirname(__FILE__) .'/svnlib.inc');

/**
 * Add more complete and accurate commit actions to a given set of
 * log entries (as retrieved by svnlib_log()).
 *
 * @param $revisions
 *   The output of svnlib_log() which can be directly passed to this function.
 *
 * @return
 *   The input @p $logentries parameter with an additional element 'actions'
 *   in each log entry element. 'actions' is an array that consists of
 *   multiple structured arrays with the following keys:
 *
 *   - 'action': 'A' for added, 'M' for modified, 'D' for deleted,
 *        'C' for copied, or 'AD' for moved ("Added/Deleted").
 *   - 'current_item': The updated state of the modified item.
 *        Exists for all actions, even for 'D' (although Subversion doesn't
 *        have).
 *   - 'source_item': The previous state of the modified item.
 *        Exists for all actions except 'A'.
 *   - 'replaced_item': Only exists if the original action was 'R',
 *        and describes the item that has been deleted and replaced by
 *        another item (which was added, copied or moved to that same place).
 *
 * Both 'current_item' and 'source_item' contain the following elements:
 *
 *   - 'type': A string specifying the item type - either 'file' or 'dir'.
 *   - 'path': The path inside the repository, including a leading slash
 *        (e.g. '/trunk/file.txt').
 *   - 'rev': The item revision, as an integer (this is always at a revision
 *        when the file is actually showing up in the changelog).
 */
function svnlib_more_log_info($revisions, $repository_root) {
  if (!isset($revisions)) {
    return NULL;
  }

  foreach ($revisions as $rev_key => $revision) {
    $actions = array();
    $queried_urls_current = array();
    $queried_urls_modified = array();

    // First step: Create action arrays for each path in the revision.
    foreach ($revision['paths'] as $path => $path_info) {
      $action = array('action' => $path_info['action']);
      if ($action['action'] == 'R') {
        $action['action'] = $path_info['action'] = 'A';
        $action['replaced'] = TRUE; // will be deleted again later
      }
      if (isset($path_info['copyfrom'])) {
        $action['copyfrom'] = $path_info['copyfrom'];
      }
      $action['url'] = $repository_root . $path;
      $actions[$path] = $action;

      // By bundling many URL detail retrievals in one call of svn_info(), we
      // can keep the number of 'svn info' invocations down to a minimum.
      // Doing this one by one would be a major blow to performance.
      if (in_array($action['action'], array('M', 'A'))) {
        $queried_urls_current[$path] = $action['url'];
      }
      if ($action['action'] == 'M') {
        $queried_urls_modified[$path] = $action['url'];
      }
    }

    // Second step: Retrieve all current items.
    $info_items = svnlib_info_cached(
      array_values($queried_urls_current), $revision['rev']
    );
    foreach ($info_items as $info_item) {
      $path = $info_item['path'];
      $actions[$path]['current_item'] = _svnlib_create_item_from_info($info_item);

      if (isset($actions[$path]['copyfrom'])) { // can happen for 'A' or 'R' actions
        // Yay, we can have the source item without invoking the binary.
        $actions[$path]['source_item'] = $actions[$path]['current_item'];
        $actions[$path]['source_item']['path'] = $actions[$path]['copyfrom']['path'];
        $actions[$path]['source_item']['rev'] = $actions[$path]['copyfrom']['rev'];
        unset($actions[$path]['copyfrom']); // not needed anymore
      }
    }

    // Third step: Retrieve all source items of 'M' actions.
    // (Mind that those can even have different paths than the current item.)
    $info_items = svnlib_info_cached(
      array_values($queried_urls_modified), $revision['rev'], $revision['rev'] - 1
    );
    if (count($info_items) == count($queried_urls_modified)) {
      foreach ($queried_urls_modified as $path => $url) {
        $info_item = array_shift($info_items);
        $actions[$path]['source_item'] = _svnlib_create_item_from_info($info_item);
      }
    }
    else {
      if (defined('SVNLIB_DEBUG')) {
        fwrite(STDERR, "Revision $revision[rev]: Retrieved 'M' info items not complete! Falling back to one-by-one retrieval.\n");
      }
    }

    // Bonus feature: Recognize moves and copies by inspecting the source item
    // of added items and matching it to a deleted one. We can it this way
    // because if the icon really were an added one then it wouldn't have
    // a source item at all. The nice thing is that we can even get rid
    // of more 'svn info' invocations by removing the corresponding 'D' actions.
    foreach ($actions as $path => $action) {
      if ($action['action'] == 'A' && isset($action['source_item'])) {
        // Search through all other items if they contain the source item
        // of this add action.
        foreach ($revision['paths'] as $other_path => $other_path_info) {
          // Only consider most recent delete actions with a matching path.
          if ($other_action['action'] == 'D'
              && $action['source_item']['rev'] == ($action['current_item']['rev'] - 1)
              && $other_path == $action['source_item']['path']) {
            // Hah! gotcha. Die, delete action, die! Instead, the "deleted" item
            // is just going to be the source item of the now merged action.
            // ...that's right, what we've got here is a move.
            unset($revisions[$rev_key]['paths'][$other_path]);
            unset($actions[$other_path]);
            $actions[$action_key]['action'] = 'AD';
          }
          break;
        }
        // If the item was not moved, it must have been copied.
        // (Otherwise there would be no source item.)
        if ($actions[$path]['action'] != 'AD') {
          $actions[$path]['action'] = 'C';
        }
      }
    }

    // Fourth step: retrieve the source items of the given path,
    // and construct the $action info array for that path.
    foreach ($actions as $path => $action) {
      // In case there was a modified item of which the parent was moved
      // to a new location just in this very revision, it has been missing
      // from the retrieved modified items further above, which means the
      // remaining ones could not be mapped to their actions.
      // In order to map them anyways, we have to retrieve them one by one -
      // which is slow, but will hardly ever occur anyways.
      if ($actions[$path]['action'] == 'M' && !isset($actions[$path]['source_item'])) {
        $source_items = svnlib_info_cached($actions[$path]['url'], $revision['rev'], $revision['rev'] - 1);
        if ($source_items) {
          $source_item = reset($source_items); // first item
          $actions[$path]['source_item'] = _svnlib_create_item_from_info($source_item);
        }
      }
      // Ok, that was the easy part - 'M' and 'A' are covered and should now
      // have a current item (in all cases) and source item (if it existed).

      // If the original type was 'R', we essentially have two actions:
      // one delete action and one add/move/copy action. We now have the data
      // for the latter one, but we can also get the data for the delete action
      // by making the algorithm believe that the action is 'D', not 'A'.
      // When we have retrieved the replaced item, we can revert that again.
      if ($action['replaced']) {
        $original_action = $actions[$path]; // backup, will be restored later on
        $actions[$path]['action'] = 'D';
      }

      // The hard part is 'D'. There are two possible cases...
      if ($actions[$path]['action'] == 'D') {
        // a) The item existed in that place before it was deleted. Cool.
        $source_items = svnlib_info_cached($actions[$path]['url'], $revision['rev'] - 1);
        if ($source_items) {
          $source_item = reset($source_items); // first item
          $actions[$path]['source_item'] = _svnlib_create_item_from_info($source_item);
          $actions[$path]['current_item'] = $actions[$path]['source_item'];
          $actions[$path]['current_item']['rev'] = $revision['rev']; // path and type are ok
        }
        else {
          // b) The item was copied to the new place and deleted immediately.
          //    Less cool, and a lot more work to get right.
          $parent_path_info = NULL;

          // Try to find the parent directory that contained the original item.
          foreach ($revision['paths'] as $other_path => $other_path_info) {
            if (isset($other_path_info['copyfrom']) // was copied or moved
                && strpos($path, $other_path_info['path'] .'/') !== FALSE) {
              $parent_path_info = $other_path_info;
              break; // bingo, that's where the deleted item came from.
            }
          }
          if (isset($parent_path_info)) {
            // Now we only need to get the previous item from underneath
            // the original parent path.
            $path_remainder = substr($path, strlen($parent_path_info['path']));
            $original_url = $repository_root . $parent_path_info['copyfrom']['path'] . $path_remainder;
            $source_items = svnlib_info($original_url, $revision['rev'] - 1);
            if ($source_items) {
              $source_item = reset($source_items); // first item
              $actions[$path]['source_item'] = _svnlib_create_item_from_info($source_item);
              $actions[$path]['current_item'] = $actions[$path]['source_item'];
              $actions[$path]['current_item']['path'] = $path;
              $actions[$path]['current_item']['rev'] = $revision['rev'];
              // type is ok, as it's the same as in the source item.
            }
          }
        }
        if (!$actions[$path]['replaced']) {
          // We don't absolutely need this source item for replace actions,
          // so let's only require it if it's a real delete action.
          if (!isset($actions[$path]['source_item'])) {
            // Should not happen by now... if if does, we have a problem.
            // In order to keep consistency in that case, let's just remove
            // the path and action and pretend nothing ever happened.
            unset($revisions[$rev_key]['paths'][$path]);
            unset($actions[$path]);
            continue;
          }
        }
      }

      if ($actions[$path]['replaced']) {
        // Extract the replaced item from the action that was written
        // by the ($path['action'] == 'D') branch, and then put it back
        // in its original place.
        if ($actions[$path]['source_item']) {
          $original_action['replaced_item'] = $actions[$path]['source_item'];
        }
        $actions[$path] = $original_action;

        // If the copy/move action heuristics and the delete action heuristics
        // retrieve the same item, then we've got the rare special case that
        // the parent directory has been copied to a new location where the
        // old copied item was swapped against a new one. (Yeah, happens.)
        // Same item as 'source_item' and 'replaced_item' is bad, so guard
        // against this.
        if ($actions[$path]['source_item']['path'] == $actions[$path]['replaced_item']['path']
            && $actions[$path]['source_item']['rev'] == $actions[$path]['replaced_item']['rev']) {
          $actions[$path]['action'] = 'A';
          unset($actions[$path]['source_item']);
        }

        // Cleaning up properties that should not be included in the result.
        unset($actions[$path]['replaced']);
      }
      unset($actions[$path]['url']);
    }

    // Store all the actions for this revision in the result array.
    $revisions[$rev_key]['actions'] = $actions;

    if (defined('SVNLIB_DEBUG')) {
      fwrite(STDOUT, '.');
    }
  }
  _svnlib_item_cache(FALSE); // clear the cache
  return $revisions;
}

/**
 * When running naively, svnlib_more_log_info() is quite slow.
 * Like, really slow. Let's try to make it faster by not calling svn_info()
 * more than once for each item if we know that the result is the same.
 * In order to be able to implement this optimization, we assume that
 * a) svn_info() is not called in any of the recursive modes, and
 * b) revisions are given as fixed revision numbers, i.e. not 'HEAD'.
 */
function svnlib_info_cached($repository_urls, $url_revision, $target_revision = NULL) {
  $cache = _svnlib_item_cache();
  $queried_urls = array();
  $result_items = array();

  if (!is_array($repository_urls)) {
    $repository_urls = array($repository_urls); // it's a lonely string
  }

  // Use the cache and skip svn_info() if we've already got the item info.
  foreach ($repository_urls as $url) {
    $item = NULL;
    //fwrite(STDOUT, "-----\n");

    if (!isset($target_revision) && isset($cache[$url][$created_rev]['no_target'])) {
      $item = $cache[$url][$created_rev]['no_target'];
    }
    if (isset($target_revision) && isset($cache[$url][$created_rev][$target_revision])) {
      $item = $cache[$url][$created_rev][$target_revision];
    }
    if (isset($item)) {
      $result_items[$item['path']] = $item;
      //fwrite(STDOUT, "Used cached item: $item[url]@$created_rev\n");
    }
    else {
      $queried_urls[] = $url;
      //fwrite(STDOUT, "Processing item: $url@$url_revision".
      //               (isset($target_revision) ? " -r$target_revision\n" : "\n"));
    }
  }
  if (empty($queried_urls)) {
    return $result_items; // nothing left to be done, yay
  }

  $items = svnlib_info($queried_urls, $url_revision, 'empty', $target_revision);

  if (!isset($items)) {
    return array();
  }
  foreach ($items as $item) {
    _svnlib_item_cache($item, $target_revision);
    $result_items[$item['path']] = $item;
    //fwrite(STDOUT, "Stored item: $item[url]@$url_revision".
    //               (isset($target_revision) ? " -r$target_revision\n" : "\n"));
  }
  return $result_items;
}

function _svnlib_item_cache($item = NULL) {
  static $cache = NULL;
  if ($item === FALSE) {
    $cache = NULL; // clear the cache, free the memory
  }
  if (!isset($cache)) { // initialization, if necessary
    $cache = array();
  }
  if (isset($item)) {
    $cache[$item['url']][$item['created_rev']]['no_target'] = $item;
    $cache[$item['url']][$item['created_rev']][$item['rev']] = $item;
  }
  return $cache;
}

function _svnlib_create_item_from_info($info_item) {
  return array(
    'type' => $info_item['type'],
    'path' => $info_item['path'],
    'rev' => $info_item['created_rev'],
  );
}


