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
<?php
$interval = array(
    'days' => 'Days',
    'months' => 'Months',
    'years' => 'Years',
);
?>
<h3>Postpone Milestones</h3>
<div class="form">
    <div class="notice">Hint: Enter a negative number to postpone backwards.<br/>
    For instance, '-2 months' will subtract 2 months from all milestone effective dates thus making them due 2 months earlier.</div>
    <table>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<?php echo $form->errorSummary($model); ?>
        <tr>
            <td>
                <?php echo $form->error($model,'postpone'); ?>
		<?php echo $form->labelEx($model,'postpone'); ?>
            </td>
            <td>
		<?php echo $form->textField($model,'postpone',array('size'=>60,'maxlength'=>255)); ?>
            </td>
        </tr>
        <tr>
            <td>
		<?php echo $form->labelEx($model,'interval'); ?>
		<?php echo $form->error($model,'interval'); ?>
            </td>
            <td>
                <?php echo CHtml::activeDropdownList($model, 'interval', $interval, array('options' => array('months'=>array('selected'=>true)))); ?>
            </td>
        </tr>
        <tr>
            <td>
                <br/>
		<?php echo CHtml::submitButton('Postpone'); ?>
            </td>
            <td>
                <br/>
                <?php echo CHtml::Button('Cancel',array('submit' => Yii::app()->request->getUrlReferrer().'?tab=milestones'));?>
            </td>
        </tr>
    </table>
<?php $this->endWidget(); ?>
</div><!-- form -->
