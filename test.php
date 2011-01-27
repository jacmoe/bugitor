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
//echo $highl->process("<?php echo 'hello world!'; ?>"); ?>
<div id="container">
<div id="content">
<div id="page-content">
<div class="commit view">
		<div class="diff">
		diff --git a/controllers/source_controller.php b/controllers/source_controller.php
index 70cfc03..6e09eed 100644
--- a/controllers/source_controller.php
+++ b/controllers/source_controller.php
@@ -29,8 +29,7 @@ class SourceController extends AppController {
 	function beforeFilter() {
 		parent::beforeFilter();
 		$this-&gt;Auth-&gt;mapActions(array(
-			&#039;branches&#039; =&gt; &#039;read&#039;,
-			&#039;rebase&#039; =&gt; &#039;update&#039;

+			&#039;branches&#039; =&gt; &#039;read&#039;, &#039;rebase&#039; =&gt; &#039;update&#039;
 		));
 	}

@@ -47,18 +46,14 @@ class SourceController extends AppController {
 		} else {
 			$this-&gt;Project-&gt;Repo-&gt;update();
 		}
-
 		list($args, $path, $current) = $this-&gt;Source-&gt;initialize($this-&gt;Project-&gt;Repo, $args);

 		$title = $current;
-
 		if (!empty($args)) {
 			$title = join(&#039;/&#039;, $args) . &#039;/&#039; . $current;
 		}
 		$this-&gt;set(&#039;title_for_layout&#039;, $title);
-
 		$data = $this-&gt;Source-&gt;read($path);
-
 		$this-&gt;set(compact(&#039;data&#039;, &#039;path&#039;, &#039;args&#039;, &#039;current&#039;));
 	}

@@ -75,14 +70,12 @@ class SourceController extends AppController {
 		}
 		list($args, $path, $current) = $this-&gt;Source-&gt;initialize($this-&gt;Project-&gt;Repo, $args);

-		$data = $this-&gt;Source-&gt;read($path);
 		$title = $current;
-
 		if (!empty($args)) {
 			$title = join(&#039;/&#039;, $args) . &#039;/&#039; . $current;
 		}
 		$this-&gt;set(&#039;title_for_layout&#039;, $title);
-
+		$data = $this-&gt;Source-&gt;read($path);
 		$branch = $this-&gt;Project-&gt;Repo-&gt;branch;
 		$this-&gt;set(compact(&#039;data&#039;, &#039;path&#039;, &#039;args&#039;, &#039;current&#039;, &#039;branch&#039;));
 		$this-&gt;render(&#039;index&#039;);
diff --git a/models/comment.php b/models/comment.php
index 0a42795..ef1ce1f 100644
--- a/models/comment.php
+++ b/models/comment.php
@@ -51,6 +51,7 @@ class Comment extends AppModel {
 		if ($created &amp;&amp; $this-&gt;addToTimeline &amp;&amp; !empty($this-&gt;data[&#039;Comment&#039;][&#039;project_id&#039;])) {
 			$Timeline = ClassRegistry::init(&#039;Timeline&#039;);
 			$timeline = array(&#039;Timeline&#039; =&gt; array(
+				&#039;user_id&#039; =&gt; $this-&gt;data[&#039;Comment&#039;][&#039;user_id&#039;],
 				&#039;project_id&#039; =&gt; $this-&gt;data[&#039;Comment&#039;][&#039;project_id&#039;],
 				&#039;model&#039; =&gt; &#039;Comment&#039;,
 				&#039;foreign_key&#039; =&gt; $this-&gt;id,
diff --git a/models/source.php b/models/source.php
index 0c29207..71bb28a 100644
--- a/models/source.php
+++ b/models/source.php
@@ -38,16 +38,25 @@ class Source extends Object {
 		$this-&gt;Repo =&amp; $Repo;

 		$path = join(DS, $args);
+
 		if ($this-&gt;Repo-&gt;type == &#039;git&#039;) {
 			if(empty($args) &amp;&amp; !$this-&gt;Repo-&gt;branch) {
 				$this-&gt;branches();
 				$this-&gt;Repo-&gt;branch = null;
+			} elseif (isset($args[0])) {
+				$branches = $this-&gt;Repo-&gt;find(&#039;branches&#039;);
+
+				if (in_array($args[0], $branches)) {
+					$this-&gt;Repo-&gt;branch(array_shift($args), true);
+					$path = join(DS, $args);
+				}
 			}
 			if ($this-&gt;Repo-&gt;branch) {
 				array_unshift($args, $this-&gt;Repo-&gt;branch);
 			}
 			array_unshift($args, &#039;branches&#039;);
 		}
+		$args = array_filter($args);

 		$current = null;
 		if (count($args) &gt; 0) {
@@ -114,25 +123,21 @@ class Source extends Object {
 			$File = new File($this-&gt;Repo-&gt;working . DS .$path);
 			return array(&#039;Content&#039; =&gt; $File-&gt;read());
 		}
-
 		$isRoot = false;
-
 		$wwwPath = $base = null;
+
 		if ($path) {
 			$wwwPath = $base = join(&#039;/&#039;, explode(DS, $path)) . &#039;/&#039;;
 		}

-		$Folder = new Folder($this-&gt;Repo-&gt;working . DS . $path);
+		$Folder = new Folder($this-&gt;Repo-&gt;working . &#039;/&#039; . $path);
 		$path = Folder::slashTerm($Folder-&gt;pwd());

 		if ($this-&gt;Repo-&gt;type == &#039;git&#039;) {
 			if ($this-&gt;Repo-&gt;branch == null) {
 				$isRoot = true;
-			} else {
-				$branch = basename($this-&gt;Repo-&gt;working);
-				if ($branch != &#039;master&#039;) {
-					$wwwPath = &#039;branches/&#039; . $branch . &#039;/&#039; . $base;
-				}
+			} elseif ($this-&gt;Repo-&gt;branch != &#039;master&#039;) {
+				$wwwPath = &#039;branches/&#039; . $this-&gt;Repo-&gt;branch . &#039;/&#039; . $base;
 			}
 		}

@@ -145,6 +150,7 @@ class Source extends Object {
 			$dir[$i][&#039;name&#039;] = $dirs[$i];
 			$lookup = $path . $dirs[$i];
 			$here = $wwwPath . $dirs[$i];
+
 			if ($dirs[$i] == &#039;master&#039;) {
 				$isRoot = true;
 			}
diff --git a/models/ticket.php b/models/ticket.php
index cd2272f..f77bae3 100644
--- a/models/ticket.php
+++ b/models/ticket.php
@@ -183,6 +183,13 @@ class Ticket extends AppModel {
 			unset($this-&gt;data[&#039;Ticket&#039;][&#039;version_id&#039;]);
 		}

+		if (empty($this-&gt;data[&#039;Ticket&#039;][&#039;user_id&#039;])) {
+			$this-&gt;data[&#039;Ticket&#039;][&#039;user_id&#039;] = null;
+			if (!empty($this-&gt;data[&#039;Ticket&#039;][&#039;reporter&#039;])) {
+				$this-&gt;data[&#039;Ticket&#039;][&#039;user_id&#039;] = $this-&gt;data[&#039;Ticket&#039;][&#039;reporter&#039;];
+			}
+		}
+
 		if ($this-&gt;id) {
 			$changes = array();
 			if (isset($this-&gt;data[&#039;Ticket&#039;][&#039;previous&#039;])) {
@@ -243,6 +250,7 @@ class Ticket extends AppModel {
 		if ($created &amp;&amp; $this-&gt;addToTimeline) {
 			$Timeline = ClassRegistry::init(&#039;Timeline&#039;);
 			$timeline = array(&#039;Timeline&#039; =&gt; array(
+				&#039;user_id&#039; =&gt; $this-&gt;data[&#039;Ticket&#039;][&#039;user_id&#039;],
 				&#039;project_id&#039; =&gt; $this-&gt;data[&#039;Ticket&#039;][&#039;project_id&#039;],
 				&#039;model&#039; =&gt; &#039;Ticket&#039;,
 				&#039;foreign_key&#039; =&gt; $this-&gt;id,
diff --git a/models/wiki.php b/models/wiki.php
index 9c450d1..c091648 100644
--- a/models/wiki.php
+++ b/models/wiki.php
@@ -156,7 +156,9 @@ class Wiki extends AppModel {
 				&#039;Wiki.project_id&#039; =&gt; $this-&gt;data[&#039;Wiki&#039;][&#039;project_id&#039;],
 			));
 		}
-
+		if (empty($this-&gt;data[&#039;Wiki&#039;][&#039;user_id&#039;])) {
+			$this-&gt;data[&#039;Wiki&#039;][&#039;user_id&#039;] = null;
+		}
 		return true;
 	}

@@ -170,6 +172,7 @@ class Wiki extends AppModel {
 		if ($created &amp;&amp; $this-&gt;addToTimeline &amp;&amp; !empty($this-&gt;data[&#039;Wiki&#039;][&#039;active&#039;])) {
 			$Timeline = ClassRegistry::init(&#039;Timeline&#039;);
 			$timeline = array(&#039;Timeline&#039; =&gt; array(
+				&#039;user_id&#039; =&gt; $this-&gt;data[&#039;Wiki&#039;][&#039;user_id&#039;],
 				&#039;project_id&#039; =&gt; $this-&gt;data[&#039;Wiki&#039;][&#039;project_id&#039;],
 				&#039;model&#039; =&gt; &#039;Wiki&#039;,
 				&#039;foreign_key&#039; =&gt; $this-&gt;id,
diff --git a/plugins/repo/models/git.php b/plugins/repo/models/git.php
index 819c731..dfe4dfd 100644
--- a/plugins/repo/models/git.php
+++ b/plugins/repo/models/git.php
@@ -243,7 +243,7 @@ class Git extends Repo {
 	 */
 	function update($remote = null, $branch = null, $params = array()) {
 		$this-&gt;cd();
- 		return $this-&gt;run(&#039;pull -q&#039;, array_merge($params, array($remote, $branch)), &#039;pass&#039;);
+ 		return $this-&gt;run(&#039;pull -q&#039;, array_merge($params, array($remote, $branch)), &#039;hide&#039;);
 	}

 	/**
diff --git a/tests/cases/controllers/source_controller.test.php b/tests/cases/controllers/source_controller.test.php
index e9f225d..f0f18a9 100644
--- a/tests/cases/controllers/source_controller.test.php
+++ b/tests/cases/controllers/source_controller.test.php
@@ -41,9 +41,9 @@ class SourceControllerTest extends CakeTestCase {
 		$this-&gt;Source-&gt;constructClasses();

 		Configure::write(&#039;Content.git&#039;, TMP . &#039;tests/git/&#039;);
-
+
 		App::import(&#039;Model&#039;, &#039;Repo.Git&#039;, false);
-
+
 		$this-&gt;Git =&amp; new Git(array(
 			&#039;class&#039; =&gt; &#039;Repo.Git&#039;,
 			&#039;type&#039; =&gt; &#039;git&#039;,
diff --git a/views/source/index.ctp b/views/source/index.ctp
index b6314bc..fed0fa1 100644
--- a/views/source/index.ctp
+++ b/views/source/index.ctp
@@ -15,7 +15,7 @@
 			$path .= $part . &#039;/&#039;;
 			echo &#039;/&#039; . $html-&gt;link(&#039; &#039; . $part . &#039; &#039;, array(&#039;action&#039; =&gt; &#039;index&#039;, $path));
 		endforeach;
-		echo &#039;/ &#039; . $current;
+		echo &#039;/ &#039; . h($current);
 	?&gt;

 &lt;/h2&gt;

@@ -43,7 +43,8 @@
 			}
 	?&gt;
 			&lt;tr&lt;?php echo $class?&gt;&gt;
-				&lt;td nowrap&gt;&lt;?php echo $html-&gt;link($item[&#039;name&#039;], array(&#039;action&#039; =&gt; &#039;index&#039;, $item[&#039;path&#039;]), array(&#039;class&#039; =&gt; &#039;folder&#039;));?&gt;&lt;/td&gt;

+				&lt;td nowrap&gt;&lt;?php echo $html-&gt;link($item[&#039;name&#039;],
+					array(&#039;action&#039; =&gt; &#039;index&#039;, $item[&#039;path&#039;]), array(&#039;class&#039; =&gt; &#039;folder&#039;));?&gt;&lt;/td&gt;

 				&lt;td nowrap&gt;&lt;?php echo $item[&#039;info&#039;][&#039;author&#039;];?&gt;&lt;/td&gt;
 				&lt;td class=&quot;message&quot;&gt;&lt;?php echo $item[&#039;info&#039;][&#039;message&#039;];?&gt;&lt;/td&gt;

 				&lt;td nowrap class=&quot;date&quot;&gt;&lt;?php echo (!empty($item[&#039;info&#039;][&#039;date&#039;])) ? date(&quot;F d Y&quot;, strtotime($item[&#039;info&#039;][&#039;date&#039;])) : null;?&gt;&lt;/td&gt;	
</div>
</div>
</div>
</div>
</div>
  </body>
</html>