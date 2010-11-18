<ul>
<?php foreach($this->getAssignedIssues() as $assigned): ?>
<div>
<?php echo CHtml::link(CHtml::encode($assigned->subject),
array('issue/view', 'id'=>$assigned->id, 'identifier' => $assigned->project->identifier)); ?>
</div>
<?php endforeach; ?>
</ul>