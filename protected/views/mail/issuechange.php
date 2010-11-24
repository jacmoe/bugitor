<html>
<head>
<style>
body {
  font-family: Verdana, sans-serif;
  font-size: 0.8em;
  color:#484848;
}
h1, h2, h3 { font-family: "Trebuchet MS", Verdana, sans-serif; margin: 0px; }
h1 { font-size: 1.2em; }
h2, h3 { font-size: 1.1em; }
a, a:link, a:visited { color: #2A5685;}
a:hover, a:active { color: #c61a1a; }
a.wiki-anchor { display: none; }
hr {
  width: 100%;
  height: 1px;
  background: #ccc;
  border: 0;
}
.footer {
  font-size: 0.8em;
  font-style: italic;
}
</style>
</head>
<body>
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
<?php $this->widget('ext.mail.MailDebug'); ?>
<div class="wiki">
<hr />
Issue #<?php echo $issue->id ?> has been updated by <?php echo Bugitor::format_username($comment->author); ?>
<h1><?php echo Bugitor::link_to_issue($issue, true)?></h1>
<?php if($comment->details) : ?>
<ul>
<?php foreach($comment->details as $detail): ?>
<li><?php echo $detail->change; ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php echo Yii::app()->textile->textilize($comment->content, false); ?>
</div>
<br/>
<br/>
<hr />
<h3>Bugitor Issue Tracker</h3>
<span class="footer">You have received this notification because you have either subscribed to it, or are involved in it.<br />
To change your notification preferences, please click here:
<a href="http://files.ogitor.org/user/profile">http://files.ogitor.org/user/profile</a>
    <?php //echo CHtml::link(Yii::app()->request->hostInfo.'/user/profile', Yii::app()->request->hostInfo.'/user/profile'); ?>
</span>
</body>
</html>
