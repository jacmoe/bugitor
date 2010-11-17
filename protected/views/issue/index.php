<?php
    $this->pageTitle = isset($_GET['projectname']) ? $_GET['projectname'] . '- Issues - ' . Yii::app()->name : Yii::app()->name . ' - Issues';
    $pageSize=Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);
?>
<h3 class="issues">Issues</h3>
<?php echo CHtml::form('issues','get', array('class' => 'floatrightup')); ?>
Show:
<?php echo CHtml::dropDownList('issueFilter',
    isset($_GET['issueFilter'])?(int)$_GET['issueFilter']:1,
    $issueFilter,
    array('empty'=>'All Issues', 'submit'=>'')); ?>
<?php echo CHtml::endForm(); ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'issue-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'selectableRows'=>2,
        'pager' => array('class' => 'CustomLinkPager'),
        'columns'=>array(
                /*array(
                    'class'=>'CCheckBoxColumn',
                    'id'=>'selectedItems'
                ),*/
                array(
                    'name' => 'id',
                    'id'=>'selectedItems',
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
                    'value' => 'Bugitor::namedImage($data->swGetStatus()->getLabel())',
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
                    'value' => '(($data->modified)?Time::timeAgoInWords($data->modified):"")',
                    'htmlOptions'=>array('width'=>'15%'),
                ),
                array(
                    'name' => 'user_id',
                    'header' => 'Author',
                    'type' => 'raw',
                    'value' => 'Bugitor::gravatar($data->user->email,16,$data->user->username)',
                    'filter' => $this->getUserFilter(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'assigned_to',
                    'header' => 'Owner',
                    'type' => 'raw',
                    'value' => '(($data->assignedTo)?Bugitor::gravatar($data->assignedTo->email,16,$data->assignedTo->username):"")',
                    'filter' => $this->getUserFilter(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'done_ratio',
                    'header' => '% Done',
                    'type' => 'raw',
                    'filter' => '',
                    'value' => '(($data->done_ratio)?Bugitor::progress_bar($data->done_ratio, array("width"=>"100%")):"")',
                    'htmlOptions'=>array('width'=>'6%','class'=>'progress'),
                ),
                array(
                    'name' => 'version_id',
                    'header' => 'Version',
                    'value' => '(($data->version)?$data->version->name:"")',
                    'filter' => $this->getVersionFilter(),
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
                        'header'=>CHtml::dropDownList('pageSize',$pageSize,array(10=>10,20=>20,50=>50,100=>100),array(
                            // change 'user-grid' to the actual id of your grid!!
                            'id' => Yii::app()->config->get('defaultPagesize'),
                            'onchange'=>"$.fn.yiiGridView.update('issue-grid',{ data:{pageSize: $(this).val() }})",
                        )),
		),
	),
)); ?>
<div class="box" style="width:350px;">
<fieldset class="collapsible">
    <legend>Quick Admin</legend>
<?php echo CHtml::dropDownList('versionDrop',
    0,
    $this->getVersionSelectList(),
    array('empty'=>'No Version')); ?>
&nbsp;|-&gt;&nbsp;<?php echo CHtml::ajaxLink("Set Version",
        $this->createUrl('reqTest03'),
        array("type" => "post",
            "data" => "js:{ids:$.fn.yiiGridView.getSelection('issue-grid'),val:$('#versionDrop').val(),type:'version'}"
        ), array("onClick" => "js:{location.reload()}")); ?>
<br/>
<?php echo CHtml::dropDownList('priorityDrop',
    0,
    $this->getPrioritySelectList()); ?>
&nbsp;|-&gt;&nbsp;<?php echo CHtml::ajaxLink("Set Priority",
        $this->createUrl('reqTest03'),
        array("type" => "post",
            "data" => "js:{ids:$.fn.yiiGridView.getSelection('issue-grid'),val:$('#priorityDrop').val(),type:'priority'}"
        ), array("onClick" => "js:{location.reload()}")); ?>
<br/>
<?php echo CHtml::dropDownList('categoryDrop',
    0,
    $this->getCategorySelectList(),
    array('empty'=>'No Category')); ?>
&nbsp;|-&gt;&nbsp;<?php echo CHtml::ajaxLink("Set Category",
        $this->createUrl('reqTest03'),
        array("type" => "post",
            "data" => "js:{ids:$.fn.yiiGridView.getSelection('issue-grid'),val:$('#categoryDrop').val(),type:'category'}"
        ), array("onClick" => "js:{location.reload()}")); ?>
</fieldset>
</div>