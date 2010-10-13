<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tracker-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_in_chlog'); ?>
		<?php echo $form->textField($model,'is_in_chlog'); ?>
		<?php echo $form->error($model,'is_in_chlog'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_in_roadmap'); ?>
		<?php echo $form->textField($model,'is_in_roadmap'); ?>
		<?php echo $form->error($model,'is_in_roadmap'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'position'); ?>
		<?php echo $form->textField($model,'position'); ?>
		<?php echo $form->error($model,'position'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->