<?php

include_once('protected/vendors/bitbucket-api-library/bbApi.php');

$bb = new bbApi();

// $user and $password defined in credentials.php
require(dirname(__FILE__) . '/credentials.php');

$repository = "jacmoes";

$bb->authenticate($user, $pass);
$changesets = new bbApiChangesets($bb);
$changeList = $changesets->show($repository);
//print_r($changeList);
$count = $changeList->count;
echo "Number of changesets: " . $count . "<br/>";
$loop = (int)($count / 15) - 1;
//echo "Looping " . $loop . " times" . "<br/>";
echo "<hr/>";
echo "<br/>";

$looper = 0;
$counter = 0;

while($counter <= $loop)
{
    $changeList = $changesets->show($repository, null, null, $looper);

    foreach($changeList->changesets as $key => $val)
    {
        echo "Revision: " . $val->revision;
        echo "<br/>";
        echo "Node: " . $val->node;
        echo "<br/>";
        echo "Raw Node: " . $val->raw_node;
        echo "<br/>";
        echo "Timestamp: " . $val->timestamp;
        echo "<br/>";
        echo "UTC Timestamp: " . $val->utctimestamp;
        echo "<br/>";
        echo "Branch: " . $val->branch;
        echo "<br/>";
        echo "Message: " . $val->message;
        echo "<br/>";
        echo "Author: " . $val->author;
        echo "<br/>";
        foreach($val->parents as $parent)
        {
            echo "Parent: " . $parent;
            echo "<br/>";
        }
        echo "<hr/>";
        echo "<br/>";
    }
    $counter++;
    $looper += 15;
}

$remaining = $count - $looper;

if($remaining > 0)
{
    $changeList = $changesets->show($repository, null, null, 'tip', $remaining - 1);

    foreach($changeList->changesets as $key => $val)
    {
        echo "Revision: " . $val->revision;
        echo "<br/>";
        echo "Node: " . $val->node;
        echo "<br/>";
        echo "Raw Node: " . $val->raw_node;
        echo "<br/>";
        echo "Timestamp: " . $val->timestamp;
        echo "<br/>";
        echo "UTC Timestamp: " . $val->utctimestamp;
        echo "<br/>";
        echo "Branch: " . $val->branch;
        echo "<br/>";
        echo "Message: " . $val->message;
        echo "<br/>";
        echo "Author: " . $val->author;
        echo "<br/>";
        foreach($val->parents as $parent)
        {
            echo "Parent: " . $parent;
            echo "<br/>";
        }
        echo "<hr/>";
        echo "<br/>";
    }
}
?>