<?php $project = $this->getProject(); ?>
<h2><?php echo CHtml::link($project->name, array('project/view', 'identifier' => $project->identifier)); ?></h2>
<?php echo $project->getDescription(); ?>
<?php if($project->homepage != ''): ?>
Homepage: <?php echo CHtml::link($project->homepage); ?>
<?php endif; ?>
<br/>
<br/>
<div class="alt" style="font-size:smaller;">Created : <?php echo Time::timeAgoInWords($project->created); ?></div>
