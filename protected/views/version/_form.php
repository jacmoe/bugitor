<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'version-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'effective_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker',
                        array(
                                'model'=>$model,
                                'attribute'=>'effective_date',
                                'options' => array(
                                        // how to change the input format? see http://docs.jquery.com/UI/Datepicker/formatDate
                                        'dateFormat'=>'yy-mm-dd',
                                        // user will be able to change month and year
                                        'changeMonth' => 'true',
                                        'changeYear' => 'true',
                                        // shows the button panel under the calendar (buttons like "today" and "done")
                                        'showButtonPanel' => 'true',
                                        // this is useful to allow only valid chars in the input field, according to dateFormat
                                        'constrainInput' => 'true',
                                        // speed at which the datepicker appears, time in ms or "slow", "normal" or "fast"
                                        'duration'=>'fast',
                                        // animation effect, see http://docs.jquery.com/UI/Effects
                                        'showAnim' =>'slide',
                                ),
                        )
                ); ?>
		<?php echo $form->error($model,'effective_date'); ?>
	</div>
        <?php echo $form->hiddenField($model,'project_id', array('value' => Project::getProjectIdFromIdentifier($_GET['identifier']))); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->