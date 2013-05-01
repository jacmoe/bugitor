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
<?php if (!$model->isNewRecord) : ?>
    <div class="alert alert-warning">To edit subject or description you need to click the Toggle Subject/Description link to make it visible.</div>
<?php endif; ?> <!-- if is new record //-->
<div class="issue row-fluid">
    <fieldset id="subject_description_fieldset" class="collapsible collapsed">
        <?php if (!$model->isNewRecord) : ?>
            <legend class="alt" onclick="$('#description_row').toggle();$('#subject_row').toggle();$('#subject_description_fieldset').toggleClass('collapsed')">
                <small>Toggle Subject/Description</small>
            </legend>
        <?php endif; ?> <!-- if is new record //-->
        <div class="form">
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                        'id' => 'issue-form',
                        'type' => 'horizontal',
                        'enableAjaxValidation' => false,
                    ));
            ?>

<?php if (!$model->isNewRecord) : ?>
    <div id="subject_row" style="display: none;">
        <?php echo $form->textFieldRow($model, 'subject', array(
            Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true,
            'class' => 'span12',
            'maxlength' => 255)
        ); ?>
    </div>
<?php else : ?>
        <?php echo $form->textFieldRow($model, 'subject', array(
            'class' => 'span12',
            'maxlength' => 255)
        ); ?>
<?php endif; ?>

<div class="row-fluid">
    <div id="splitcontentleft" class="span6">
        <div class="row-fluid">
            <?php if ($model->isNewRecord) : ?>
                <?php echo $form->dropDownListRow($model, 'tracker_id', CHtml::listData(
                    Tracker::model()->findAll(), 'id', 'name'), array(
                        'class' => 'span12',
                        'selected' => 'Bug')
                ); ?>
            <?php else : ?>
                <?php echo $form->dropDownListRow($model, 'tracker_id', CHtml::listData(
                    Tracker::model()->findAll(), 'id', 'name'), array(
                        'class' => 'span12')
                ); ?>
            <?php endif; ?>
        </div>
        <div class="row-fluid">
            <?php if(Yii::app()->user->checkAccess('Issue.Delete')) : ?>
                <?php if ($model->isNewRecord) : ?>
                    <?php echo $form->dropDownListRow($model, 'status', array('swIssue/new' => 'New*'), array(
                        'class' => 'span12')
                ); ?>
                <?php else : ?>
                    <?php echo $form->dropDownListRow($model, 'status', SWHelper::nextStatuslistData($model), array(
                        'class' => 'span12',
                        Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true)
                    ); ?>
                <?php endif; ?>
            <?php else : ?>
                <?php if(isset($model->status)) : ?>
                    <?php echo $model->getStatusLabel($model->status); ?>
                <?php else : ?>
                    New*
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="row-fluid">
            <?php if ($model->isNewRecord) : ?>
                <?php echo $form->dropDownListRow($model, 'issue_priority_id', CHtml::listData(
                        IssuePriority::model()->findAll(array('order' => 'id')), 'id', 'name'), array(
                            'options' => array('2' => array(
                                'selected' => true)
                            ),
                            'class' => 'span12',
                        )
                ); ?>
            <?php else : ?>
                <?php echo $form->dropDownListRow($model, 'issue_priority_id', CHtml::listData(
                    IssuePriority::model()->findAll(array('order' => 'id')), 'id', 'name'), array(
                        'class' => 'span12')
                ); ?>
            <?php endif; ?>
        </div>
        <div class="row-fluid">
            <?php if(Yii::app()->user->checkAccess('Issue.Delete')) : ?>
                <?php echo $form->dropDownListRow($model, 'assigned_to', $this->getMemberSelectList(), array(
                    Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true,
                    'class' => 'span12',
                    'prompt' => '<None>')
                ); ?>
                <?php echo $form->error($model, 'assigned_to'); ?>
            <?php else : ?>
                <?php if(isset($model->assignedTo)) : ?>
                    <span><?php echo Bugitor::gravatar($model->assignedTo); ?></span>
                    <?php echo Bugitor::link_to_user($model->assignedTo); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div><!-- splitcontentleft -->

    <div id="splitcontentright" class="span6">
        <div class="row-fluid">
            <?php if(Yii::app()->user->checkAccess('Issue.Delete')) : ?>
                <?php echo $form->dropDownListRow($model, 'issue_category_id',
                    $this->getCategorySelectList(),
                    array(Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true,
                    'prompt' => '<None>',
                    'class' => 'span11')); ?>
            <?php else : ?>
                <?php if(isset($model->issueCategory)) : ?>
                        <div class="span11">
                            <?php echo $model->issueCategory->name; ?>
                        </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="row-fluid">
            <?php if(Yii::app()->user->checkAccess('Issue.Delete')) : ?>
                <?php if(isset($model->milestone)) : ?>
                    <?php echo $form->dropDownListRow($model, 'milestone_id', $this->getmilestoneSelectList(
                    Project::getProjectIdFromIdentifier($_GET['identifier']), true,
                    $model->milestone->id, $model->milestone->name .' : '. $model->milestone->title),
                    array(Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true,
                    'prompt' => '<None>',
                    'class' => 'span11')); ?>
                <?php else : ?>
                    <?php echo $form->dropDownListRow($model, 'milestone_id', $this->getmilestoneSelectList(
                    Project::getProjectIdFromIdentifier($_GET['identifier']), true),
                    array(Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true,
                    'prompt' => '<None>',
                    'class' => 'span11')); ?>
                <?php endif; ?>
            <?php else : ?>
                <?php if(isset($model->milestone)) : ?>
                        <div class="span11">
                            <?php echo $model->milestone->name; ?>
                        </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="row-fluid">
            <div class="span11">
                <?php if(Yii::app()->user->checkAccess('Issue.Delete')) : ?>
                    <div class="controls">
                    <?php if ((Yii::app()->user->checkAccess('Issue.Update')) && (!$model->isNewRecord)) : ?>
<?php
$sliderScript = <<< EOD
js:function(event, ui)
{
    if(ui.value > 0)
    {
        if( $("td.closed").length == 0)
        {
            $("<td class='closed'></td>").insertBefore("td").css("class", "progress");
        }
    } else {
        if( $("td.closed").length != 0)
        {
            $("td.closed").remove();
        }
    }
    if(ui.value < 100)
    {
        if( $("td.todo").length == 0)
        {
            $("<td class='todo'></td>").insertAfter("td").css("class", "progress");
        }
    } else {
        if( $("td.todo").length != 0)
        {
            $("td.todo").remove();
        }
    }
    $(".progress").prop("title", ui.value + "%");
    $("td.closed").css("width", ui.value + "%");
    $("td.todo").css("width", 100-ui.value + "%");
    $(".pourcent").html(ui.value + "%");
    $("#sliderval").val(ui.value);
}
EOD;

?>
                            <?php
                                $this->widget('zii.widgets.jui.CJuiSlider', array(
                                    'value' => $model->done_ratio,
                                    'id' => 'doneRatioSlider',
                                    // additional javascript options for the slider plugin
                                    'options' => array(
                                        'min' => 0,
                                        'max' => 100,
                                        'step' => 5,
                                        'slide' => $sliderScript,
                                    ),
                                    'htmlOptions' => array(
                                        'style' => 'height:8px;width:100%;',
                                        Yii::app()->user->checkAccess('Issue.Update') ? 'enabled' : 'disabled' => true,
                                    ),
                                ));
                            ?>
                    <?php endif; ?>
                    <?php echo $form->hiddenField($model, 'done_ratio', array(
                        'value' => $model->done_ratio,
                        'id' => 'sliderval'
                        )
                    ); ?>
                    <?php if(isset($model->done_ratio)) : ?>
                        <?php echo Bugitor::progress_bar($model->done_ratio, array(
                            'id' => 'done_ratio',
                            'class' => 'span11',
                            'width' => '100%',
                            'legend' => $model->done_ratio . '%')
                        ); ?>
                    </div>
                    <?php else : ?>
                        <?php echo Bugitor::progress_bar(0, array(
                            'id' => 'done_ratio',
                            'class' => 'span11',
                            'width' => '100%',
                            'legend' => $model->done_ratio . '%')
                        ); ?>
                    </div>
                    <?php endif; ?>
                <?php else : ?>
                    <?php if(isset($model->done_ratio)) : ?>
                        <div class="controls">
                            <?php echo Bugitor::progress_bar($model->done_ratio, array(
                                'class' => 'span11',
                                'width' => '100%',
                                'legend' => $model->done_ratio . '%')
                            ); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div><!-- splitcontentright -->
</div>


<?php if (!$model->isNewRecord) : ?>
    <div class="row-fluid" id="description_row" style="display: none;">
        <div class="span12">
        <?php if (Yii::app()->user->checkAccess('Issue.Update')) : ?>
            <?php echo $form->markitupRow($model, 'description'); ?>
        <?php else : ?>
            <?php echo $form->textAreaRow($model, 'description', array(
                'disabled' => true,
                'style' => 'height:150px;width:100%;')
            ); ?>
        <?php endif; ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="control-group">
                <label for="Comment" class="control-label">Comment:</label>
                <div class="controls">
                    <?php $this->widget('ext.yiiext.widgets.markitup.EMarkitupWidget', array(
                            'name' => 'Comment',
                            'htmlOptions'=>array('class' => 'span12', 'width' => '100%', 'height' => '400px',)
                    ))?>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="row-fluid">
        <div class="span12">
            <?php echo $form->markitupRow($model, 'description'); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>
    </div>
<?php endif; ?>

<div class="row-fluid">
    <?php if ($model->isNewRecord) : ?>
        <?php echo $form->hiddenField($model, 'user_id', array('value' => Yii::app()->getModule('user')->user()->id)); ?>
    <?php else: ?>
        <?php echo $form->hiddenField($model, 'user_id', array('value' => $model->user_id)); ?>
    <?php endif; ?>
    <?php if(!Yii::app()->user->checkAccess('Issue.Delete')) : ?>
        <?php if ($model->isNewRecord) : ?>
            <?php echo $form->hiddenField($model, 'status', array('value' => 'swIssue/new')); ?>
        <?php else: ?>
            <?php echo $form->hiddenField($model, 'status', array('value' => $model->status)); ?>
        <?php endif; ?>
        <?php echo $form->hiddenField($model, 'issue_category_id', array('value' => $model->issue_category_id)); ?>
        <?php echo $form->hiddenField($model, 'assigned_to', array('value' => $model->assigned_to)); ?>
        <?php echo $form->hiddenField($model, 'milestone_id', array('value' => $model->milestone_id)); ?>
        <?php echo $form->hiddenField($model, 'done_ratio', array('value' => $model->done_ratio)); ?>
    <?php endif; ?>
    <?php echo $form->hiddenField($model, 'project_id', array('value' => Project::getProjectIdFromIdentifier($_GET['identifier']))); ?>
</div>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type' => 'primary', 'label'=> $model->isNewRecord ? 'Create' : 'Save')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'link', 'label'=>'Cancel', 'url' => Yii::app()->request->getUrlReferrer())); ?>


            <?php $this->endWidget(); ?><!-- form widget //-->
        </div><!-- form //-->
    </fieldset>
</div>
