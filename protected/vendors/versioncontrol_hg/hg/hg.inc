<?php
// $Id: hg.inc,v 1.8 2008/02/03 04:01:12 ezyang Exp $

/**
 * @file
 * Generic functions for interfacing with Mercurial via command line.
 *
 * @note
 *    There are some conventions used in this file. They are:
 *      - Global options always come after the local options in the
 *        parameter list.
 *      - Command line strings always have a trailing space, so it is safe 
 *        to append anything afterwards.
 *
 * @note
 *    These functions can be run *without* Drupal.
 *
 * @warning
 *    If no return value is specified in the docblock, the raw output
 *    from the command line call is passed back. This behavior may change.
 *
 * @warning
 *    This file does not implement all of Mercurial's functions, just the
 *    ones deemed most useful to version control API. Future versions
 *    should include all functions.
 *
 * Copyright 2008 by Edward Z. Yang (ezyang, http://drupal.org/user/211688)
 */

/**
 * Implode an options array into flags and arguments.
 *
 * @param $opts
 *    Options array in form array($flag => $value).
 * @return
 *    String command-line fragment.
 */
function _hg_implode_opts($opts) {
  $cmd = '';
  foreach ($opts as $flag => $value) {
    if (strlen($flag) == 1) {
      $cmd .= '-';
    }
    else {
      $cmd .= '--';
    }
    $cmd .= $flag .' ';
    if (is_string($value)) {
      $cmd .= _hg_escape_arg($value) .' ';
    }
  }
  return $cmd;
}

/**
 * Escapes an argument for command line.
 *
 * @note
 *    This function exists so that, if we find bugs in PHP's argument
 *    escaping implementation, we can overload it.
 */
function _hg_escape_arg($string) {
  return escapeshellarg($string);
}

/**
 * Build the base command for Mercurial.
 *
 * @param $command
 *    Mercurial command, like 'pull' or 'commit'.
 * @param $opts
 *    Command specific options.
 * @param $gopts
 *    Global options that precede the command.
 */
function _hg_build_base($command, $opts = array(), $gopts = array()) {
  $cmd = 'hg ';
  $cmd .= _hg_implode_opts($gopts);
  $cmd .= "$command ";
  $cmd .= _hg_implode_opts($opts);
  return $cmd;
}

/**
 * Retrieve Mercurial's revision history of files or a project.
 * 
 * @param $repository
 *     Repository root directory.
 * @param $rev
 *     Integer revision or string revision range (like 4:2) to retrieve logs for.
 * @param $file
 *     File(s) to retrieve logs for.
 * @param $opts
 *     Extra options to pass.
 * @param $gopts
 *     Extra global options to pass
 * @return
 *     List of revisions with various information. See HgLogTest in
 *     tests/hg.test for more information on basic format.
 */
function hg_log($repository, $rev = null, $file = null, $opts = array(), $gopts = array()) {
  $gopts['repository'] = $repository;
  if (!isset($gopts['debug'])) {
    // Turn on debug mode to ensure full nodeids.
    $gopts['debug'] = true;
  }
  // This template puts the data into a machine readable format:
  $opts['style'] = dirname(__FILE__) .'/templates/lossless.style';
  if ($rev !== null) $opts['rev'] = $rev;
  $cmd = _hg_build_base('log', $opts, $gopts);
  if ($file !== null) {
    $cmd .= _hg_escape_arg($file) .' ';
  }
  exec($cmd, $out);
  if (empty($out)) return false;
  $ret = array();
  $i = 0;
  $collect = false;
  foreach ($out as $line) {
    if ($line === '----') {
      $i++;
      continue;
    }
    if ($collect !== false) {
      if ($line === '') {
        $collect = false;
        continue;
      }
      $ret[$i][$collect][] = $line;
      continue;
    }
    if ($line === '') {
      continue;
    }
    list($key, $value) = explode(':', $line, 2);
    switch ($key) {
      case 'desc':
        $value = urldecode($value);
        break;
      case 'files':
      case 'file_adds':
      case 'file_dels':
      case 'file_copies':
      case 'tags':
        $collect = $key;
        $ret[$i][$key] = array();
        break;
    }
    if ($collect !== false) {
      continue;
    }
    $ret[$i][$key] = $value;
  }
  return $ret;
}

/**
 * Create a copy of an existing Mercurial repository to a new directory.
 * 
 * @param $source
 *     Source repository to clone.
 * @param $dest
 *     Destination of clone.
 * @param $opts
 *     Extra options
 * @param $gopts
 *     Global extra options
 */
function hg_clone($source, $dest = null, $opts = array(), $gopts = array()) {
  $cmd = _hg_build_base('clone', $opts, $gopts);
  $cmd .= _hg_escape_arg($source) .' ';
  if ($dest !== null) {
    $cmd .= _hg_escape_arg($dest) .' ';
  }
  return shell_exec($cmd .'2>&1');
}

/**
 * Commit changes to given files to repository. This function is used
 * primarily for testing.
 *
 * @param $repository
 *     Repository root directory.
 * @param $user
 *    User to be recorded as committer.
 * @param $message
 *    Commit message.
 * @param $file
 *    File(s) to commit, leave NULL to commit everything
 * @param $opts
 *     Extra options
 * @param $gopts
 *     Global extra options
 */
function hg_commit($repository, $user, $message, $file = null, $opts = array(), $gopts = array()) {
  $gopts['repository'] = $repository;
  $opts['user'] = $user;
  $opts['message'] = $message;
  $cmd = _hg_build_base('commit', $opts, $gopts);
  if ($file !== null) {
    if (is_string($file)) {
      $file = array($file);
    }
    $cmd .= implode(' ', array_map('_hg_escape_arg', $file)) .' ';
  }
  return shell_exec($cmd .'2>&1');
}

/**
 * Finds all changes from a specified Mercurial repository and adds them to
 * the local repository.
 *
 * @param $repository
 *    Repository to import changes into
 * @param $source
 *    Repository to import changes from
 * @param $update
 *    Whether or not to update the local checkout
 * @param $opts
 *     Extra options
 * @param $gopts
 *     Global extra options
 */
function hg_pull($repository, $source = null, $update = FALSE, $opts = array(), $gopts = array()) {
  $gopts['repository'] = $repository;
  if ($update !== FALSE) $opts['update'] = TRUE;
  $cmd = _hg_build_base('pull', $opts, $gopts);
  if ($source !== null) {
    $cmd .= _hg_escape_arg($source) .' ';
  }
  return shell_exec($cmd .'2>&1');
}

/**
 * Retrieves the contents of a specified file at a given revision.
 *
 * @param $repository
 *    Repository root directory
 * @param $file
 *    File to retrieve contents of
 * @param $rev
 *    Optional revision of file, if NULL use latest
 * @param $opts
 *    Extra options
 * @param $gopts
 *    Global extra options
 * @return
 *    Contents of file, or FALSE if error.
 */
function hg_cat($repository, $file, $rev = NULL, $opts = array(), $gopts = array()) {
  $gopts['cwd'] = $repository;
  if ($rev !== NULL) {
    $opts['rev'] = $rev;
  }
  $cmd = _hg_build_base('cat', $opts, $gopts);
  $cmd .= _hg_escape_arg($file) .' ';
  $value = shell_exec($cmd);
  if ($value === '' || $value === NULL) {
    system($cmd, $exit_code);
    if ($exit_code !== 0) {
      return FALSE;
    }
  }
  return $value;
}

/**
 * Parses the contents of .hgtags into array($tag => $nodeid).
 * @param $hgtags
 *    Contents of .hgtags, probably retrieved by hg_cat();
 */
function _hg_parse_hgtags($hgtags) {
  $tags = array();
  if ($hgtags) {
    $temp = explode("\n", $hgtags);
    foreach ($temp as $line) {
      $line = rtrim($line);
      if ($line === '') continue;
      list($nodeid, $tagname) = explode(" ", $line);
      $tags[$tagname] = $nodeid;
    }
  }
  return $tags;
}

