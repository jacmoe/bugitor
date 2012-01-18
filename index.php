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
 * Copyright (C) 2009 - 2011 Bugitor Team
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
ob_start();
@header("HTTP/1.1 503 Service Temporarily Unavailable");
@header("Status: 503 Service Temporarily Unavailable");
@header("Retry-After: 120");
@header("Connection: Close");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Bugitor Issue Tracker - STOP SOPA!</title>

<style type="text/css" media="all">
html,
body {
    margin: 0;
    padding: 0;
}

#text-shadow-box {
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: #444;
    font-family: Helvetica, Arial, sans-serif;
    -webkit-tap-highlight-color: rgba(0,0,0,0);
  -webkit-user-select: none;
}

#text-shadow-box #tsb-text,
#text-shadow-box #tsb-link {
    position: absolute;
    top: 40%;
    left: 0;
    width: 100%;
    height: 1em;
    margin: -0.77em 0 0 0;
    font-size: 90px;
    line-height: 1em;
    font-weight: bold;
    text-align: center;
}

#text-shadow-box #tsb-text {
    font-size: 100px;
    color: transparent;
}

#text-shadow-box #tsb-link a {
    color: #999;
    text-decoration: none;
}

#text-shadow-box #tsb-box,
#text-shadow-box #tsb-wall {
    position: absolute;
    top: 40%;
    left: 0;
    width: 100%;
    height: 60%;
}

#text-shadow-box #tsb-wall {
    background: #999;
}

#text-shadow-box #tsb-wall p {
    font-size: 18px;
    line-height: 1.5em;
    text-align: justify;
    color: #222;
    width: 550px;
    margin: 1.5em auto;
}

#text-shadow-box #tsb-wall p a {
    color: #fff;
}

#text-shadow-box #tsb-wall p a:hover {
    text-decoration: none;
    color: #000;
    background: #fff;
}

#tsb-spot {
    position: absolute;
    top: 0;
    left: 0;
    width: 200%;
    height: 200%;
    pointer-events: none;
    background: -webkit-gradient(radial, center center, 0, center center, 350, from(rgba(0,0,0,0)), to(rgba(0,0,0,1)));
    background: -moz-radial-gradient(center 45deg, circle closest-side, transparent 0, black 350px);
}
</style>

</head>
<body>

<div id="text-shadow-box">
    <div id="tsb-box"></div>
    <p id="tsb-text">STOP SOPA!</p>
    <p id="tsb-link"><a href="http://americancensorship.org/">STOP SOPA!</a></p>
    <div id="tsb-wall">
        <p>This site has gone dark today in protest of the U.S. Stop Online Piracy Act (SOPA) and PROTECT-IP Act (PIPA).  The U.S. Congress is about to censor the Internet, even though the vast majority of Americans are opposed. We need to kill these bills to protect our rights to free speech, privacy, and prosperity.  Learn more at <a href="http://americancensorship.org/">AmericanCensorship.org</a></p>
    </div>
    <div id="tsb-spot"></div>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">
/**
 * Zachary Johnson
 * http://www.zachstronaut.com
 * I place the following code in the public domain.
 */

var text = null;
var spot = null;
var box = null;
var boxProperty = '';

init();

function init() {
    text = document.getElementById('tsb-text');
    spot = document.getElementById('tsb-spot');
    box = document.getElementById('tsb-box');

    if (typeof box.style.webkitBoxShadow == 'string') {
        boxProperty = 'webkitBoxShadow';
    } else if (typeof box.style.MozBoxShadow == 'string') {
        boxProperty = 'MozBoxShadow';
    } else if (typeof box.style.boxShadow == 'string') {
        boxProperty = 'boxShadow';
    }

    if (text && spot && box) {
        document.getElementById('text-shadow-box').onmousemove = onMouseMove;
        document.getElementById('text-shadow-box').ontouchmove = function (e) {e.preventDefault(); e.stopPropagation(); onMouseMove({clientX: e.touches[0].clientX, clientY: e.touches[0].clientY});};
    }

    onMouseMove({clientX: Math.floor(window.innerWidth / 2), clientY: Math.floor(window.innerHeight / 2.75)});
}

function onMouseMove(e) {
    var xm = (e.clientX - Math.floor(window.innerWidth / 2)) * 0.4;
    var ym = (e.clientY - Math.floor(window.innerHeight / 3)) * 0.4;
    var d = Math.round(Math.sqrt(xm*xm + ym*ym) / 5);
    text.style.textShadow = -xm + 'px ' + -ym + 'px ' + (d + 10) + 'px black';

    if (boxProperty) {
        box.style[boxProperty] = '0 ' + -ym + 'px ' + (d + 30) + 'px black';
    }

    xm = e.clientX - window.innerWidth;
    ym = e.clientY - window.innerHeight;
    spot.style.backgroundPosition = xm + 'px ' + ym + 'px';
}
</script>

</body>
</html>
<?php
$g=ob_get_clean();
echo $g;
exit;
exit();

$message = '';

if (!file_exists(dirname(__FILE__) . '/protected/config/db.php')) {
    $message .= '<h1>Error</h1>';
    $message .= '<h3>Database connection does not seem to have been configured</h3>';
    $message .= '<p><tt>Please run the <a href="/installer">Bugitor Installer</a> to configure database instance</tt></p>';
    die($message);
}

$hostname = $_SERVER['SERVER_NAME'];

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/framework/yii.php';
if (!file_exists($yii)) {
    $message .= '<h1>Error</h1>';
    $message .= '<h3>Yii Framework cannot be found</h3>';
    $message .= "<p><tt><i>\"{$yii}\"</i> is not a valid path.</tt></p>";
    $message .= "<p><tt>Please review - and edit - the following files:</tt></p>";
    $message .= "<ul>";
    $message .= "<li>BUGITOR/index.php</li>";
    $message .= "<li>BUGITOR/protected/yiic.php</li>";
    $message .= "<li>BUGITOR/installer/index.php</li>";
    $message .= "<li>BUGITOR/installer/protected/yiic.php</li>";
    $message .= "</ul>";
    die($message);
}

switch ( strtolower($hostname))
{
case 'localhost':
case 'bugitor.localhost':
case '127.0.0.1':
    $config=dirname(__FILE__).'/protected/config/local.php';
    // remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    // specify how many levels of call stack should be shown in each log message
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',2);
    break;
default:
    $config=dirname(__FILE__).'/protected/config/main.php';
    break;
}

require_once($yii);
$app = Yii::createWebApplication($config);

// we need to set this to UTC, regardless of default timezone 
// which is only for display. UTC is what timestamps etc. are using.
Yii::app()->setTimeZone("UTC");

$app->run();
