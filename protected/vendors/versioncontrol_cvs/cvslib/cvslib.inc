<?php
// $Id: cvslib.inc,v 1.7 2009/04/08 23:59:05 jpetso Exp $
/**
 * @file
 * A few generic functions for interfacing with CVS via command line.
 *
 * @note
 *    These functions can be run *without* Drupal.
 *
 * Copyright 2005 by Kjartan Mannes ("Kjartan", http://drupal.org/user/2)
 * Copyright 2006, 2007 by Derek Wright ("dww", http://drupal.org/user/46549)
 * Copyright 2007, 2008, 2009 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 */

define('CVSLIB_STDOUT_NO_FILE', 1);
define('CVSLIB_STDOUT_USE_FILE_IF_POSSIBLE', 2);

// file_directory_temp() and t() are normally provided by Drupal, but as
// this file is supposed to be independent from Drupal code, here are
// fallback definitions for those functions.
if (!function_exists('file_directory_temp')) {
  function file_directory_temp() {
    return sys_get_temp_dir();
  }
}
if (!function_exists('t')) {
  function t($message, $replacements) {
    return function_exists('cvslib_local_translate')
      ? cvslib_local_translate($message, $replacements)
      : strtr($message, $replacements);
  }
}


/**
 * Perform a repository login if necessary, which is the case for pserver
 * access methods. Needs to be called once before the 'cvs' executable
 * is invoked (which is done automatically by the functions in this file).
 *
 * @param $repository_root
 *   The root directory of the CVS repository that you want to access.
 *   This function performs the necessary shell escapes, no need to do that
 *   beforehand.
 *
 * @return
 *   TRUE logging in was successful, or FALSE otherwise. If logging in did not
 *   succeed, any subsequent 'cvs' invocations will fail, so make sure you
 *   check the result value.
 */
function _cvslib_repository_login($repository_root) {
  if (strpos(':pserver:', $repository_root) !== FALSE) {
    $root = escapeshellcmd($repository_root);
    exec("cvs -d $root login", $output_lines, $return_status);
    if ($return_status != 0) {
      _cvslib_error_message(t('Failed to log in to CVS repository, could not fetch new repository logs.'));
      return FALSE;
    }
  }
  return TRUE;
}

/**
 * Append an appropriate output pipe to a $cmd array, which causes STDERR
 * to be written to a random file.
 *
 * @return
 *   An array with the temporary files that will be created when $cmd
 *   is executed. In its current form, the return array only contains
 *   the filename for STDERR output as 'stderr' array element.
 */
function _cvslib_add_output_pipes(&$cmd, $stdout_mode = CVSLIB_STDOUT_USE_FILE_IF_POSSIBLE) {
  $tempdir = file_directory_temp();
  $tempfiles = array();
  $random_number = mt_rand();

  if ($stdout_mode == CVSLIB_STDOUT_NO_FILE) {
    $use_stdout_file = FALSE;
  }
  else { // if ($stdout_mode == CVSLIB_STDOUT_USE_FILE_IF_POSSIBLE) {
    if (function_exists('cvslib_local_use_file') && !cvslib_local_use_file()) {
      $use_stdout_file = FALSE;
    }
    else {
      $use_stdout_file = TRUE;
    }
  }

  // Always use a temporary file unless the site specifically wants us not to.
  if ($use_stdout_file) {
    $tempfiles['stdout'] = $tempdir .'/drupal_versioncontrol_cvs.stdout.'. $random_number .'.txt';
    $cmd[] = '> '. $tempfiles['stdout'];
  }
  $tempfiles['stderr'] = $tempdir .'/drupal_versioncontrol_cvs.stderr.'. $random_number .'.txt';
  $cmd[] = '2> '. $tempfiles['stderr'];
  return $tempfiles;
}

/**
 * Delete temporary files that have been created by a command which included
 * output pipes from _cvslib_add_output_pipes().
 */
function _cvslib_delete_temporary_files($tempfiles) {
  if (isset($tempfiles['stdout'])) {
    @unlink($tempfiles['stdout']);
  }
  @unlink($tempfiles['stderr']);
}

/**
 * Read the STDERR output for a command that was executed.
 * The output must have been written to a temporary file which was given
 * by _cvslib_add_output_pipes(). The temporary file is deleted after it
 * has been read. After calling the function, the error message can be
 * retrieved by calling cvslib_last_error_message() or discarded by calling
 * cvslib_unset_error_message().
 */
function _cvslib_set_error_message($tempfiles) {
  _cvslib_error_message(file_get_contents($tempfiles['stderr']));
}

/**
 * Retrieve the STDERR output from the last invocation of 'cvs' that exited
 * with a non-zero status code. After fetching the error message, it will be
 * unset again until a subsequent 'cvs' invocation fails as well. If no message
 * is set, this function returns NULL.
 *
 * For better security, it is advisable to run the returned error message
 * through check_plain() or similar string checker functions.
 */
function cvslib_last_error_message() {
  $message = _cvslib_error_message();
  _cvslib_error_message(FALSE);
  return $message;
}

/**
 * Write or retrieve an error message, stored in a static variable.
 *
 * @param $info
 *   NULL to retrieve the message, FALSE to unset it, or a string containing
 *   the new message to remember it for later retrieval.
 */
function _cvslib_error_message($message = NULL) {
  static $error_message = NULL;

  if (!isset($message)) {
    return $error_message;
  }
  else {
    $error_message = ($message === FALSE) ? NULL : $message;
    return $error_message;
  }
}

/**
 * Execute a command (given as array of command line parts), check the
 * return code (setting an error message if it's non-zero), and prepare
 * the output text for further processing.
 *
 * @return
 *   Boolean FALSE (to be checked with "===" or "!==") if the return code was
 *   non-zero. Otherwise, either an array of output lines or a read-opened
 *   file handle for the output - which one you get depends on whether or not
 *   an output file was specified for the stdout pipe.
 */
function _cvslib_exec($cmd, $tempfiles) {
  $cmd = implode(' ', $cmd);

  $return_code = 0;
  exec($cmd, $output, $return_code);
  //watchdog('special', $cmd);

  if ($return_code != 0) {
    _cvslib_set_error_message($tempfiles);
    _cvslib_delete_temporary_files($tempfiles);
    return FALSE;
  }

  if (isset($tempfiles['stdout'])) {
    $output = fopen($tempfiles['stdout'], 'r');
    if ($output === FALSE) {
      _cvslib_error_message(t('Could not open output file (!filepath).', array(
        '!filepath' => $tempfiles['stdout'],
      )));
      _cvslib_delete_temporary_files($tempfiles);
      return FALSE;
    }
  }
  else {
    reset($output); // reset the array pointer, so that we can use next()
  }
  return $output;
}

/**
 * Prepare a repository item path for usage as CVS command line argument.
 */
function _cvslib_fix_path($path) {
  if ($path == '/') {
    $path = '.';
  }
  if ($path[0] == '/') {
    $path = substr($path, 1);
  }
  return escapeshellcmd($path);
}


/**
 * Retrieve the contents a repository item (probably a directory, but will also
 * work for files) and return it as list of item objects. This function is
 * equivalent to "cvs -qnf -d $repository_root rls -e $item_path".
 *
 * @param $repository_root
 *   The root directory of the CVS repository that you want to access.
 *   This function performs the necessary shell escapes, no need to do that
 *   beforehand.
 * @param $item_path
 *   A string containing an item path relative to the repository root.
 *   This function will retrieve item information for each of the corresponding
 *   items, or for all of their descendant files if the path denotes a
 *   directory. Both paths starting with a slash ("/") and paths omitting it
 *   are accepted as input.
 * @param $constraints
 *   An array of filter constraints that specify which repository items should
 *   be retrieved. The following array elements are accepted (all are optional):
 *
 *   - 'recursive': By default, only direct child items of the given directory
 *        path are retrieved. If set to TRUE, all descendant items of the given
 *        directory path are retrieved. If the item path refers to a file item,
 *        this option has no effect whatsoever.
 *   - 'show_dead_files': By default, dead (= deleted) files will not be
 *        retrieved. If set to TRUE, they will.
 *   - 'show_empty_dirs': By default, empty directories will not be retrieved.
 *        If set to TRUE, even empty directories will show up.
 *   - 'date': Retrieve files at a specific date (given as Unix timestamp).
 *   - 'revision': Retrieve files with a given revision number or on a given
 *        branch/tag (on a branch, the latest revisions will be retrieved).
 *
 * @return
 *   An array that will be filled list of item objects, with the item path as
 *   array key. Each object has the following properties:
 *
 *   - path: The absolute path in the repository, starting with '/'.
 *   - directory: TRUE if the item is a directory, FALSE if it's a file.
 *   - revision: The revision number (a string, e.g. '1.1' or '1.59.2.3').
 *       This is only set if $item->directory is FALSE.
 *   - date: The time when the above revision was committed, as Unix timestamp.
 *       This is only set if $item->directory is FALSE.
 *   - binary: TRUE if the file is marked as binary, or FALSE if not.
 *       This is only set if $item->directory is FALSE.
 *   - sticky_date: This property may exist if $constraints contained a 'date'
 *       filter, and (if set) contains a timestamp of the "sticky date" that
 *       CVS assigns to this item. Mutually exclusive with sticky_tag below.
 *   - sticky_tag: This property may exist if $constraints contained a
 *       'revision' filter describing a branch or tag, and (if set) contains
 *       the "sticky tag" (branch/tag name) that CVS assigns to this item.
 *       Mutually exclusive with sticky_date above.
 *
 *   If any errors occurred for any of the given items, boolean FALSE will be
 *   returned - remember to check the return value with "===" or "!==".
 */
function cvslib_ls($repository_root, $item_path, $constraints = array()) {
  if (!_cvslib_repository_login($repository_root)) {
    return FALSE;
  }

  $cmd = array(
    'cvs',
    '-qnf', // standard global arguments: quiet, no disk changes, no ~/.cvsrc file
    '-d '. escapeshellcmd($repository_root),
    'rls',
    '-e', // CVS/Entries output format, required by the parser in here
  );
  if (!empty($constraints['recursive'])) {
    $cmd[] = '-R';
  }
  if (!empty($constraints['show_dead_files'])) {
    $cmd[] = '-d';
  }
  if (empty($constraints['show_empty_directories'])) {
    $cmd[] = '-P';
  }
  if (!empty($constraints['revision'])) {
    $cmd[] = '-r'. $constraints['revision'];
  }
  if (!empty($constraints['date'])) {
    $cmd[] = '-D @'. $constraints['date'];
  }
  $relative_item_path = _cvslib_fix_path($item_path);
  $cmd[] = $relative_item_path;

  $tempfiles = _cvslib_add_output_pipes($cmd);
  $entries = _cvslib_exec($cmd, $tempfiles);

  if ($entries === FALSE) {
    return FALSE;
  }

  // Parse the info from the raw output.
  $contents = _cvslib_parse_ls($repository_root, $entries, $relative_item_path);

  // Close the stdout file and delete both stdout and stderr output.
  if (is_resource($entries)) {
    fclose($entries);
  }
  _cvslib_delete_temporary_files($tempfiles);

  return $contents;
}

function _cvslib_parse_ls($repository_root, &$entries, $relative_item_path) {
  // If the log was retrieved by taking the return value of exec(), we've
  // got and array and navigate it via next(). If we stored the log in a
  // temporary file, $entries is a file handle that we need to fgets() instead.
  $next = is_array($entries) ? 'next' : 'fgets';

  $context = (($relative_item_path == '.') ? '/' : ('/'. $relative_item_path .'/'));
  $items = array();

  while (($line = $next($entries)) !== FALSE) {
    if (empty($line)) {
      continue;
    }

    // We can't use 'cvs -l' because it cuts off long revision numbers, e.g.
    // "---- 2008-06-11 21:18:39 +0200 1.258.2.2+      devel.module"
    //
    // So let's use the CVS/Entries format ('-e' option), which has the
    // drawback of not displaying whether a file is dead or not.
    //
    // Format (when used with rls, which lacks sticky tag confusion and stuff):
    //   "D/$path////" for directories
    //   "/$path/$revision/$formatted_local_time/$flags/$sticky_date_or_tag" for files

    $matches_found = preg_match("@^(D?)/(.+)/([\d\.]*)/([^/]*)/([a-z\-]*)/(?:([TD])([^/]*))?$@", $line, $matches);

    if (!$matches_found) {
      // No matches means we've probably run into a directory context line,
      // e.g. ".:" for the root directory or "contributions/modules:" for a
      // regular one.
      $matches_found = preg_match("@^(.+):$@", $line, $matches);
      if ($matches_found) {
        $context = (($matches[1] == '.') ? '/' : ('/'. $matches[1] .'/'));
      }
      continue;
    }

    $item = new stdClass();
    $item->path = ($context == '/'. $matches[2] .'/')
      ? '/'. $matches[2] // file has been queried: just leave the path as is
      : $context . $matches[2]; // directory has been queried: compose the path

    if ($matches[1] == 'D') {
      $item->directory = TRUE;
    }
    else {
      $item->directory = FALSE;
      $item->revision = $matches[3];
      $item->date = strtotime($matches[4]);
      $item->binary = (strpos($matches[5], '-kb') !== FALSE);

      if ($matches[6] == 'T') { // sticky tag (which includes branches)
        // "cvs ls" is evil, and also returns revisions like "1.31" as sticky
        // tag. Tag names must start with a letter, so we can guard from that.
        if (!is_numeric($matches[7][0])) {
          $item->sticky_tag = $matches[7];
        }
      }
      else if ($matches[6] == 'D') { // sticky date
        $item->sticky_date = strtotime($matches[7]);
      }
    }
    $items[$item->path] = $item;
  }

  return $items;
}


/**
 * Retrieve and parse the logs of one or more items (files or directories)
 * into a list of file revision objects that they can be processed more easily.
 * This function is equivalent to
 * "cvs -qnf -d $repository_root rlog -S $item_paths".
 *
 * @param $repository_root
 *   The root directory of the CVS repository that you want to access.
 *   This function performs the necessary shell escapes, no need to do that
 *   beforehand.
 * @param $item_paths
 *   An array of item paths relative to the repository root, or a string
 *   containing a single path. This function will retrieve the file revisions
 *   for each of the corresponding items, or for all of their descendant files
 *   if the path denotes a directory. Both paths starting with a slash ("/")
 *   and paths omitting it are accepted as input.
 * @param $constraints
 *   An array of filter constraints that specify which file revisions should
 *   be retrieved. The following array elements are accepted (all are optional):
 *
 *   - 'recursive': By default, file revisions for all descendant files of the
 *        given directory path will be retrieved. If set to FALSE, only direct
 *        child items of the given directory path are retrieved. If the item
 *        path refers to a file item, this option has no effect whatsoever.
 *   - 'show_dead_files': By default, dead (= deleted) files will be retrieved.
 *        If set to FALSE, they won't.
 *   - 'date': Retrieve the latest revision(s) before a specific date
 *        (given as Unix timestamp). This means there will exactly be one
 *        file revision for each file item that matches the other filters.
 *        Cannot be combined with 'date_lower' and 'date_upper'.
 *   - 'date_lower': A Unix timestamp. If given, no file revisions will be
 *        retrieved that were performed earlier than this lower bound.
 *   - 'date_upper': A Unix timestamp. If given, no file revisions will be
 *        retrieved that were performed later than this upper bound.
 *   - 'revision': The revision or range of revisions that should be matched.
 *        This (string) option corresponds to the "-r" option of "cvs rlog",
 *        so have fun inspecting "cvs --help rlog" for the full documentation.
 *        Commonly used values:
 *        - "1.14" for a single revision with that revision number.
 *        - "TAGNAME" or "BRANCHNAME." for the (last) revision(s) on that
 *          branch or tag. In order to have HEAD treated as normal branch,
 *          this function accepts "HEAD." as last revision on HEAD, and "HEAD"
 *          as the whole main branch. (This differs from the command line,
 *          where "HEAD." doesn't work and "HEAD" is treated as tag.)
 *
 * @return
 *   An array that will be filled with a simple, flat list of
 *   file revision objects. Each object has the following properties:
 *
 *   - path: The absolute path in the repository, starting with '/'.
 *   - revision: The revision number (a string, e.g. '1.1' or '1.59.2.3').
 *   - date: The time of the revision, as Unix timestamp.
 *   - username: The CVS username of the committer.
 *   - dead: TRUE if the file revision is in the "dead" (deleted) state,
 *       or FALSE if it currently exists in the repository.
 *   - lines_added: An integer that specifies how many lines have been added
 *       in this revision.
 *   - lines_removed: An integer that specifies how many lines have been added
 *       in this revision.
 *   - commitid: Optional property, may exist in more recent versions of CVS.
 *       (It seems to have been introduced in 2005 or something.) If given,
 *       this is a string which is the same for all file revisions in a commit.
 *   - message: The commit message (a string with possible line breaks).
 *   - branch: The branch that this file revision was committed to,
 *       as string containing the name of the branch.
 *   - tags: A list of names of the tags that are assigned to this specific
 *       file revision.
 *
 *   If any errors occurred for any of the given items, boolean FALSE will be
 *   returned - remember to check the return value with "===" or "!==".
 */
function cvslib_log($repository_root, $item_paths = '.', $constraints = array()) {
  if (!_cvslib_repository_login($repository_root)) {
    return FALSE;
  }
  if (is_string($item_paths)) {
    $item_paths = array($item_paths);
  }
  if (empty($item_paths)) {
    $item_paths[] = '.';
  }
  $root = escapeshellcmd($repository_root);
  $filter_args = array();

  // Prepare the date filter argument.
  if (isset($constraints['date'])) {
    $filter_args[] = '-d @'. $constraints['date'];
  }
  if (isset($constraints['date_lower']) && !isset($constraints['date_upper'])) {
    $filter_args[] = '-d ">=@'. $constraints['date_lower'] .'"';
  }
  else if (!isset($constraints['date_lower']) && isset($constraints['date_upper'])) {
    $filter_args[] = '-d "<=@'. $constraints['date_upper'] .'"';
  }
  else if (isset($constraints['date_lower']) && isset($constraints['date_upper'])) {
    $filter_args[] = '-d "@'. $constraints['date_lower']
      .'<=@'. $constraints['date_upper'] .'"';
  }

  // Other filter arguments.
  if (isset($constraints['recursive']) && $constraints['recursive'] == FALSE) {
    $filter_args[] = '-l'; // "Local directory only, no recursion."
  }
  if (isset($constraints['show_dead_files']) && $constraints['show_dead_files'] == FALSE) {
    $filter_args[] = '-s Exp'; // "Exp" is the opposite of "dead"
  }
  if (isset($constraints['revision']) && $constraints['revision'] != 'HEAD') {
    // Treating "HEAD" as branch, as mentioned in the apidox above.
    if ($constraints['revision'] == 'HEAD.') {
      $constraints['revision'] = 'HEAD';
    }
    $filter_args[] = '-r'. $constraints['revision'];
  }

  // Call CVS in order to get the raw logs.
  $cmd = array(
    'cvs',
    '-qnf', // standard global arguments: quiet, no disk changes, no ~/.cvsrc file
    '-d '. $root,
    'rlog',
    '-S', // "Do not print name/header if no revisions selected."
  );
  foreach ($filter_args as $arg) {
    $cmd[] = $arg;
  }
  foreach ($item_paths as $path) {
    $cmd[] = _cvslib_fix_path($path);
  }
  $tempfiles = _cvslib_add_output_pipes($cmd);
  $logs = _cvslib_exec($cmd, $tempfiles);

  if ($logs === FALSE) {
    return FALSE;
  }

  // Parse the info from the raw output.
  $file_revisions = _cvslib_parse_log($repository_root, $logs);

  // Close the stdout file and delete both stdout and stderr output.
  if (is_resource($logs)) {
    fclose($logs);
  }
  _cvslib_delete_temporary_files($tempfiles);

  return $file_revisions;
}

/**
 * Get the part of a string that is right to the first colon,
 * trimming spaces on both input and result text.
 */
function _cvslib_explode($text, $delim = ':') {
  $parts = explode($delim, $text, 2);
  return trim($parts[1]);
}

/**
 * Parse the logs into a list of file revision objects, so that they
 * can be processed more easily.
 *
 * @param $repository_root
 *   The unescaped root directory of the CVS repository.
 * @param $logs
 *   Either an array containing all the output lines (if the output was
 *   directly read by exec()) or a file handle of the temporary file
 *   that the output was written to.
 *
 * @return
 *   An simple, flat list of file revision objects (see cvslib_log() for the
 *   format description).
 */
function _cvslib_parse_log($repository_root, &$logs) {
  // If the log was retrieved by taking the return value of exec(), we've
  // got and array and navigate it via next(). If we stored the log in a
  // temporary file, $logs is a file handle that we need to fgets() instead.
  $next = is_array($logs) ? 'next' : 'fgets';

  // Remove prefixes like ":pserver:" from the repository root.
  $root_path = preg_replace('|[^/]*(/.+)$|', '\1', $repository_root);

  $file_revisions = array();

  while (($line = $next($logs)) !== FALSE) {
    if (empty($line)) {
      continue;
    }

    $matches_found = preg_match('/^RCS file: (.+)$/', $line, $matches);
    if (!$matches_found) {
      continue;
    }
    $file = new stdClass();

    // Remove the root path and the trailing ",v".
    $file->path = trim(preg_replace("@^$root_path(.*)(,v)$@", '\1', $matches[1]));
    // Remove a possible "Attic/" directory that exists if the file
    // is currently in a "dead" state.
    $file->path = preg_replace('@^(.*/)Attic/(.*)$@', '\1\2', $file->path);

    $next($logs); // head - not used
    $next($logs); // branch - not used
    $next($logs); // locks - not used
    $next($logs); // access - not used

    // Retrieve branches and tags ("symbolic names" is the common term here).
    if (trim($next($logs)) == 'symbolic names:') {
      $file->branches = array();
      $file->tags = array();

      while (TRUE) {
        $line = $next($logs);

        if (preg_match('/^keyword substitution: (.*)$/', $line, $matches)) {
          // $matches[1] could be stored as $file->keyword, but is not used.
          break; // no branches and tags anymore, go on with the next steps
        }
        $parts = explode(':', trim($line)); // e.g. "DRUPAL-5--2-0: 1.4"

        // If the revision ends with "0.N", we know this is a branch.
        if (preg_match('/\.0\.\d+$/', trim($parts[1]))) {
          // When saving the revision number for branches, we need to
          // move the final N into the place of the '.0' when we
          // save it so that we can compare revisions numbers against
          // this value and match them to the right branch.
          $branch_prefix = preg_replace('/^(.+?)\.\d+(\.\d+)$/', '\1\2', trim($parts[1]));
          $file->branches[$branch_prefix] = trim($parts[0]);
        }
        else {
          // There's no magic for revision numbers on non-branch tags.
          // However, since multiple tags can point to the same
          // revision, here we want to key on the tag name.
          $file->tags[trim($parts[0])] = trim($parts[1]);
        }
      }
    }
    $file->tag_names = array_keys($file->tags);

    // Next line looks like "total revisions: 4;     selected revisions: 2"
    $parts = explode(';', $next($logs));
    $file->number_revisions = _cvslib_explode($parts[1]); // "2" in the above example

    // Skip until "description" (which should be the next line anyways, usually)
    while (trim($next($logs)) != "description:") {
    }
    $separator = $next($logs); // like, "----------------------------"

    for ($i = 0; $i < $file->number_revisions; $i++) {
      $file_revision = new stdClass();
      $file_revision->path = $file->path;

      $parts = explode(' ', $next($logs)); // that line is like "revision 1.9"
      $file_revision->revision = trim($parts[1]);

      // Example line (commitid is only in more recent versions of CVS):
      // date: 2007-10-02 20:44:15 +0100;  author: jakob;  state: Exp;  lines: +2 -1;  commitid: vaXgz7afKtx3m3As;
      $line = $next($logs);
      $parts = explode(';', $line);
      $file_revision->date = strtotime(_cvslib_explode($parts[0]));
      $file_revision->username = _cvslib_explode($parts[1]);

      // "state" is "Exp" or "dead" (in case no low-level modifications
      // involving 'rcs' were performed), so store this as boolean.
      $file_revision->dead = (_cvslib_explode($parts[2]) == 'dead');

      // "lines: (...)" from the above example line only appears
      // for revisions other than 1.1.
      $lines = array();
      if ($file_revision->revision !== '1.1' && $file_revision->dead == FALSE) {
        $lines = explode(' ', _cvslib_explode($parts[3]));
      }
      $file_revision->lines_added = empty($lines) ? 0 : abs($lines[0]);
      $file_revision->lines_removed = empty($lines) ? 0 : abs($lines[1]);

      // commitid is only in more recent versions of CVS -
      // use it if it's given, or fall back to single-file commits if not.
      if (preg_match('/^.+;\s+commitid: ([^;]+).*$/', $line, $matches)) {
        $file_revision->commitid = $matches[1];
      }

      // The next line is either "branches: (...)"
      // or the first line of the commit message.
      $line = $next($logs);
      $message = '';

      if (substr($line, 0, 9) != 'branches:') {
        // Not sure if $next() always includes linebreaks or not -> trim.
        $message = trim($line) ."\n";
      }

      // After that, we have either more message lines or the end of the message.
      while (($line = $next($logs)) != $separator && trim($line) != "=============================================================================") {
        $message .= "$line\n";
      }
      $file_revision->message = trim($message);

      // Retrieve the branch of this revision.
      $parts = explode('.', $file_revision->revision);

      if (empty($file->branches) || count($parts) <= 2) {
        $file_revision->branch = 'HEAD';
      }
      else {
        // Let's say we start with "1.59.2.7".
        array_pop($parts); // "1.59.2" is the only possible branch prefix
        $branch_prefix = implode('.', $parts);

        if (isset($file->branches[$branch_prefix])) {
          // Get the name of the branch that maps to this branch prefix.
          $file_revision->branch = $file->branches[$branch_prefix];
        }
        else { // should not happen, but who knows... maybe with deleted branches?
          $file_revision->branch = ''; // "branch is unknown"
        }
      }

      // Store the tags that are assigned to this file revision.
      $file_revision->tags = array();

      foreach ($file->tags as $tag_name => $revision) {
        if ($revision == $file_revision->revision) {
          $file_revision->tags[] = $tag_name;
        }
      }

      // Also, remember all branches and tags that apply to that file, by
      // simply pulling in the "branches" and "tags" properties from the $file
      // into each file revision object.
      $file_revision->all_branches = $file->branches;
      $file_revision->all_tags = $file->tag_names;

      $file_revisions[] = $file_revision;
    } // loop to the next revision of this file
  } // loop to the next file

  return $file_revisions;
}


/**
 * Copy the contents of a file in a repository to a given destination.
 * This function is equivalent to
 * 'cvs -qnf -d $repository_root checkout -p $item_path > $destination'.
 *
 * @param $destination
 *   The path of the file that should afterwards contain the file contents.
 * @param $repository_root
 *   The root directory of the CVS repository that you want to access.
 *   This function performs the necessary shell escapes, no need to do that
 *   beforehand.
 * @param $item_path
 *   A string containing the file path relative to the repository root. Both
 *   paths starting with a slash ("/") and paths omitting it are accepted
 *   as input.
 * @param $constraints
 *   An array of filter constraints that specify which repository items should
 *   be retrieved. The following array elements are accepted (all are optional):
 *
 *   - 'date': Retrieve the file at a specific date (given as Unix timestamp).
 *   - 'revision': Retrieve the file with a given revision number or on a given
 *        branch/tag (on a branch, the latest revision will be retrieved).
 *
 * @return
 *   TRUE if the file was created successfully. If the CVS invocation exited
 *   with an error, this function returns FALSE and the error message can be
 *   retrieved by calling cvslib_last_error_message().
 */
function cvslib_cat($destination, $repository_root, $item_path, $constraints = array()) {
  if (!_cvslib_repository_login($repository_root)) {
    return FALSE;
  }

  $cmd = array(
    'cvs',
    '-qnf', // standard global arguments: quiet, no disk changes, no ~/.cvsrc file
    '-d '. escapeshellcmd($repository_root),
    'checkout',
    '-p', // "Send updates to standard output (avoids stickiness)."
  );
  if (!empty($constraints['revision'])) {
    $cmd[] = '-r'. $constraints['revision'];
  }
  if (!empty($constraints['date'])) {
    $cmd[] = '-D @'. $constraints['date'];
  }
  $relative_item_path = _cvslib_fix_path($item_path);
  $cmd[] = $relative_item_path;

  $cmd[] = '> '. $destination;
  $tempfiles = _cvslib_add_output_pipes($cmd, CVSLIB_STDOUT_NO_FILE);
  $output = _cvslib_exec($cmd, $tempfiles);

  if ($output === FALSE) {
    @unlink($destination);
    return FALSE;
  }
  _cvslib_delete_temporary_files($tempfiles);
  return TRUE;
}

/**
 * Export the contents of a whole directory in a repository to a given
 * destination directory. This function is equivalent to
 * 'cvs -qf -d $repository_root export -d . $destination'.
 *
 * @param $destination
 *   The path of the directory that should afterwards contain the
 *   repository directory's contents.
 * @param $repository_root
 *   The root directory of the CVS repository that you want to access.
 *   This function performs the necessary shell escapes, no need to do that
 *   beforehand.
 * @param $item_path
 *   A string containing the directory path relative to the repository root.
 *   Both paths starting with a slash ("/") and paths omitting it are accepted
 *   as input.
 * @param $constraints
 *   An array of filter constraints that specify which repository items should
 *   be retrieved. The following array elements are accepted (all are optional):
 *
 *   - 'date': Retrieve the directory at a specific date (given as Unix timestamp).
 *   - 'revision': Retrieve the directory on a given branch/tag (for branches,
 *        the latest revision will be retrieved except if 'date' is specified).
 *
 * @return
 *   TRUE if the directory was exported successfully. If the CVS invocation
 *   exited with an error, this function returns FALSE and the error message
 *   can be retrieved by calling cvslib_last_error_message().
 */
function cvslib_export($destination, $repository_root, $item_path, $constraints = array()) {
  if (!_cvslib_repository_login($repository_root)) {
    return FALSE;
  }
  // Create the target directory and chdir() to it, because "cvs export" can't
  // take absolute export directory paths as argument.
  if (!mkdir($destination, 0777, TRUE)) {
    return FALSE;
  }
  // Guard dirname() against relative directories and the likes.
  $destination = realpath($destination);
  $cwd = getcwd();

  // CVS has problems with absolute paths as "-d" option as well as with
  // specifying the cwd (".") directly. So that only leaves chdir'ing to the
  // parent directory and exporting to the basename from there.
  if (!chdir(dirname($destination))) {
    return FALSE;
  }

  $cmd = array(
    'cvs',
    '-qf', // standard global arguments: quiet, no ~/.cvsrc file
    '-d '. escapeshellcmd($repository_root),
    'export',
    '-d '. basename($destination),
  );
  if (!empty($constraints['revision'])) {
    $cmd[] = '-r'. $constraints['revision'];
  }
  if (!empty($constraints['date'])) {
    $cmd[] = '-D @'. $constraints['date'];
  }
  $relative_item_path = _cvslib_fix_path($item_path);
  $cmd[] = $relative_item_path;

  $tempfiles = _cvslib_add_output_pipes($cmd);
  $output = _cvslib_exec($cmd, $tempfiles);
  _cvslib_delete_temporary_files($tempfiles);
  chdir($cwd);

  return ($output === FALSE) ? FALSE : TRUE;
}
