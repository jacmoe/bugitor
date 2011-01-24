<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('revision')); ?>:</b>
	<?php echo CHtml::encode($data->revision); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('scm_id')); ?>:</b>
	<?php echo CHtml::encode($data->scm_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('commit_date')); ?>:</b>
	<?php echo CHtml::encode($data->commit_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('message')); ?>:</b>
	<?php echo CHtml::encode($data->message); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('short_rev')); ?>:</b>
	<?php echo CHtml::encode($data->short_rev); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('parent')); ?>:</b>
	<?php echo CHtml::encode($data->parent); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('branch')); ?>:</b>
	<?php echo CHtml::encode($data->branch); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tags')); ?>:</b>
	<?php echo CHtml::encode($data->tags); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('add')); ?>:</b>
	<?php echo CHtml::encode($data->add); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edit')); ?>:</b>
	<?php echo CHtml::encode($data->edit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('del')); ?>:</b>
	<?php echo CHtml::encode($data->del); ?>
	<br />

	*/ ?>

</div>