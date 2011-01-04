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
 * Copyright (C) 2009 - 2010 Bugitor Team
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
<div class="issue">
    <fieldset id="subject_description_fieldset" class="collapsible collapsed">
        <?php if (!$model->isNewRecord) : ?>
            <legend class="alt" onclick="$('#description_row').toggle();$('#subject_row').toggle();$('#subject_description_fieldset').toggleClass('collapsed')">
                <small>Toggle Subject/Description</small>
            </legend>
        <?php endif; ?> <!-- if is new record //-->
        <div class="form">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'issue-form',
                        'enableAjaxValidation' => false,
                    ));
            ?>
            <div class="halfsplitcontentleft">
                <p class="note">Fields with <span class="required">*</span> are required.</p>
                <?php echo $form->errorSummary($model); ?>
                <div class="row">
                    <?php echo $form->labelEx($model, 'tracker_id'); ?>
                    <?php if ($model->isNewRecord) : ?>
                        <?php echo $form->dropDownList($model, 'tracker_id', CHtml::listData(
                                        Tracker::model()->findAll(), 'id', 'name'), array('selected' => 'Bug')); ?>
                    <?php else : ?>
                        <?php echo $form->dropDownList($model, 'tracker_id', CHtml::listData(
                                        Tracker::model()->findAll(), 'id', 'name')); ?>
                    <?php endif; ?>
                    <?php echo $form->error($model, 'tracker_id'); ?>
                </div>
                <?php if (!$model->isNewRecord) : ?>
                    <div class="row" id="subject_row" style="display: none;">
                        <?php echo $form->labelEx($model, 'subject'); ?>
                        <?php echo $form->textField($model, 'subject', array(Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true, 'size' => 112, 'maxlength' => 255)); ?>
                        <?php echo $form->error($model, 'subject'); ?>
                    </div>
                    <div class="row" id="description_row" style="display: none;">
                        <?php echo $form->labelEx($model, 'description'); ?>
                        <?php if (Yii::app()->user->checkAccess('Issue.Update')) : ?>
                            <?php
                                    $this->widget('ext.yiiext.widgets.markitup.EMarkitupWidget', array(
                                        'model' => $model,
                                        'attribute' => 'description',
                                        'htmlOptions' => array('style' => 'height:150px;')
                                    )) ?>
                        <?php else : ?>
                            <?php echo $form->textArea($model, 'description', array('disabled' => true, 'style' => 'height:150px;width:98%;')); ?>
                        <?php endif; ?>
                        <?php echo $form->error($model, 'description'); ?>
                    </div>
                    <div class="row">
                        <?php //$this->renderPartial('/comment/_form', array('model' => $comment,)); ?>
                        <?php $this->widget('ext.yiiext.widgets.markitup.EMarkitupWidget', array(
                                'name' => 'Comment',
                                'htmlOptions'=>array('style'=>'height:150px;')
                        ))?>
                    </div>
                <?php else : ?>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'subject'); ?>
                        <?php echo $form->textField($model, 'subject', array('size' => 112, 'maxlength' => 255)); ?>
                        <?php echo $form->error($model, 'subject'); ?>
                    </div>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'description'); ?>
                        <?php
                            $this->widget('ext.yiiext.widgets.markitup.EMarkitupWidget', array(
                                'model' => $model,
                                'attribute' => 'description',
                                'htmlOptions' => array('style' => 'height:150px;')
                            ))
                        ?>
                        <?php echo $form->error($model, 'description'); ?>
                    </div>
                <?php endif; ?>
                <div class="row buttons">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
                    <?php echo CHtml::Button('Cancel', array('submit' => Yii::app()->request->getUrlReferrer())); ?>
                </div>
            </div><!-- content left //-->
            <div class="halfsplitcontentright">
                <div class="row">
                    <?php echo $form->labelEx($model, 'issue_priority_id'); ?>
                    <?php if ($model->isNewRecord) : ?>
                        <?php
                            echo $form->dropDownList($model,
                                    'issue_priority_id',
                                    CHtml::listData(IssuePriority::model()->findAll(array('order' => 'id')), 'id', 'name'),
                                    array(Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true, 'options' => array('2' => array('selected' => true))));
                        ?>
                    <?php else : ?>
                        <?php
                            echo $form->dropDownList($model,
                                    'issue_priority_id',
                                    CHtml::listData(IssuePriority::model()->findAll(array('order' => 'id')), 'id', 'name'),
                                    array(Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true));
                        ?>
                    <?php endif; ?>
                    <?php echo $form->error($model, 'issue_priority_id'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'status'); ?>
                    <?php if ($model->isNewRecord) : ?>
                        <?php echo $form->dropDownList($model, 'status', array('swIssue/new' => 'New*'), array(Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true)); ?>
                    <?php else : ?>
                        <?php echo $form->dropDownList($model, 'status', SWHelper::nextStatuslistData($model), array(Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true)); ?>
                    <?php endif; ?>
                    <?php echo $form->error($model, 'status'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'issue_category_id'); ?>
                    <?php echo $form->dropDownList($model, 'issue_category_id', $this->getCategorySelectList(), array(Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true, 'prompt' => '<None>')); ?>
                    <?php echo $form->error($model, 'issue_category_id'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'assigned_to'); ?>
                    <?php echo $form->dropDownList($model, 'assigned_to', $this->getUserSelectList(), array(Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true, 'prompt' => '<None>')); ?>
                    <?php echo $form->error($model, 'assigned_to'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'version_id'); ?>
                    <?php echo $form->dropDownList($model, 'version_id', $this->getVersionSelectList(), array(Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true, 'prompt' => '<None>')); ?>
                    <?php echo $form->error($model, 'version_id'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'done_ratio'); ?>
                    <?php if ((Yii::app()->user->checkAccess('Issue.Update')) && (!$model->isNewRecord)) : ?>
                        <?php
                            $this->widget('zii.widgets.jui.CJuiSlider', array(
                                'value' => $model->done_ratio,
                                'id' => 'doneRatioSlider',
                                // additional javascript options for the slider plugin
                                'options' => array(
                                    'min' => 0,
                                    'max' => 100,
                                    'step' => 5,
                                    'slide' => 'js:function(event, ui) { $("#done_ratio").val(ui.value);}',
                                ),
                                'htmlOptions' => array(
                                    'style' => 'height:8px;width:144px;',
                                    Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true,
                                ),
                            ));
                        ?>
                    <?php endif; ?>
                    <?php echo $form->textField($model, 'done_ratio', array('id' => 'done_ratio', 'readonly' => true)); ?>
                    <?php echo $form->error($model, 'done_ratio'); ?>
                </div>
                <div class="row">
                    <?php if ($model->isNewRecord) : ?>
                        <?php echo $form->hiddenField($model, 'user_id', array('value' => Yii::app()->getModule('user')->user()->id)); ?>
                    <?php else: ?>
                        <?php echo $form->hiddenField($model, 'user_id', array('value' => $model->user_id)); ?>
                    <?php endif; ?>
                    <?php echo $form->hiddenField($model, 'project_id', array('value' => Project::getProjectIdFromIdentifier($_GET['identifier']))); ?>
                </div>
            </div><!-- content right //-->
            <?php $this->endWidget(); ?><!-- form widget //-->
        </div><!-- form //-->
    </fieldset>
</div>