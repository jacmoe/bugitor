<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Restore");
$this->breadcrumbs=array(
	UserModule::t("Login") => array('/user/login'),
	UserModule::t("Restore"),
);
?>
<?php if(Yii::app()->user->hasFlash('recoveryMessage')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
</div>
<?php else: ?>

<div id="page group">
<?php echo CHtml::beginForm(); ?>
    <fieldset class="lost-password">
    <h1><?php echo UserModule::t("Restore User Password"); ?></h1>

	<?php echo CHtml::errorSummary($form); ?>
	
	<div class="field stacked">
		<?php echo CHtml::activeLabel($form,'login_or_email'); ?>
		<?php echo CHtml::activeTextField($form,'login_or_email') ?>
		<p class="hint"><?php echo UserModule::t("Please enter your login or email address."); ?></p>
	</div>
	
	<div class="button-bar group">
		<?php echo CHtml::submitButton(UserModule::t("Restore")); ?>
	</div>
    </fieldset> 

<?php echo CHtml::endForm(); ?>
</div><!-- form -->
<?php endif; ?>