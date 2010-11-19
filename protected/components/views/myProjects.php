<?php $projects = $this->getMyProjects(); ?>
<?php if(count($projects) > 0) : ?>
<div class="alt"><?php echo count($projects); ?> project<?php echo(count($projects)>1) ? 's' : '' ?> I am a member of.</div>
<ul>
<?php foreach($projects as $project): ?>
<li>
<?php echo CHtml::link(CHtml::encode($project->project->name),
array('project/view', 'identifier' => $project->project->identifier)); ?>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
