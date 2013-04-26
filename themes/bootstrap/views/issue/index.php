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
                        'htmlOptions' => array('style' => 'width:120px !important', 'class' => 'pull-right'),
                    ));
                }
            }
            ?>
</div>
<h3 class="issues-icon">Issues</h3>
<?php echo CHtml::form('issues','get', array('class' => 'floatrightup')); ?>
<?php echo CHtml::dropDownList('issueFilter',
    isset($_GET['issueFilter'])?(int)$_GET['issueFilter']:1,
    $issueFilter,
    array('empty'=>'All Issues', 'submit'=>'', 'style' => 'width:120px !important',
        'class' => 'pull-right',
)); ?>
<?php echo CHtml::endForm(); ?>
<?php
        $script = <<<EOD
	var format;
	var localise = function () {
            var theTime = this.title;
            this.t = this.title;
            this.title = jQuery.localtime.toLocalTime(theTime, format);	
            jQuery(this).text(jQuery.timeago(jQuery(this).text()));
            //jQuery(this).text(humaneDate(jQuery(this).text()));
	};
	var formats = jQuery.localtime.getFormat();
	var cssClass;
	for (cssClass in formats) {
		if (formats.hasOwnProperty(cssClass)) {
			format = formats[cssClass];
			jQuery("acronym." + cssClass).each(localise);

		}
	}
EOD;
?>
<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'=>'issue-grid',
    'fixedHeader' => true,
    'enableHistory' => true,
    'headerOffset' => 40, // 40px is the height of the main navigation at bootstrap
    'type' => 'striped bordered',
	'dataProvider'=>$model->search(),
	'afterAjaxUpdate'=>"function() { $script }",
    'filter'=>$model,
    'sortableRows'=>true,
    'pager' => array('class' => 'CustomLinkPager'),
    'responsiveTable' => true,
    'template' => "{items}{pager}",
    'enablePagination' => true,
    'columns'=>array(
            array(
                'name' => 'id',
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
                'htmlOptions'=>array('width'=>'35%'),
            ),
            array(
                'name' => 'modified',
                'header' => 'Last Modified',
                'type' => 'raw',
                'filter' => '',
                'value' => '(($data->modified)?((isset($data->updatedBy)) ? Bugitor::gravatar($data->updatedBy,16) : Bugitor::gravatar($data->user,16)). " " . Bugitor::timeAgoInWords($data->modified):"")',
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
            'htmlOptions'=>array('width'=>'15'),
    		'class'=>'bootstrap.widgets.TbButtonColumn',
                        'header'=>CHtml::dropDownList('pageSize',Yii::app()->user->getState('pageSize') ? Yii::app()->user->getState('pageSize'):'20' ,array(10=>10,20=>20,50=>50,100=>100),array(
                            'style' => 'width:60px !important',
                            'id' => Yii::app()->config->get('defaultPagesize'),
                            'onchange'=>"$.fn.yiiGridView.update('issue-grid',{ data:{pageSize: $(this).val() }})",
                        )),
		),
	),
)); ?>
