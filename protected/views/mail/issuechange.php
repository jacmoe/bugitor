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
<h1><?php echo Bugitor::link_to_issue($issue, true)?></h1>
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
<a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/user/profile">http://<?php echo $_SERVER['HTTP_HOST'] ?>/user/profile</a>
</span>
</body>
</html>
