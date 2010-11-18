<?php $watched_issues = $this->getWatchedIssues(); ?>
<?php if(count($watched_issues) > 0) : ?>
<div class="alt"><?php echo count($watched_issues); ?> issue<?php echo(count($watched_issues)>1) ? 's' : '' ?> watched by me.</div>
<ul>
<?php foreach($watched_issues as $watched): ?>
<li>
<?php echo CHtml::link(CHtml::encode($watched->issue->subject),
array('issue/view', 'id'=>$watched->issue->id, 'identifier' => $watched->issue->project->identifier)); ?>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
