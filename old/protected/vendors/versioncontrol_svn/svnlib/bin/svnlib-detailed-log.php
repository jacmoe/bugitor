#!/usr/bin/php
<?php

$cmd = array_shift($argv);
$rev = '1:HEAD';

while (!empty($argv)) {
  $arg = array_shift($argv);

  switch ($arg) {
    case '-r':
    case '--revision':
      $rev = array_shift($argv);
      break;

    default:
      $repos[] = $arg;
      break;
  }
}
if (empty($repos)) {
  fwrite(STDERR, "Usage:\n". $argv[0] ." [-r <revision>] <URL>\n");
  exit(1);
}

define(SVNLIB_DEBUG, 1); // pretty status indicator dots for each revision
include_once '../svnlib-deluxe.inc';

$items = svnlib_info($repos[0]);
if (!$items) {
  fwrite(STDERR, "Error retrieving item details.\n");
  exit(2);
}
$item = array_shift($items);
$repo_root = $item['repository_root'];

$revisions = svnlib_log($repos[0], $rev);
$revisions = svnlib_more_log_info($revisions, $repo_root);
fwrite(STDOUT, print_r($revisions, TRUE));
