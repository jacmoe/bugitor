<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'member-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
                <?php echo $form->dropDownList($model, 'user_id', Project::getNonMembersList(),array('prompt' => '<None>')); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'role'); ?>
		<?php echo $form->dropDownList($model,'role', Project::getUserRoleOptions()); ?>
		<?php echo $form->error($model,'role'); ?>
	</div>
        <?php echo $form->hiddenField($model,'project_id', array('value' => Project::getProjectIdFromIdentifier($_GET['identifier']))); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->