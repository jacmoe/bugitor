<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'member-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
                <?php if($model->isNewRecord) :?>
                <?php echo $form->dropDownList($model, 'user_id', Project::getNonMembersList(),array('prompt' => '<None>')); ?>
                <?php else: ?>
		<?php echo CHtml::textField('the_user',$model->user->username,array('disabled' => true)); ?>
                <?php endif; ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'role'); ?>
		<?php echo $form->dropDownList($model,'role', $model->getUserRoleOptions()); ?>
		<?php echo $form->error($model,'role'); ?>
	</div>
        <?php echo $form->hiddenField($model,'project_id', array('value' => Project::getProjectIdFromIdentifier($_GET['identifier']))); ?>
	<?php if(!$model->isNewRecord) :?>
        <?php echo $form->hiddenField($model,'user_id',array('value' => $model->user_id)); ?>
        <?php endif; ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->