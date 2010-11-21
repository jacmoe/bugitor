<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2010 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php

function mtrack_run_tool($toolname, $mode, $args = null)
{
  global $FORKS;

  $tool = "/home/stealth977/bin/hg";//MTrackConfig::get('tools', $toolname);
  if (!strlen($tool)) {
    $tool = $toolname;
  }
  if (PHP_OS == 'Windows' && strpos($tool, ' ') !== false) {
    $tool = '"' . $tool . '"';
  }
  $cmd = $tool;
  if (is_array($args)) {
    foreach ($args as $arg) {
      if (is_array($arg)) {
        foreach ($arg as $a) {
          $cmd .= ' ' . escapeshellarg($a);
        }
      } else {
        $cmd .= ' ' . escapeshellarg($arg);
      }
    }
  }
  if (!isset($FORKS[$cmd])) {
    $FORKS[$cmd] = 0;
  }
  $FORKS[$cmd]++;
  if (false) {
    if (php_sapi_name() == 'cli') {
      echo $cmd, "\n";
    } else {
      error_log($cmd);
      echo htmlentities($cmd) . "<br>\n";
    }
  }

  switch ($mode) {
    case 'read':   return popen($cmd, 'r');
    case 'write':  return popen($cmd, 'w');
    case 'string': return stream_get_contents(popen($cmd, 'r'));
    case 'proc':
      $pipedef = array(
        0 => array('pipe', 'r'),
        1 => array('pipe', 'w'),
        2 => array('pipe', 'w'),
      );
      $proc = proc_open($cmd, $pipedef, $pipes);
      return array($proc, $pipes);
  }
}

if (php_sapi_name() != 'cli') {
  //set_exception_handler('mtrack_last_chance_saloon');
  //error_reporting(E_NOTICE|E_ERROR|E_WARNING);
  //ini_set('display_errors', false);
  set_time_limit(300);
}

  function hg()
  {
	putenv('PYTHONPATH=/home/stealth977/.packages/lib/python');
    $args = func_get_args();
    //$a = array("-y", "-R", $this->repopath, "--cwd", $this->repopath);
	$a = array("-R", '/home/stealth977/files.ogitor.org/', "--cwd", '/home/stealth977/files.ogitor.org/');
    foreach ($args as $arg) {
      $a[] = $arg;
    }

    return mtrack_run_tool('hg', 'read', $a);
  }

$sep = uniqid();
$fp = hg('log',
      '--template', $sep . '\n{node|short}\n{branches}\n{tags}\n{file_adds}\n{file_copies}\n{file_mods}\n{file_dels}\n{author|email}\n{date|hgdate}\n{desc}\n', '-l 10');
	  
    fgets($fp); # discard leading $sep

    // corresponds to the file_adds, file_copies, file_modes, file_dels
    // in the template above
    static $file_status_order = array('A', 'C', 'M', 'D');

    while (true) {
      //$ent = new MTrackSCMEvent;
      echo 'Revision: '. trim(fgets($fp));
      echo "<br/>";
      //if (!strlen($ent->rev)) {
//        break;
//      }

//      $ent->branches = array();
      foreach (preg_split('/\s+/', trim(fgets($fp))) as $b) {
        if (strlen($b)) {
//          $ent->branches[] = $b;
        }
      }
//      if (!count($ent->branches)) {
//        $ent->branches[] = 'default';
//      }

//      $ent->tags = array();
      foreach (preg_split('/\s+/', trim(fgets($fp))) as $t) {
        if (strlen($t)) {
//          $ent->tags[] = $t;
        }
      }

//      $ent->files = array();

      foreach ($file_status_order as $status) {
        foreach (preg_split('/\s+/', trim(fgets($fp))) as $t) {
          if (strlen($t)) {
//            $f = new MTrackSCMFileEvent;
				echo 'File: ' . $t . ' ' . $status;
				echo "<br/>";
//            $f->name = $t;
//            $f->status = $status;
//            $ent->files[] = $f;
          }
        }
      }

      $changeby = trim(fgets($fp));
      //$ent->changeby = trim(fgets($fp));
	  echo 'Author: ' . $changeby;
      echo "<br/>";
      list($ts) = preg_split('/\s+/', fgets($fp));
      echo 'Time: ' . (int)$ts;
	  echo "<br/>";
	  //$ent->ctime = MTrackDB::unixtime((int)$ts);
      $changelog = array();
      while (($line = fgets($fp)) !== false) {
        $line = rtrim($line, "\r\n");
        if ($line == $sep) {
          break;
        }
        $changelog[] = $line;
      }
      $thechangelog = join("\n", $changelog);

	echo 'Changelog: ' . $thechangelog;
	echo "<br/>";

      //$res[] = $ent;

      if ($line === false) {
        break;
      }
    }
    $fp = null;
