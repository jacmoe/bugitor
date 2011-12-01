<?php
require_once 'protected/vendors/php-github-api/lib/Github/Autoloader.php';
Github_Autoloader::register();

$github = new Github_Client();

$commits = $github->getCommitApi()->getBranchCommits('jacmoe', 'highlighter', 'master');

//echo "<pre>";
//print_r($commits);
//echo "</pre>";

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