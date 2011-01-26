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
<html>
<body style="font-family: Verdana, sans-serif; font-size: 0.8em; color:#484848;">
<div class="wiki">
Issue #<?php echo $issue->id ?> has been updated by <?php echo Bugitor::format_username($comment->author); ?>
<?php if($comment->details) : ?>
<ul>
<?php foreach($comment->details as $detail): ?>
<li><?php echo $detail->change; ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<hr />
<h3><?php echo Bugitor::link_to_issue($issue, true)?></h3>
<ul>
    <li>Author: <?php echo (isset($issue->user) ? Bugitor::format_username($issue->user) : ''); ?></li>
    <li>Status: <?php echo (isset($issue->status) ? $issue->getStatusLabel($issue->status) : ''); ?></li>
    <li>Priority: <?php echo (isset($issue->issuePriority) ? $issue->issuePriority->name : ''); ?></li>
    <li>Owner: <?php echo (isset($issue->assignedTo) ? Bugitor::format_username($issue->assignedTo) : ''); ?></li>
    <li>Category: <?php echo (isset($issue->issueCategory) ? $issue->issueCategory->name : ''); ?></li>
    <li>Version: <?php echo (isset($issue->version) ? $issue->version->name : ''); ?></li>
    <li>Project: <?php echo (isset($issue->project) ? $issue->project->name : ''); ?></li>
</ul>
<?php echo Yii::app()->textile->textilize($comment->content, false); ?>
</div>
<br/>
<br/>
<hr />
<h3 style="font-size: 1.1em; font-family: 'Trebuchet MS', Verdana, sans-serif; margin: 0px;">Bugitor Issue Tracker</h3>
<span style="font-size: 0.8em; font-style: italic;">You have received this notification because you have either subscribed to it, or are involved in it.<br />
To change your notification preferences, please click here:
<a href="<?php echo Yii::app()->config->get('hostname') ?>user/profile"><?php echo Yii::app()->config->get('hostname') ?>user/profile</a>
</span>
</body>
</html>
