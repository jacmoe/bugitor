<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Change Password");
$this->breadcrumbs=array(
	UserModule::t("Profile") => array('/user/profile'),
	UserModule::t("Change Password"),
);
?>

<?php //echo $this->renderPartial('menu'); ?>

<div id="page group">
<?php echo CHtml::beginForm(); ?>
    <fieldset class="change-password">
    <h1><?php echo UserModule::t("Change password"); ?></h1>

	<?php echo CHtml::errorSummary($form); ?>
	
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
	
	
	<div class="button-bar group">
	<?php echo CHtml::submitButton(UserModule::t("Save")); ?>
	</div>
    <div class="note centered"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></div>
    </fieldset> 

<?php echo CHtml::endForm(); ?>
</div><!-- form -->