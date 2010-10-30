<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'issue-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'tracker_id'); ?>
                <?php echo $form->dropDownList($model, 'tracker_id', CHtml::listData(
                Tracker::model()->findAll(), 'id', 'name'),array('prompt' => '<Select>')); ?>
		<?php echo $form->error($model,'tracker_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'project_id'); ?>
                <?php echo $form->dropDownList($model, 'project_id', CHtml::listData(
                Project::model()->findAll(), 'id', 'name'),array('prompt' => '<Select>')); ?>
		<?php echo $form->error($model,'project_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php $this->widget('ext.yiiext.widgets.markitup.EMarkitupWidget', array(
			// you can either use it for model attribute
			'model' => $model,
			'attribute' => 'description',
			// or just for input field
			//'name' => 'my_input_name',
		))?>
		<?php //echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'due_date'); ?>
		<?php echo $form->textField($model,'due_date'); ?>
		<?php echo $form->error($model,'due_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'issue_category_id'); ?>
		<?php echo $form->textField($model,'issue_category_id'); ?>
		<?php echo $form->error($model,'issue_category_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
                <?php echo $form->dropDownList($model, 'user_id', CHtml::listData(
                User::model()->findAll(), 'id', 'username'),array('prompt' => '<Select>')); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'issue_priority_id'); ?>
                <?php echo $form->dropDownList($model, 'issue_priority_id', CHtml::listData(
                IssuePriority::model()->findAll(), 'id', 'name'),array('prompt' => '<Select>')); ?>
		<?php echo $form->error($model,'issue_priority_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'version_id'); ?>
		<?php echo $form->textField($model,'version_id'); ?>
		<?php echo $form->error($model,'version_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'assigned_to'); ?>
                <?php echo $form->dropDownList($model, 'assigned_to', CHtml::listData(
                User::model()->findAll(), 'id', 'username'),array('prompt' => '<None>')); ?>
		<?php echo $form->error($model,'assigned_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'modified'); ?>
		<?php echo $form->textField($model,'modified'); ?>
		<?php echo $form->error($model,'modified'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_date'); ?>
		<?php echo $form->textField($model,'start_date'); ?>
		<?php echo $form->error($model,'start_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'done_ratio'); ?>
		<?php echo $form->textField($model,'done_ratio'); ?>
		<?php echo $form->error($model,'done_ratio'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',SWHelper::nextStatuslistData($model)); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'closed'); ?>
		<?php echo $form->textField($model,'closed'); ?>
		<?php echo $form->error($model,'closed'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->