<?php $owned_issues = $this->getOwnedIssues(); ?>
<?php if(count($owned_issues) > 0) : ?>
<div class="alt"><?php echo count($owned_issues); ?> issue<?php echo(count($owned_issues)>1) ? 's' : '' ?> owned by me.</div>
<ul>
<?php foreach($owned_issues as $owned): ?>
<li>
<?php echo CHtml::link(CHtml::encode($owned->subject),
array('issue/view', 'id'=>$owned->id, 'identifier' => $owned->project->identifier)); ?>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
