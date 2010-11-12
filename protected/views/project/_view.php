<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'identifier' =>$data->identifier)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('homepage')); ?>:</b>
	<?php echo CHtml::encode($data->homepage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('public')); ?>:</b>
	<?php echo CHtml::encode($data->public); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo Time::timeAgoInWords($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified')); ?>:</b>
	<?php echo Time::timeAgoInWords($data->modified); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('identifier')); ?>:</b>
	<?php echo CHtml::encode($data->identifier); ?>
	<br />

	*/ ?>

</div>