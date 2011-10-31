<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Registration");
$this->breadcrumbs=array(
	UserModule::t("Registration"),
);
?>

<?php if(Yii::app()->user->hasFlash('registration')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('registration'); ?>
</div>
<?php else: ?>

<div id="page group">
<?php echo CHtml::beginForm('','post',array('enctype'=>'multipart/form-data')); ?>
    <fieldset class="register">

    <h1><?php echo UserModule::t("Registration"); ?></h1>
	
	<?php echo CHtml::errorSummary($form); ?>
	<?php echo CHtml::errorSummary($profile); ?>
	
	<div class="field stacked">
	<?php echo CHtml::activeLabelEx($form,'username'); ?>
	<?php echo CHtml::activeTextField($form,'username'); ?>
	</div>
	
	<div class="field stacked">
	<?php echo CHtml::activeLabelEx($form,'password'); ?>
	<?php echo CHtml::activePasswordField($form,'password'); ?>
	<p class="hint">
	<?php echo UserModule::t("Minimal password length 4 symbols."); ?>
	</p>
	</div>
	
	<div class="field stacked">
	<?php echo CHtml::activeLabelEx($form,'verifyPassword'); ?>
	<?php echo CHtml::activePasswordField($form,'verifyPassword'); ?>
	</div>
	
	<div class="field stacked">
	<?php echo CHtml::activeLabelEx($form,'email'); ?>
	<?php echo CHtml::activeTextField($form,'email'); ?>
	</div>
	
<?php 
		$profileFields=ProfileField::model()->forRegistration()->sort()->findAll();
		if ($profileFields) {
			foreach($profileFields as $field) {
			?>
	<div class="field stacked">
		<?php echo CHtml::activeLabelEx($profile,$field->varname); ?>
		<?php 
		if ($field->widgetEdit($profile)) {
			echo $field->widgetEdit($profile);
		} elseif ($field->range) {
			echo CHtml::activeDropDownList($profile,$field->varname,Profile::range($field->range));
		} elseif ($field->field_type=="TEXT") {
			echo CHtml::activeTextArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
		} else {
			echo CHtml::activeTextField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
		}
		 ?>
		<?php echo CHtml::error($profile,$field->varname); ?>
	</div>	
			<?php
			}
		}
?>
	<?php if (UserModule::doCaptcha('registration')): ?>
	<div class="field stacked">
		<?php echo CHtml::activeLabelEx($form,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo CHtml::activeTextField($form,'verifyCode'); ?>
		</div>
		<p class="hint"><?php echo UserModule::t("Please enter the letters as they are shown in the image above."); ?>
		<br/><?php echo UserModule::t("Letters are not case-sensitive."); ?></p>
	</div>
	<?php endif; ?>
	
	<div class="button-bar group">
		<?php echo CHtml::submitButton(UserModule::t("Register")); ?>
	</div>
    
    <div class="note centered"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></div>
</fieldset>
<?php echo CHtml::endForm(); ?>
</div><!-- form -->
<?php endif; ?>