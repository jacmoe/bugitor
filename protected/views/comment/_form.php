<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'comment-form',
'enableAjaxValidation'=>false,
)); ?>
<p class="note">Fields with <span class="required">*</span> are
required.</p>
<?php echo $form->errorSummary($model); ?>
<div class="row">
<?php echo $form->labelEx($model,'content'); ?>
<?php $this->widget('ext.yiiext.widgets.markitup.EMarkitupWidget', array(
        // you can either use it for model attribute
        'model' => $model,
        'attribute' => 'content',
        // or just for input field
        //'name' => 'my_input_name',
))?>
<?php echo $form->error($model,'content'); ?>
</div>
<div class="row buttons">
<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' :
'Save'); ?>
<?php echo CHtml::Button('Cancel',array('submit' => CHttpRequest::getUrlReferrer()));?>
</div>
<?php $this->endWidget(); ?>
</div>