<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
        <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id, 'name' => $data->project->name)); ?>
        <br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tracker_id')); ?>:</b>
	<?php echo CHtml::encode($data->tracker->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('project_id')); ?>:</b>
	<?php echo CHtml::encode($data->project->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('subject')); ?>:</b>
	<?php echo CHtml::encode($data->subject); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php //$this->beginWidget('CMarkdown', array('purifyOutput'=>true)); ?>
	<?php echo $data->getDescription(); ?>
	<?php //$this->endWidget(); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('due_date')); ?>:</b>
	<?php echo CHtml::encode($data->due_date); ?>
	<br />

	<?php /*
        <b><?php echo CHtml::encode($data->getAttributeLabel('issue_category_id')); ?>:</b>
	<?php echo CHtml::encode($data->issue_category_id); ?>
	<br />
        */ ?>
	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('issue_priority_id')); ?>:</b>
	<?php echo CHtml::encode($data->issue_priority_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('version_id')); ?>:</b>
	<?php echo CHtml::encode($data->version_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('assigned_to')); ?>:</b>
	<?php echo CHtml::encode($data->assigned_to); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified')); ?>:</b>
	<?php echo CHtml::encode($data->modified); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_date')); ?>:</b>
	<?php echo CHtml::encode($data->start_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('done_ratio')); ?>:</b>
	<?php echo CHtml::encode($data->done_ratio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('closed')); ?>:</b>
	<?php echo CHtml::encode($data->closed); ?>
	<br />

	*/ ?>

</div>