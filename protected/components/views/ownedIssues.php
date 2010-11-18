<ul>
<?php foreach($this->getOwnedIssues() as $owned): ?>
<div>
<?php echo CHtml::link(CHtml::encode($owned->subject),
array('issue/view', 'id'=>$owned->id, 'identifier' => $owned->project->identifier)); ?>
</div>
<?php endforeach; ?>
</ul>