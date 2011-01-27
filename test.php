<html>
  <head>
    <title>diff</title>
    <style type="text/css">
  body {
    margin: 2em;
    padding: 0;
    border: 0;
    font: 1em verdana, helvetica, sans-serif;
    color: #000;
    background: #fff;
    text-align: center;
  }
  ol.code {
    width: 90%;
    margin: 0 5%;
    padding: 0;
    font-size: 0.75em;
    line-height: 1.8em;
    overflow: hidden;
    color: #939399;
    text-align: left;
    list-style-position: inside;
    border: 1px solid #d3d3d0;
  }
  ol.code li {
    float: left;
    clear: both;
    width: 99%;
    white-space: nowrap;
    margin: 0;
    padding: 0 0 0 1%;
    background: #fff;
  }
  ol.code li.even { background: #f3f3f0; }
  ol.code li code {
    font: 1.2em courier, monospace;
    color: #c30;
    white-space: pre;
    padding-left: 0.5em;
  }
  .code .comment { color: #939399; }
  .code .default { color: #44c; }
  .code .keyword { color: #373; }
  .code .string { color: #c30; }
    </style>
<link rel="stylesheet" type="text/css" href="/css/generic.css" />
<link rel="stylesheet" type="text/css" href="/css/chaw.css" />
<script type="text/javascript" src="/js/jquery-1.3.1.min.js"></script>
<script type="text/javascript" src="/js/jquery.highlight_diff.min.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$(".diff").highlight_diff();
});
//]]>
</script>  </head>
  <body>
<?php

function mtrack_run_tool($toolname, $mode, $args = null) {
    global $FORKS;

    //$tool = "/home/stealth977/bin/hg";//MTrackConfig::get('tools', $toolname);
    $tool = "/usr/bin/hg"; //MTrackConfig::get('tools', $toolname);
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
        case 'read': return popen($cmd, 'r');
        case 'write': return popen($cmd, 'w');
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

function hg() {
    //putenv('PYTHONPATH=/home/stealth977/.packages/lib/python');
    $args = func_get_args();
    //$a = array("-y", "-R", $this->repopath, "--cwd", $this->repopath);
    $a = array("-R", "/opt/lampp/htdocs/repositories/bugitor/", "--cwd", "/opt/lampp/htdocs/repositories/bugitor/");
    //$a = array("-R", 'C:/wamp/www/', "--cwd", 'C:/wamp/www/');
    foreach ($args as $arg) {
        $a[] = $arg;
    }

    return mtrack_run_tool('hg', 'read', $a);
}
//$start = 100;
//$end = 150;
//$limit = 100;
//$sep = uniqid();
//$fp = hg('log',
//        '--rev', $start.':'.$end, '--template', $sep . '\n{node|short}\n{branches}\n{tags}\n{file_adds}\n{file_copies}\n{file_mods}\n{file_dels}\n{author|email}\n{date|hgdate}\n{desc}\n', '-l '.$limit);
//
//fgets($fp); # discard leading $sep
//// corresponds to the file_adds, file_copies, file_modes, file_dels
//// in the template above
//static $file_status_order = array('A', 'C', 'M', 'D');
//
//$count = 0;
//while (true) {
//    //$ent = new MTrackSCMEvent;
//    echo "<br/>";
//    echo $count .')-------------------------------------------------------------';
//    echo "<br/>";
//    $count++;
//    echo 'Revision: ' . trim(fgets($fp));
//    echo "<br/>";
//    //if (!strlen($ent->rev)) {
////        break;
////      }
////      $ent->branches = array();
//    $branches = array();
//    foreach (preg_split('/\s+/', trim(fgets($fp))) as $b) {
//        if (strlen($b)) {
//            echo 'Branch: '.$b;
//            echo '<br/>';
//            $branches[] = $b;
//        }
//    }
//    if (!count($branches)) {
//        echo 'Branch: default';
//        echo '<br/>';
//    }
////      if (!count($ent->branches)) {
////        $ent->branches[] = 'default';
////      }
//
//    $tags = array();
//    foreach (preg_split('/\s+/', trim(fgets($fp))) as $t) {
//        if (strlen($t)) {
//            echo 'Tag: '.$t;
//            echo '<br/>';
//            $tags[] = $t;
//        }
//    }
//
//    $files = array();
//    foreach ($file_status_order as $status) {
//        foreach (preg_split('/\s+/', trim(fgets($fp))) as $t) {
//            if (strlen($t)) {
////            $f = new MTrackSCMFileEvent;
//                echo 'File: ' . $t . ' ' . $status;
//                echo "<br/>";
////            $f->name = $t;
////            $f->status = $status;
////            $files[] = $f;
//            }
//        }
//    }
//
//    $changeby = trim(fgets($fp));
//    //$ent->changeby = trim(fgets($fp));
//    echo 'Author: ' . $changeby;
//    echo "<br/>";
//    list($ts) = preg_split('/\s+/', fgets($fp));
//    echo 'Time: ' . date("d-m-Y H:i:s", $ts);
//    echo "<br/>";
//    //$ent->ctime = MTrackDB::unixtime((int)$ts);
//    $changelog = array();
//    while (($line = fgets($fp)) !== false) {
//        $line = rtrim($line, "\r\n");
//        if ($line == $sep) {
//            break;
//        }
//        $changelog[] = $line;
//    }
//    $thechangelog = join("\n", $changelog);
//
//    echo 'Changelog: ' . $thechangelog;
//    echo "<br/>";
//
//    //$res[] = $ent;
//
//    if ($line === false) {
//        break;
//    }
//} //while true
//$fp = null;
?>
<?php
/* SVN FILE: $Id: highlight.php 689 2008-11-05 10:30:07Z AD7six $ */

/**
 * Class to style php code as an ordered list.
 *
 * Originally from http://shiflett.org/blog/oct/formatting-and-highlighting-php-code-listings
 * Some minor modifications to allow it to work with php4.
 *
 * PHP versions 4 and 5
 *
 * @filesource
 * @package       vendors
 * @since         Noswad site version 3
 * @version       $Revision: 689 $
 * @created      26/01/2007
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-11-05 11:30:07 +0100 (Wed, 05 Nov 2008) $
 */

/*
 * Default CSS to follow:
  body {
    margin: 2em;
    padding: 0;
    border: 0;
    font: 1em verdana, helvetica, sans-serif;
    color: #000;
    background: #fff;
    text-align: center;
  }
  ol.code {
    width: 90%;
    margin: 0 5%;
    padding: 0;
    font-size: 0.75em;
    line-height: 1.8em;
    overflow: hidden;
    color: #939399;
    text-align: left;
    list-style-position: inside;
    border: 1px solid #d3d3d0;
  }
  ol.code li {
    float: left;
    clear: both;
    width: 99%;
    white-space: nowrap;
    margin: 0;
    padding: 0 0 0 1%;
    background: #fff;
  }
  ol.code li.even { background: #f3f3f0; }
  ol.code li code {
    font: 1.2em courier, monospace;
    color: #c30;
    white-space: pre;
    padding-left: 0.5em;
  }
  .code .comment { color: #939399; }
  .code .default { color: #44c; }
  .code .keyword { color: #373; }
  .code .string { color: #c30; }
 */
class highlighter {

	function highlight () {
		$this->__construct();
	}

	function __construct() {
		ini_set('highlight.comment', 'comment');
		ini_set('highlight.default', 'default');
		ini_set('highlight.keyword', 'keyword');
		ini_set('highlight.string', 'string');
		ini_set('highlight.html', 'html');
	}

	function process($code= "") {
		$code= highlight_string($code, TRUE);
                $code= substr($code, 33, -15);
                $code= str_replace('<span style="color: ', '<span class="', $code);
		$code= str_replace('&nbsp;', ' ', $code);
		$code= str_replace('&amp;', '&#38;', $code);
		$code= str_replace('<br />', "\n", $code);
		$code= trim($code);

		/* Normalize Newlines */
		$code= str_replace("\r", "\n", $code);
		$code= preg_replace("!\n\n+!", "\n", $code);

		$lines= explode("\n", $code);
		while(strip_tags($lines[count($lines) -1]) == '') {
			$lines[count($lines) -2] .= $lines[count($lines) -1];
			unset($lines[count($lines) -1]);
		}

		/* Previous Style */
		$previous= FALSE;

		/* Output Listing */
		$return= "  <ol class=\"code\">\n";
		foreach ($lines as $key => $line) {
			if (substr($line, 0, 7) == '</span>') {
				$previous= FALSE;
				$line= substr($line, 7);
			}

			if (empty ($line)) {
				$line= '&#160;';
			}

			if ($previous) {
				$line= "<span class=\"$previous\">" . $line;
			}

			/* Set Previous Style */
			if (strpos($line, '<span') !== FALSE) {
				switch (substr($line, strrpos($line, '<span') + 13, 1)) {
					case 'c' :
						$previous= 'comment';
						break;
					case 'd' :
						$previous= 'default';
						break;
					case 'k' :
						$previous= 'keyword';
						break;
					case 's' :
						$previous= 'string';
						break;
				}
			}

			/* Unset Previous Style Unless Span Continues */
			if (substr($line, -7) == '</span>') {
				$previous= FALSE;
			}
			elseif ($previous) {
				$line .= '</span>';
			}

			if ($key % 2) {
				$return .= "    <li class=\"even\"><code>$line</code></li>\n";
			} else {
				$return .= "    <li><code>$line</code></li>\n";
			}
		}
		$return .= "  </ol>\n";
		return $return;
	}
}

//$highl = new highlighter();
$revision = 648;
$path = "protected/components/BugitorMenu.php";
$path2 = "protected/controllers/ChangesetController.php";
$path3 = "protected/views/changeset/view.php";
$path4 = "themes/classic/views/layouts/main.php";
?>
<a name="top"></a>
<div id="container">
<div id="content">
<div id="page-content">
<div class="commit view">
<a name="top"></a>
<ul>
    <li><a href="#<?php echo $path; ?>"><?php echo $path; ?></a></li>
    <li><a href="#<?php echo $path2; ?>"><?php echo $path2; ?></a></li>
    <li><a href="#<?php echo $path3; ?>"><?php echo $path3; ?></a></li>
    <li><a href="#<?php echo $path4; ?>"><?php echo $path4; ?></a></li>
</ul>
<a name="<?php echo $path; ?>"></a><div class="diff box">
<?php echo htmlspecialchars(`/usr/bin/hg diff --git -r{$revision} -R /opt/lampp/htdocs/repositories/bugitor --cwd /opt/lampp/htdocs/repositories/bugitor {$path}`); ?>
</div>
<a style="float: right;" href="#top">Up To File-list</a>
<a name="<?php echo $path2; ?>"></a><div class="diff box">
<?php echo htmlspecialchars(`/usr/bin/hg diff --git -r{$revision} -R /opt/lampp/htdocs/repositories/bugitor --cwd /opt/lampp/htdocs/repositories/bugitor {$path2}`); ?>
</div>
<a style="float: right;" href="#top">Up To File-list</a>
<a name="<?php echo $path3; ?>"></a><div class="diff box">
<?php echo htmlspecialchars(`/usr/bin/hg diff --git -r{$revision} -R /opt/lampp/htdocs/repositories/bugitor --cwd /opt/lampp/htdocs/repositories/bugitor {$path3}`); ?>
</div>
<a style="float: right;" href="#top">Up To File-list</a>
<a name="<?php echo $path4; ?>"></a><div class="diff box">
<?php echo htmlspecialchars(`/usr/bin/hg diff --git -r{$revision} -R /opt/lampp/htdocs/repositories/bugitor --cwd /opt/lampp/htdocs/repositories/bugitor {$path4}`); ?>
</div>
<a style="float: right;" href="#top">Up To File-list</a>
<?php
$from = 10;
    $to = 11;
    $path = '/opt/lampp/htdocs/repositories/bugitor/';

    //$fp = mtrack_run_tool('hg', 'read', 'diff --git -r 600 -R '. $path . ' --cwd ' . $path);
    //$fp = hg('diff', '--git');
    //$fp = hg('diff', '-U', 8, '/opt/lampp/htdocs/repositories/bugitor');
    //$fp = hg("diff", "--git", "-r600");
    $revision = 600;
    //$fp = hg('diff', '--git' , '--rev', $revision);
    //echo fgets($fp);
?>
</div>
</div>
</div>
</div>
  </body>
</html>