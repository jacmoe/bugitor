<ul>
<?php foreach($this->getWatchedIssues() as $watched): ?>
<div>
<?php echo CHtml::link(CHtml::encode($watched->issue->subject),
array('issue/view', 'id'=>$watched->issue->id, 'identifier' => $watched->issue->project->identifier)); ?>
</div>
<?php endforeach; ?>
</ul>