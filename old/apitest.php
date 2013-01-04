<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
        <!--<![endif]-->
        <head>
                <meta charset="utf-8" />
                <link rel="stylesheet" type="text/css" href="/testassets/chaw.css" />
        <script type="text/javascript" src="/testassets/jquery.min.js"></script>
        <script type="text/javascript" src="/testassets/jquery.highlight_diff.min.js"></script>
        <title>Bitbucket API test</title>
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
        </head>
        <body>
<?php

include_once('protected/vendors/bitbucket-api-library/bbApi.php');

$bb = new bbApi();

// $user and $password defined in credentials.php
require(dirname(__FILE__) . '/credentials.php');

$repository = "jacmoes";

$bb->authenticate($user, $pass);
$changesets = new bbApiChangesets($bb);
$changeList = $changesets->show($repository);
//$changeList = $changesets->show($repository, null, 'dc580ce65088');
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
        foreach($val->files as $file)
        {
            print_r($file);
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
        <script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
$(".diff").highlight_diff();
});
/*]]>*/
</script>
</body>
</html>