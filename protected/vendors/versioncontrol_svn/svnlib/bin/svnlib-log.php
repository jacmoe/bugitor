#!/usr/bin/php
<?php

$cmd = array_shift($argv);
$rev = 'HEAD:1';

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

include_once '../svnlib.inc';

$result = svnlib_log($repos[0], $rev);
fwrite(STDOUT, print_r($result, TRUE));
