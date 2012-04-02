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
 * Copyright (C) 2009 - 2012 Bugitor Team
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
<?php
?>
<h3>Update Settings</h3>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<?php echo $form->errorSummary($model); ?>
    <div class="row">
		<?php echo $form->labelEx($model,'pagesize'); ?>
		<?php echo $form->textField($model,'pagesize',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'pagesize'); ?>
	</div>
    <div class="row">
		<?php echo $form->labelEx($model,'hg_executable'); ?>
		<?php echo $form->textField($model,'hg_executable',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'hg_executable'); ?>
	</div>
    <div class="row">
        <?php echo $form->labelEx($model,'git_executable'); ?>
        <?php echo $form->textField($model,'git_executable',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'git_executable'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'svn_executable'); ?>
        <?php echo $form->textField($model,'svn_executable',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'svn_executable'); ?>
    </div>
    <div class="row">
		<?php echo $form->labelEx($model,'default_scm'); ?>
		<?php echo $form->textField($model,'default_scm',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'default_scm'); ?>
	</div>
    <div class="row">
		<?php echo $form->labelEx($model,'python_path'); ?>
		<?php echo $form->textField($model,'python_path',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'python_path'); ?>
	</div>
    <div class="row">
		<?php echo $form->labelEx($model,'default_timezone'); ?>
		<?php echo $form->textField($model,'default_timezone',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'default_timezone'); ?>
	</div>
    <div class="row">
		<?php echo $form->labelEx($model,'hostname'); ?>
		<?php echo $form->textField($model,'hostname',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'hostname'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($model,'logging_enabled'); ?>
		<?php echo $form->checkBox($model,'logging_enabled'); ?>
		<?php echo $form->error($model,'logging_enabled'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Update'); ?>
                <?php echo CHtml::Button('Cancel',array('submit' => Yii::app()->request->getUrlReferrer()));?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
