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
$this->pageTitle = $model->project->name . ' - Comment on ' . $model->tracker->name . ' #' . $model->id . ': ' . $model->subject . ' - ' . Yii::app()->name ;
?>
<h3 class="editissue">Comment on Issue <?php echo $model->id; ?> : <i>"<?php echo $model->subject; ?>"</i></h3>

<div class="issue">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'issue-form',
                        'enableAjaxValidation' => false,
                    ));
            ?>
                <p class="note">Fields with <span class="required">*</span> are required.</p>
                <?php echo $form->errorSummary($model); ?>
                <?php echo Bugitor::gravatar($model->user); ?>
                <h3><?php echo $model->subject; ?></h3>
                Added by <?php echo Bugitor::link_to_user($model->user); ?> <?php echo Time::timeAgoInWords($model->created); ?>.
                <?php if(isset($model->updatedBy)) echo '  Updated by '.Bugitor::link_to_user($model->updatedBy) .' '. Time::timeAgoInWords($model->modified); ?>
                <hr/>
                <table width="95%">
                    <tbody><tr>
                        <td style="width: 15%;" class="status"><b>Status:</b></td>
                        <td style="width: 35%;" class="status">
                            <?php if(isset($model->status)) : ?>
                                <?php echo $model->getStatusLabel($model->status); ?>
                            <?php endif; ?>
                        </td>
                        <td class="category"><b>Category:</b></td>
                        <td>
                            <?php if(isset($model->issueCategory)) : ?>
                                    <?php echo $model->issueCategory->name; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="priority"><b>Priority:</b></td>
                        <td class="priority">
                            <?php if(isset($model->issuePriority)) : ?>
                                    <?php echo $model->issuePriority->name; ?>
                            <?php endif; ?>
                        </td>
                        <td class="fixed-version"><b>Version:</b></td>
                        <td>
                            <?php if(isset($model->version)) : ?>
                                    <?php echo $model->version->name; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="assigned-to"><b>Assigned to:</b></td>
                        <td>
                            <?php if(isset($model->assignedTo)) : ?>
                                <span><?php echo Bugitor::gravatar($model->assignedTo); ?></span>
                                <?php echo Bugitor::link_to_user($model->assignedTo); ?>
                            <?php endif; ?>
                        </td>
                        <td class="progress"><b>% Done:</b></td>
                        <td class="progress">
                            <?php if(isset($model->done_ratio)) : ?>
                                <?php echo Bugitor::progress_bar($model->done_ratio, array('width'=>'80px', 'legend'=>$model->done_ratio.'%'));?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody></table>
                <hr/>
                <b>Description:</b><br/>
                <?php echo Yii::app()->textile->textilize($model->description); ?>
                <hr/>
                <div class="row">
                    <?php $this->widget('ext.yiiext.widgets.markitup.EMarkitupWidget', array(
                            'name' => 'Comment',
                            'htmlOptions'=>array('style'=>'height:150px;')
                    ))?>
                </div>
                <div class="row buttons">
                    <?php echo CHtml::submitButton('Save'); ?>
                    <?php echo CHtml::Button('Cancel', array('submit' => Yii::app()->request->getUrlReferrer())); ?>
                </div>
                <div class="row">
                    <?php echo $form->hiddenField($model, 'tracker_id', array('value' => $model->tracker_id)); ?>
                    <?php echo $form->hiddenField($model, 'subject', array('value' => $model->subject)); ?>
                    <?php echo $form->hiddenField($model, 'description', array('value' => $model->description)); ?>
                    <?php echo $form->hiddenField($model, 'issue_priority_id', array('value' => $model->issue_priority_id)); ?>
                    <?php echo $form->hiddenField($model, 'status', array('value' => $model->status)); ?>
                    <?php echo $form->hiddenField($model, 'issue_category_id', array('value' => $model->issue_category_id)); ?>
                    <?php echo $form->hiddenField($model, 'assigned_to', array('value' => $model->assigned_to)); ?>
                    <?php echo $form->hiddenField($model, 'version_id', array('value' => $model->version_id)); ?>
                    <?php echo $form->hiddenField($model, 'done_ratio', array('value' => $model->done_ratio)); ?>
                    <?php echo $form->hiddenField($model, 'user_id', array('value' => $model->user_id)); ?>
                    <?php echo $form->hiddenField($model, 'project_id', array('value' => Project::getProjectIdFromIdentifier($_GET['identifier']))); ?>
                </div>
            <?php $this->endWidget(); ?><!-- form widget //-->
</div><!-- form //-->
<div class="scroll">
<?php if($model->commentCount>=1): ?>
    <?php $this->renderPartial('_comments',array('comments'=>$model->comments,)); ?>
<?php endif; ?>
</div>