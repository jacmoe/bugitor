<?php $assigned_issues = $this->getAssignedIssues(); ?>
<?php if(count($assigned_issues) > 0) : ?>
<div class="alt"><?php echo count($assigned_issues); ?> issue<?php echo(count($assigned_issues)>1) ? 's' : '' ?> assigned to me.</div>
<ul>
<?php foreach($assigned_issues as $assigned): ?>
<li>
<?php echo CHtml::link(CHtml::encode($assigned->subject),
array('issue/view', 'id'=>$assigned->id, 'identifier' => $assigned->project->identifier)); ?>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
