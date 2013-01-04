#!/usr/bin/php
<?php

$command = array_shift($argv);
$recursive = FALSE;
$rev = 'HEAD';

while (!empty($argv)) {
  $arg = array_shift($argv);

  switch ($arg) {
    case '-R':
    case '--recursive':
      $recursive = TRUE;
      break;

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
  fwrite(STDERR, "Usage:\n". $argv[0] ." [-r <revision>] [-R|--recursive] <URL>\n");
  exit(1);
}

include_once '../svnlib.inc';

$result = svnlib_info($repos, $rev, $recursive);
fwrite(STDOUT, print_r($result, TRUE));
