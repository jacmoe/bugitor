<?php
// $Id: versioncontrol_git.log.inc,v 1.40.2.17 2010/12/09 00:27:23 marvil07 Exp $
/**
 * @file
 * Git backend for Version Control API - Provides Git commit information and
 * account management as a pluggable backend.
 * FIXME add an action to operation_labels
 *
 * Copyright 2008 by Jimmy Berry ("boombatower", http://drupal.org/user/214218)
 * Copyright 2009-2010 by Cornelius Riemenschneider ("CorniI", http://drupal.org/user/136353)
 */

/**
 * Actually update the repository by fetching commits and other stuff
 * directly from the repository, invoking the git executable.
 * @param VersioncontrolGitRepository $repository
 * @return
 *   TRUE if the logs were updated, or FALSE if fetching and updating the logs
 *   failed for whatever reason.
 *
 * FIXME while largely ported to the new entities system, this is still not 100%
 * done
 */
function _versioncontrol_git_log_update_repository(VersioncontrolGitRepository &$repository) {
  if (!$repository->isValidGitRepo()) {
    drupal_set_message(t('The repository %name at <code>@root</code> is not a valid Git bare repository.', array('%name' => $repository->name, '@root' => $repository->root)), 'error');
    return FALSE;
  }

  $root = escapeshellcmd($repository->root);
  putenv("GIT_DIR=$root");
  if ($repository->locked == TRUE) {
    drupal_set_message(t('This repository is locked, there is already a fetch in progress. If this is not the case, press the clear lock button.'), 'error');
    return FALSE;
  }
  $repository->locked = 1;
  $repository->update();

  // 1. Process branches

  // Fetch branches from the repo and load them from the db.
  $branches_in_repo = $repository->fetchBranches();
  $branches_in_db = $repository->loadBranches();
  $branches_in_db_by_name = array();
  foreach ($branches_in_db as $branch) { // Branches get keyed on id; key on name instead.
    $branches_in_db_by_name[$branch->name] = $branch;
  }
  unset($branches_in_db);

  // Determine whether we've got branch changes to make.
  $branches_new = array_diff_key($branches_in_repo, $branches_in_db_by_name);
  $branches_deleted = array_diff_key($branches_in_db_by_name, $branches_in_repo);

  // Insert new branches in the repository. Later all commits in these new
  // branches will be updated.
  // Unfortunately we can't say anything about the branch author at this time.
  // The post-update hook could do this, though.
  // We also can't insert a VCOperation which adds the branch, because
  // we don't know anything about the branch. This is all stuff a hook could
  // figure out.
  foreach($branches_new as $branch) {
    $branch->insert();
  }
  // reload branches, after they was inserted
  $branches_in_db = $repository->loadBranches();
  $branches_in_db_by_name = array();
  foreach ($branches_in_db as $branch) {
    $branches_in_db_by_name[$branch->name] = $branch;
  }

  // Deleted branches are removed, commits in them are not!
  foreach($branches_deleted as $branch) {
    $branch->delete();
  }

  // 2. Process commits

  // Fetch commits from the repo and load them from the db.
  $commits_in_repo_hashes = $repository->fetchCommits();
  $commits_in_db = $repository->loadCommits();
  $commits_in_db_hashes = array();
  foreach ($commits_in_db as $commit) {
    $commits_in_db_hashes[] = $commit->revision;
  }
  unset($commits_in_db);
  $commits_new = array_diff($commits_in_repo_hashes, $commits_in_db_hashes);

  // Insert new commits in the database.
  foreach ($commits_new as $hash) {
    $command = "git show --numstat --summary --pretty=format:\"%H%n%P%n%an%n%ae%n%cn%n%ce%n%ct%n%s%n%b%nENDOFOUTPUTGITMESSAGEHERE\" " . escapeshellarg($hash);
    $output = _versioncontrol_git_log_exec($command);
    _versioncontrol_git_log_parse_and_insert_commit($repository, $output, $branches_in_db_by_name);
  }

  // Add a new branch to all commits contained in that branch.
  foreach ($branches_new as $branch_new) {
    $commit_ids = _versioncontrol_git_log_get_commits_in_branch($branch_new);
    $commits = $repository->loadCommits(array(), array('revision' => $commit_ids));
    _versioncontrol_git_log_attach_branch_to_commits($branches_in_db_by_name[$branch_new->name], $commits);
  }

  // 3. Process tags

  // Insert new tags in the database.
  $tags_in_repo = $repository->fetchTags();
  $tags_in_db = $repository->loadTags();
  $tags_in_db_by_name = array();
  foreach ($tags_in_db as $tag) {
    $tags_in_db_by_name[$tag->name] = $tag;
  }
  unset($tags_in_db);
  // Deleting tags is *not* supported. Read the manual if you want to know why...
  // Check for new tags.
  $tags_new = array_diff_key($tags_in_repo, $tags_in_db_by_name);
  if (!empty($tags_new)) {
    _versioncontrol_git_log_process_tags($repository, $tags_new);
  }

  // Update repository updated field. Displayed on administration interface for documentation purposes.
  $repository->updated = time();
  $repository->locked = 0;
  $repository->update();
  return TRUE;
}

/// All general functions

/**
 * Execute a Git command using the root context and the command to be executed.
 * @param string $command Command to execute.
 * @return mixed Logged output from the command in either array of file pointer form.
 */
function _versioncontrol_git_log_exec($command) {
  $logs = array();
  exec($command, $logs);
  array_unshift($logs, ''); // FIXME doing it this way is just wrong.
  reset($logs); // Reset the array pointer, so that we can use next().
  return $logs;
}

/// All commit related function

/**
 * Returns an array of all branches a given commit is in.
 * @param string $revision
 * @param array $branch_label_list
 * @return VersioncontrolBranch
 */
function _versioncontrol_git_log_get_branches_of_commit($revision, $branch_label_list) {
  $exec = 'git branch --contains ' . escapeshellarg($revision);
  $logs = _versioncontrol_git_log_exec($exec);
  $branches = array();
  while (($line = next($logs)) !== FALSE) {
    $line = trim($line);
    if($line[0] == '*') {
      $line = substr($line, 2);
    }
    $branches[] = $branch_label_list[$line];
  }
  return $branches;
}

/**
 * This function returns all commits in the repository
 * @param $repository
 * @return array An array of strings with all commit id's in it
 */
function _versioncontrol_git_log_get_commits_in_repo($repository) {
  $logs = _versioncontrol_git_log_exec("git rev-list --all");
  $commits = array();
  while (($line = next($logs)) !== FALSE) {
    $commits[] = trim($line);
  }
  return $commits;
}

/**
 * This function returns all commits in the given branch.
 * @param VersioncontrolBranch $branch
 * @return array An array of strings with all commit id's in it
 */
function _versioncontrol_git_log_get_commits_in_branch($branch) {
  $logs = _versioncontrol_git_log_exec("git rev-list " . escapeshellarg($branch->name) . " --");
  $commits = array();
  while (($line = next($logs)) !== FALSE) {
    $commits[] = trim($line);
  }
  return $commits;
}

/**
 * A function to fill in the source_item for a specific VersioncontrolItem.
 *
 * Now VCS API assumes there is only one source item, so merges can not be
 * tracked propertly there, and we are neither tracking on git backend for
 * now.
 * For merges we are choosing the first parent git-log  show.
 *
 * @param VersioncontrolItem &$item
 * @param array $parents The parent commit(s)
 * @return none
 */
function _versioncontrol_git_fill_source_item(&$item, $parents, $inc_data) {
  $data = array(
    'type' => VERSIONCONTROL_ITEM_FILE,
    'repository' => $inc_data['repository'],
    'path' => $item->path,
  );

  $path_stripped = substr($item->path, 1);
  // using -5 to let detect merges until 4 parents, merging more than 4 parents in one operation is insane!
  // use also --first-parent to retrieve only one parent for the current support of VCS API
  $cmd = 'git log --first-parent --follow --pretty=format:"%H" -5 ' . escapeshellarg($item->revision) . ' -- ' . escapeshellarg($path_stripped);
  $prev_revisions = _versioncontrol_git_log_exec($cmd);

  next($prev_revisions); // grab our hash out
  if (($parent_hash = next($prev_revisions)) !== FALSE) { // get the first parent hash
    $data['revision'] = trim($parent_hash);
    // just fill an object from scratch
    $source_item = new VersioncontrolGitItem($item->getBackend());
    $source_item->build($data);
    $item->setSourceItem($source_item);
  }
  //TODO unify the way to fail
}

/**
 * Takes parts of the output of git log and returns all affected OperationItems for a commit.
 * @param array $logs
 * @param string $line
 * @param string $revision
 * @param array $parents The parent commit(s)
 * @param bool $merge
 * @return array All items affected by a commit.
 */
function _versioncontrol_git_parse_items(&$logs, &$line, $data, $parents) {
  $op_items = array();

  // Parse the diffstat for the changed files.
  do {
    if (!preg_match('/^(\S+)' . "\t" . '(\S+)' . "\t" . '(.+)$/', $line, $matches)) {
      break;
    }
    $path = '/'. $matches[3];
    $op_items[$path] = new VersioncontrolGitItem($data['backend']);
    $data['path'] = $path;
    $op_items[$path]->build($data);
    unset($data['path']);

    if (is_numeric($matches[1]) && is_numeric($matches[2])) {
      $op_items[$path]->line_changes_added = $matches[1];
      $op_items[$path]->line_changes_removed = $matches[2];
    }
  } while (($line = next($logs)) !== FALSE);

  // Parse file actions.
  do {
    if (!preg_match('/^ (\S+) (\S+) (\S+) (.+)$/', $line, $matches)) {
      break;
    }
    // We also can get 'mode' here if someone changes the file permissions.
    if ($matches[1] == 'create') {
      $op_items['/'. $matches[4]]->action = VERSIONCONTROL_ACTION_ADDED;
    }
    else if ($matches[1] == 'delete') {
      $op_items['/'. $matches[4]]->action = VERSIONCONTROL_ACTION_DELETED;
      $op_items['/'. $matches[4]]->type = VERSIONCONTROL_ITEM_FILE_DELETED;
    }
  } while (($line = next($logs)) !== FALSE);

  // Fill in the source_items for non-added items
  foreach ($op_items as $path => &$item) {
    if ($item->action != VERSIONCONTROL_ACTION_ADDED) {
      _versioncontrol_git_fill_source_item($item, $parents, $data);
    }
  }
  return $op_items;
}

/**
 * Parse the output of 'git log' and insert a commit based on its data.
 *
 * @param VersioncontrolRepository $repository
 * @param array $logs The output of 'git log' to parse
 * @param array $branch_label_list An associative list of branchname => VersioncontrolBranch
 */
function _versioncontrol_git_log_parse_and_insert_commit(VersioncontrolRepository $repository, $logs, $branch_label_list) {
  // Get commit hash (vcapi's "revision")
  $revision = trim(next($logs));

  // Get parent commit hash(es)
  $parents = explode(' ', trim(next($logs)));
  if ($parents[0] == '') {
    $parents = array();
  }
  $merge = isset($parents[1]); // Multiple parents indicates a merge

  // Get author data
  $author_name = trim(next($logs));
  $author_email = trim(next($logs));
  // Get committer data
  $committer_name = trim(next($logs));
  $committer_email = trim(next($logs));

  // Get date as timestamp
  $date = trim(next($logs));

  // Get revision message.
  // TODO: revisit!
  $message = '';
  $i = 0;
  while (($line = trim(next($logs))) !== FALSE) {
    if ($line == 'ENDOFOUTPUTGITMESSAGEHERE') {
      if (substr($message, -2) === "\n\n") {
        $message = substr($message, 0, strlen($message) - 1);
      }
      break;
    }
    if ($i == 1) {
      $message .= "\n";
    }
    $message .= $line ."\n";
    $i++;
  }

  // This is either a (kind of) diffstat for each modified file or a list of
  // file actions like moved, created, deleted, mode changed.
  $line = next($logs);

  // build the data array to be used for the commit op
  $op_data = array(
    'type' => VERSIONCONTROL_OPERATION_COMMIT,
    'revision' => $revision,
    'author' => $author_email,
    'author_name' => $author_name,
    'committer' => $committer_email,
    'committer_name' => $committer_name,
    'parent_commit' => reset($parents),
    'merge' => $merge,
    'date' => $date,
    'message' => $message,
    'repository' => $repository,
  );

  $op = new VersioncontrolGitOperation($repository->getBackend());
  $op->build($op_data);
  $op->labels = _versioncontrol_git_log_get_branches_of_commit($revision, $branch_label_list);
  $op->insert(array('map users' => TRUE));

  $item_action = $merge ? VERSIONCONTROL_ACTION_MERGED : VERSIONCONTROL_ACTION_MODIFIED;
  // build the data array to be used as default values for the item revision
  $default_item_data = array(
    // pass backend in data array to avoid just another param to parse
    // item function
    'backend' => $repository->getBackend(),
    'repository' => $repository,
    'vc_op_id' => $op->vc_op_id,
    'type' => VERSIONCONTROL_ITEM_FILE,
    'revision' => $revision,
    'action' => $item_action,
  );

  // Parse in the raw data and create VersioncontrolGitItem objects.
  $op_items = _versioncontrol_git_parse_items($logs, $line, $default_item_data, $parents);
  $op->itemRevisions = $op_items;
  $op->save(array('nested' => TRUE));
}

/// All branch related functions

/**
 * Attaches the label $branch to every commit if it is not there yet.
 * @param VersioncontrolBranch $branch
 * @param array $commits
 * @return none
 */
function _versioncontrol_git_log_attach_branch_to_commits($branch, $commits) {
  foreach ($commits as $op) {
    // We need this complicated logic to avoid adding a branch twice to a commit.
    $already_there = FALSE;
    foreach($op->labels as $label) {
      if (is_subclass_of($label, 'VersioncontrolBranch') && $label->name == $branch->name) {
        $already_there = TRUE;
        break;
      }
    }
    if (!$already_there) {
      $op->labels[] = $branch;
      $op->updateLabels($op->labels);
    }
  }
}


/// Tag related functions

/**
 * Returns a list of all existing tags in the database.
 * @param VersioncontrolRepository $repository
 * @return array An array of tag names.
 */
function _versioncontrol_git_log_get_tags_in_db($repository) {
  return _versioncontrol_git_log_get_labels_in_db($repository, VERSIONCONTROL_LABEL_TAG);
}

/**
 * Get all tags present in the repository.
 * @return array
 */
function _versioncontrol_git_log_get_tags_in_repo() {
  $log = _versioncontrol_git_log_exec('git show-ref --tags'); // Query tags.
  $tags = array();
  while (($line = next($log)) !== FALSE) {
  	// the output of git show-ref --tags looks like this
  	// 94a5915923d5a9a6af935e4055c95582fd1a1136 refs/tags/DRUPAL-5--1-0
  	// to get 'DRUPAL-5--1-0', we have to skip 51 chars.
    $tags[] = substr(trim($line), 51);
  }
  return $tags;
}

/**
 * Returns a string with fully qualified tag names from an array of tag names.
 * @param array $tags
 * @return string
 */
function _versioncontrol_git_get_tag_string($tags) {
  $tag_string = '';
  // $tag_string is a list of fully qualified tag names
  foreach ($tags as $tag) {
    $tag_string .= escapeshellarg("refs/tags/{$tag->name}") . ' ';
  }
  return $tag_string;
}

/**
 * Returns a list of tag names with the tagged commits.
 * Handles annotated tags.
 * @param array $tags An array of tag names
 * @return array A list of all tags with the respective tagged commit.
 */
function _versioncontrol_git_log_get_tag_commit_list($tags) {
  if(empty($tags)) {
    return array();
  }
  $tag_string = _versioncontrol_git_get_tag_string($tags);
  $exec = "git show-ref -d $tag_string";
  $tag_commit_list_raw = _versioncontrol_git_log_exec($exec);
  $tag_commit_list = array();
  $tags_annotated = array();
  foreach($tag_commit_list_raw as $tag_commit_line) {
  	if($tag_commit_line == '') {
  	  continue;
  	}
  	$tag_commit = substr($tag_commit_line, 0, 40);
  	// annotated tag mark
  	// 9c70f55549d3f4e70aaaf30c0697f704d02e9249 refs/tags/tag^{}
  	if (substr($tag_commit_line, -3, 3) == '^{}') {
  	  $tag_name = substr($tag_commit_line, 51, -3);
      $tags_annotated[$tag_name] = $tag_commit;
  	}
  	// Simple tags
  	// 9c70f55549d3f4e70aaaf30c0697f704d02e9249 refs/tags/tag
  	else {
      $tag_name = substr($tag_commit_line, 51);
  	}
    $tag_commit_list[$tag_name] = $tag_commit;
  }
  // Because annotated tags show up twice in the output of git show-ref, once
  // with a 'tag' object and once with a commit-id we will go through them and
  // adjust the array so we just keep the commits.
  foreach($tags_annotated as $tag_name => $tag_commit) {
  	$tag_commit_list[$tag_name] = $tag_commit;
  }
  return $tag_commit_list;
}

/**
 * Does all processing to insert the tags in $tags_new in the database.
 * @param VersioncontrolGitRepository $repository
 * @param array $tags_new All new tags.
 * @return none
 */
function _versioncontrol_git_log_process_tags($repository, $tags_new) {
  if (empty($tags_new)) {
    return;
  }

  // get a list of all tag names with the corresponding commit.
  $tag_commit_list = _versioncontrol_git_log_get_tag_commit_list($tags_new);
  $format = '%(objecttype)%0a%(objectname)%0a%(refname)%0a%(taggername) %(taggeremail)%0a%(taggerdate)%0a%(contents)ENDOFGITTAGOUTPUTMESAGEHERE';
  foreach($tag_commit_list as $tag_name => $tag_commit) {
    $exec = "git for-each-ref --format=\"$format\" refs/tags/" . escapeshellarg($tag_name);
  	$logs_tag_msg = _versioncontrol_git_log_exec($exec);

    $tag_ops = $repository->loadCommits(array(), array('revision' => $tag_commit));
    $tagged_commit_op = reset($tag_ops);
  	// Get the specific tag data for annotated vs not annotated tags.
    if ($logs_tag_msg[1] == 'commit') {
      // simple tag
      // [2] is tagged commit [3] tagname [4] and [5] empty [6] commit log message
      // We get the tagger, the tag_date and the tag_message from the tagged commit.
      $tagger = $tagged_commit_op->author;
      $tag_date = $tagged_commit_op->date + 1;
      $message = $tagged_commit_op->message;
    }
    else if($logs_tag_msg[1] == 'tag') {
      // annotated tag
      // [2] is the tagged commit [3] tag name
      $tagger = $logs_tag_msg[4];
      $tag_date = strtotime($logs_tag_msg[5]);
      // Get the tag message
      $message = '';
      $i = 0;
      while (true) {
      	$line = $logs_tag_msg[$i + 6];
      	if($logs_tag_msg[$i + 7] == 'ENDOFGITTAGOUTPUTMESAGEHERE') {
      		$message .= $line;
      		break;
      	}
      	$message .= $line ."\n";
        $i++;
      }
    }
    else {
      watchdog('versioncontrol_git', 'Serious problem in tag parsing, please check that you\'re using a supported version of git!');
    }

    $tag_data = array(
      'name' => $tag_name,
      'repository' => $repository,
      'repo_id' => $repository->repo_id,
    );

    $tag = $repository->getBackend()->buildEntity('tag', $tag_data + array('action' => VERSIONCONTROL_ACTION_ADDED));
    $tag->insert();
  }
}
