<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2011 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'milestone-form',
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
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
        <div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php $this->widget('ext.yiiext.widgets.markitup.EMarkitupWidget', array(
			'model' => $model,
			'attribute' => 'description',
                        'settings' => 'textile',
                        'htmlOptions'=>array('style'=>'height:150px;')
		))?>
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
                <?php echo CHtml::Button('Cancel',array('submit' => Yii::app()->request->getUrlReferrer()));?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->