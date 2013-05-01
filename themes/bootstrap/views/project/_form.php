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
 * Copyright (C) 2009 - 2013 Bugitor Team
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
<div class="form row-fluid">
	<?php
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	            'id' => 'project-form',
			    'action' => array('update', 'id' => $model->id),
	            'type' => 'horizontal',
	            'enableAjaxValidation' => false,
	        ));
	?>

	<?php echo $form->errorSummary($model); ?>

	<!-- name -->
	<?php echo $form->textFieldRow($model, 'name',
		array(
			'class' => 'span8',
			)
	); ?>
	<?php echo $form->error($model, 'name'); ?>

	<!-- tagline -->
	<?php echo $form->textFieldRow($model, 'tagline',
		array(
            'class' => 'span8',
		)
	); ?>
	<?php echo $form->error($model, 'tagline'); ?>

	<!-- description -->
	<?php echo $form->markitupRow($model, 'description', array(
        'class' => 'span8',
    )); ?>
	<?php echo $form->error($model, 'description'); ?>

	<!-- identifier -->
	<?php if(!$model->isNewRecord) :?>
		<?php echo $form->textFieldRow($model, 'identifier',
			array(
                'class' => 'span8',
				'disabled' => true,
			)
		); ?>
		<?php echo $form->error($model,'identifier'); ?>
    <?php endif; ?>

	<!-- home page -->
	<?php echo $form->textFieldRow($model, 'homepage',
		array(
            'class' => 'span8',
			'maxlength' => 255,
		)
	); ?>
	<?php echo $form->error($model, 'homepage'); ?>

	<!-- public -->
	<?php echo $form->checkBoxRow($model, 'public'); ?>
	<?php echo $form->error($model, 'public'); ?>

	<!-- buttons -->
    <div class="controls">
        <?php $this->widget('bootstrap.widgets.TbButton',
        	array(
        		'buttonType' => 'submit',
        		'type' => 'primary',
        		'label' => $model->isNewRecord ? 'Create' : 'Save',
        	)
        ); ?>
        <?php $this->widget('bootstrap.widgets.TbButton',
        	array(
        		'buttonType' => 'link',
        		'label' => 'Cancel',
        	)
        ); ?>
    </div>
<?php $this->endWidget(); ?>

</div><!-- form -->
