<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_in_chlog')); ?>:</b>
	<?php echo CHtml::encode($data->is_in_chlog); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_in_roadmap')); ?>:</b>
	<?php echo CHtml::encode($data->is_in_roadmap); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('position')); ?>:</b>
	<?php echo CHtml::encode($data->position); ?>
	<br />


</div>