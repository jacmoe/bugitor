<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'changeset-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'revision'); ?>
		<?php echo $form->textField($model,'revision',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'revision'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'scm_id'); ?>
		<?php echo $form->textField($model,'scm_id'); ?>
		<?php echo $form->error($model,'scm_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'commit_date'); ?>
		<?php echo $form->textField($model,'commit_date'); ?>
		<?php echo $form->error($model,'commit_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'message'); ?>
		<?php echo $form->textArea($model,'message',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'message'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'short_rev'); ?>
		<?php echo $form->textField($model,'short_rev'); ?>
		<?php echo $form->error($model,'short_rev'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'parent'); ?>
		<?php echo $form->textField($model,'parent',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'parent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'branch'); ?>
		<?php echo $form->textField($model,'branch',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'branch'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php echo $form->textField($model,'tags',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tags'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'add'); ?>
		<?php echo $form->textField($model,'add'); ?>
		<?php echo $form->error($model,'add'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'edit'); ?>
		<?php echo $form->textField($model,'edit'); ?>
		<?php echo $form->error($model,'edit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'del'); ?>
		<?php echo $form->textField($model,'del'); ?>
		<?php echo $form->error($model,'del'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->