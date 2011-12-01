<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
	<!--<![endif]-->
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/assets/f105b38b/css/chaw.css" />
<link rel="stylesheet" type="text/css" href="/assets/cad80fdb/styles/github.css" />
<script type="text/javascript" src="/assets/a87bfd91/jquery.min.js"></script>
<script type="text/javascript" src="/assets/f105b38b/jquery.highlight_diff.min.js"></script>
<title>Github API test</title>
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<link rel="stylesheet" type="text/css" href="/themes/new/css/screen.css" />
		<!-- Favicons
		================================================== -->
		<link rel="shortcut icon" href="/themes/new/images/favicon.ico">
		<link rel="apple-touch-icon" href="/themes/new/images/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/themes/new/images/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/themes/new/images/apple-touch-icon-114x114.png">
        
        <script type="text/javascript" src="/assets/cad80fdb/highlight.pack.js"></script>
        <script type="text/javascript">
        /*<![CDATA[*/
        hljs.initHighlightingOnLoad();
        /*]]>*/
        </script>
	</head>
	<body>
<?php
require_once 'protected/vendors/php-github-api/lib/Github/Autoloader.php';
Github_Autoloader::register();

$github = new Github_Client();

$commits = $github->getCommitApi()->getBranchCommits('jacmoe', 'highlighter', 'master');
/*echo "<pre>";
print_r($commits);
echo "</pre>";*/

/*$repo = $github->getRepoApi()->show('jacmoe', 'highlighter');
echo "<pre>";
print_r($repo);
echo "</pre>";*/

$commit = $github->getCommitApi()->getCommit('jacmoe', 'highlighter', '0a0c5b493f44bd45f7e8320f5676a9595becae9c');
/*echo "<pre>";
print_r($commit);
echo "</pre>";*/

foreach($commit['modified'] as $modified){
    echo "<div class=diff>";
    echo "diff --git\n";
    //echo "<pre>";
    echo htmlspecialchars($modified['diff']);
    //echo "</pre>";
    echo "</div>";
    echo "<hr/>";
}

foreach($commits as $commit)
{
    echo "Parents:";
    echo "<br/>";
    foreach($commit['parents'] as $parent)
    {
        echo "Parent: " . $parent['id'];
        echo "<br/>";
    }
    echo "Author Name: " . $commit['author']['name'];
    echo "<br/>";
    echo "Author Login: " . $commit['author']['login'];
    echo "<br/>";
    echo "Author Email: " . $commit['author']['email'];
    echo "<br/>";
    echo "URL: " . $commit['url'];
    echo "<br/>";
    echo "ID: " . $commit['id'];
    echo "<br/>";
    echo "Committed Date: " . $commit['committed_date'];
    echo "<br/>";
    echo "Authored Date: " . $commit['authored_date'];
    echo "<br/>";
    echo "Message: " . $commit['message'];
    echo "<br/>";
    echo "Tree: " . $commit['tree'];
    echo "<br/>";
    echo "Committer Name: " . $commit['committer']['name'];
    echo "<br/>";
    echo "Committer Login: " . $commit['committer']['login'];
    echo "<br/>";
    echo "Committer Email: " . $commit['committer']['email'];
    echo "<br/>";
    echo "<hr/>";
    echo "<br/>";
}
?>
	<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
$(".diff").highlight_diff();
});
/*]]>*/
</script>
</body>
</html>