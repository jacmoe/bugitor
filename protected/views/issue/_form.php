<div class="issue">
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'issue-form',
	'enableAjaxValidation'=>false,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php echo $form->errorSummary($model); ?>
        <div class="halfsplitcontentleft">
	<div class="row">
		<?php echo $form->labelEx($model,'tracker_id'); ?>
                <?php echo $form->dropDownList($model, 'tracker_id', CHtml::listData(
                Tracker::model()->findAll(), 'id', 'name'),array('selected' => 'Bug')); ?>
		<?php echo $form->error($model,'tracker_id'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>112,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php $this->widget('ext.yiiext.widgets.markitup.EMarkitupWidget', array(
			'model' => $model,
			'attribute' => 'description',
		))?>
		<?php echo $form->error($model,'description'); ?>
	</div>
        </div>
        <div class="halfsplitcontentright">
            <div class="row">
                    <?php echo $form->labelEx($model,'issue_priority_id'); ?>
                    <?php echo $form->dropDownList($model, 'issue_priority_id', CHtml::listData(
                    IssuePriority::model()->findAll(array('order'=>'id')), 'id', 'name'), array('options' => array('2'=>array('selected'=>true)))); ?>
                    <?php echo $form->error($model,'issue_priority_id'); ?>
            </div>
            <div class="row">
                    <?php echo $form->labelEx($model,'status'); ?>
                    <?php if($model->isNewRecord) : ?>
                        <?php echo $form->dropDownList($model,'status',array('swIssue/new' => 'New*')); ?>
                    <?php else : ?>
                        <?php echo $form->dropDownList($model,'status',SWHelper::nextStatuslistData($model)); ?>
                    <?php endif; ?>
                    <?php echo $form->error($model,'status'); ?>
            </div>
            <div class="row">
                    <?php echo $form->labelEx($model,'issue_category_id'); ?>
                    <?php echo $form->textField($model,'issue_category_id'); ?>
                    <?php echo $form->error($model,'issue_category_id'); ?>
            </div>
            <div class="row">
                    <?php echo $form->labelEx($model,'assigned_to'); ?>
                    <?php echo $form->dropDownList($model, 'assigned_to', CHtml::listData(
                    User::model()->findAll(), 'id', 'username'),array('prompt' => '<None>')); ?>
                    <?php echo $form->error($model,'assigned_to'); ?>
            </div>
            <div class="row">
                    <?php echo $form->labelEx($model,'version_id'); ?>
                    <?php echo $form->textField($model,'version_id'); ?>
                    <?php echo $form->error($model,'version_id'); ?>
            </div>
	<div class="row">
		<?php echo $form->labelEx($model,'done_ratio'); ?>
		<?php echo $form->textField($model,'done_ratio'); ?>
		<?php echo $form->error($model,'done_ratio'); ?>
	</div>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
        </div>
	<div class="row">
		<?php echo $form->hiddenField($model,'user_id', array('value' => Yii::app()->getModule('user')->user()->id)); ?>
		<?php echo $form->hiddenField($model,'project_id', array('value' => Project::getProjectIdFromIdentifier($_GET['identifier']))); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>