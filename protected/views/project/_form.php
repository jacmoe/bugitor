<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-form',
        'action' => array('update', 'id' => $model->id),
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php $this->widget('ext.yiiext.widgets.markitup.EMarkitupWidget', array(
			'model' => $model,
			'attribute' => 'description',
                        'htmlOptions'=>array('style'=>'height:150px;')
		))?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<?php if(!$model->isNewRecord) :?>
        <div class="row">
		<?php echo $form->labelEx($model,'identifier'); ?>
		<?php echo $form->textField($model,'identifier',array('size'=>60,'maxlength'=>255,'disabled' => true)); ?>
		<?php echo $form->error($model,'identifier'); ?>
	</div>
        <?php endif; ?>
	
        <div class="row">
		<?php echo $form->labelEx($model,'homepage'); ?>
		<?php echo $form->textField($model,'homepage',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'homepage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'public'); ?>
		<?php echo $form->checkBox($model,'public'); ?>
		<?php echo $form->error($model,'public'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
                <?php echo CHtml::Button('Cancel',array('submit' => CHttpRequest::getUrlReferrer()));?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->