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
    $this->pageTitle = isset($_GET['projectname']) ? $_GET['projectname'] . '- Issues - ' . Yii::app()->name : Yii::app()->name . ' - Issues';
    $pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);
?>
<div class="contextual">
            <?php
            if (((Yii::app()->controller->id === 'project') || (Yii::app()->controller->id === 'issue')) && (isset($_GET['identifier']))) {
                if (('issue/view' !== $this->route) && ('issue/update' !== $this->route) && ('issue/create' !== $this->route)) {
                    $this->widget('DropDownRedirect', array(
                        'data' => Yii::app()->controller->getProjects(),
                        'url' => $this->createUrl($this->route, array_merge($_GET, array('identifier' => '__value__'))),
                        'select' => $_GET['identifier'], //the preselected value
                    ));
                }
            }
            ?>
</div>
<h3 class="issues">Issues</h3>
<?php echo CHtml::form('issues','get', array('class' => 'floatrightup')); ?>
<?php echo CHtml::dropDownList('issueFilter',
    isset($_GET['issueFilter'])?(int)$_GET['issueFilter']:1,
    $issueFilter,
    array('empty'=>'All Issues', 'submit'=>'')); ?>
<?php echo CHtml::endForm(); ?>
<?php $this->widget('ext.rrviews.RRGridView', array(
	'id'=>'issue-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'selectableRows'=>2,
        'pager' => array('class' => 'CustomLinkPager'),
        'columns'=>array(
                array(
                    'id'=>'selectedItems',
                    'class'=>'CCheckBoxColumn',
                ),
                array(
                    'name' => 'id',
                    //'id'=>'selectedItems',
                    'header' => 'Id',
                    'type' => 'raw',
                    'filter' => '',
                    'value' => 'CHtml::link(CHtml::encode($data->id),array("view","id"=>$data->id, "identifier"=>$data->project->identifier))',
                    'htmlOptions'=>array('width'=>'5'),
                    ),
                array(
                    'name' => 'tracker_id',
                    'header' => 'Type',
                    'type' => 'raw',
                    'value' => 'Bugitor::namedImage($data->tracker->name)',
                    'filter' => $this->getTrackerFilter(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'status',
                    'header' => 'Status',
                    'type' => 'raw',
                    'value' => 'Bugitor::namedImage($data->getStatusLabel($data->status))',
                    'filter' => SWHelper::allStatuslistData($model),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'issue_priority_id',
                    'header' => 'Priority',
                    'type' => 'raw',
                    'value' => 'Bugitor::namedImage($data->issuePriority->name)',
                    'filter' => $this->getPriorityFilter(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'subject',
                    'type' => 'raw',
                    'value' => 'CHtml::link(CHtml::encode($data->subject),array("view","id"=>$data->id, "identifier"=>$data->project->identifier),array("title" => $data->description))',
                    'htmlOptions'=>array('width'=>'25%'),
                ),
                array(
                    'name' => 'modified',
                    'header' => 'Last Modified',
                    'type' => 'raw',
                    'filter' => '',
                    'value' => '(($data->modified)?((isset($data->updatedBy)) ? Bugitor::gravatar($data->updatedBy,16) : Bugitor::gravatar($data->user,16)). " " . Time::timeAgoInWords($data->modified):"")',
                    'htmlOptions'=>array('width'=>'15%'),
                ),
                array(
                    'name' => 'user_id',
                    'header' => 'Author',
                    'type' => 'raw',
                    'value' => 'Bugitor::gravatar($data->user,16)',
                    'filter' => $this->getUserFilter(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'comment_count',
                    'header' => 'Comments',
                    'type' => 'raw',
                    'value' => '(($data->comment_count)?$data->comment_count:"")',
                    'filter' => '',
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'assigned_to',
                    'header' => 'Owner',
                    'type' => 'raw',
                    'value' => '(($data->assignedTo)?Bugitor::gravatar($data->assignedTo,16):"")',
                    'filter' => $this->getMemberFilter(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'done_ratio',
                    'header' => '% Done',
                    'type' => 'raw',
                    'filter' => '',
                    'value' => 'Bugitor::progress_bar($data->done_ratio, array("width"=>"100%"))',
                    'htmlOptions'=>array('width'=>'6%','class'=>'progress'),
                ),
                array(
                    'name' => 'milestone_id',
                    'header' => 'Milestone',
                    'type' => 'raw',
                    'value' => '(($data->milestone)?CHtml::tag("acronym", array("title" => $data->milestone->title), $data->milestone->name):"")',
                    'filter' => $this->getMilestoneFilter(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'issue_category_id',
                    'header' => 'Category',
                    'value' => '(($data->issueCategory)?$data->issueCategory->name:"")',
                    'filter' => $this->getCategoryFilter(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
		array(
			'class'=>'IssueButtonColumn',
                        'header'=>CHtml::dropDownList('pageSize',Yii::app()->user->getState('pageSize') ? Yii::app()->user->getState('pageSize'):'20' ,array(10=>10,20=>20,50=>50,100=>100),array(
                            // change 'user-grid' to the actual id of your grid!!
                            'id' => Yii::app()->config->get('defaultPagesize'),
                            'onchange'=>"$.fn.yiiGridView.update('issue-grid',{ data:{pageSize: $(this).val() }})",
                        )),
		),
	),
)); ?>
<?php if(Yii::app()->user->checkAccess('Issue.MassEdit')) : ?>
<div class="box" style="width:350px;">
    <fieldset id="quick_admin_fieldset" class="collapsible collapsed">
            <legend onclick="$('#quick_admin').toggle();$('#quick_admin_fieldset').toggleClass('collapsed')">
                Quick Admin
            </legend>
            <div id="quick_admin" style="display: none;">
            <?php
            echo CHtml::dropDownList('milestoneDrop',
                    0,
                    $this->getMilestoneSelectList($model->project_id),
                    array('empty' => 'No Milestone'));
            ?>
            &nbsp;|-&gt;&nbsp;<?php
            echo CHtml::ajaxLink("Set Milestone",
                    $this->createUrl('massEdit'),
                    array("type" => "post",
                        "data" => "js:{ids:$.fn.yiiGridView.getSelection('issue-grid'),val:$('#milestoneDrop').val(),type:'milestone'}"
                    ), array("onClick" => "js:{location.reload()}")); ?>
            <br/>
<?php
            echo CHtml::dropDownList('priorityDrop',
                    0,
                    $this->getPrioritySelectList()); ?>
            &nbsp;|-&gt;&nbsp;<?php
            echo CHtml::ajaxLink("Set Priority",
                    $this->createUrl('massEdit'),
                    array("type" => "post",
                        "data" => "js:{ids:$.fn.yiiGridView.getSelection('issue-grid'),val:$('#priorityDrop').val(),type:'priority'}"
                    ), array("onClick" => "js:{location.reload()}")); ?>
            <br/>
            <?php
            echo CHtml::dropDownList('categoryDrop',
                    0,
                    $this->getCategorySelectList(),
                    array('empty' => 'No Category'));
            ?>
            &nbsp;|-&gt;&nbsp;<?php
            echo CHtml::ajaxLink("Set Category",
                    $this->createUrl('massEdit'),
                    array("type" => "post",
                        "data" => "js:{ids:$.fn.yiiGridView.getSelection('issue-grid'),val:$('#categoryDrop').val(),type:'category'}"
                    ), array("onClick" => "js:{location.reload()}"));
            ?>
                    </div>
                </fieldset>
</div>
<?php endif; ?>
