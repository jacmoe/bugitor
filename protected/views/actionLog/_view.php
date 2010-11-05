<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('action')); ?>:</b>
	<?php echo CHtml::encode($data->action); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('model')); ?>:</b>
	<?php echo CHtml::encode($data->model); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idModel')); ?>:</b>
	<?php echo CHtml::encode($data->idModel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('field')); ?>:</b>
	<?php echo CHtml::encode($data->field); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creationdate')); ?>:</b>
	<?php echo CHtml::encode($data->creationdate); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('userid')); ?>:</b>
	<?php echo CHtml::encode($data->userid); ?>
	<br />

	*/ ?>

</div>