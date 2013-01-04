<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Login");
$this->breadcrumbs=array(
	UserModule::t("Login"),
);
?>
<?php if(Yii::app()->user->hasFlash('loginMessage')): ?>
<div class="success">
	<?php echo Yii::app()->user->getFlash('loginMessage'); ?>
</div>
<?php endif; ?>
<div id="page group">
<?php echo CHtml::beginForm(); ?>
    <fieldset class="login">
	
	<h1>Login</h1>
	
	<?php echo CHtml::errorSummary($model); ?>
	
	<div class="field stacked ">
		<?php echo CHtml::activeLabelEx($model,'username'); ?>
		<?php echo CHtml::activeTextField($model,'username', array('type' => 'email', 'class' => 'login')) ?>
	</div>
	
	<div class="field stacked ">
		<?php echo CHtml::activeLabelEx($model,'password'); ?>
		<?php echo CHtml::activePasswordField($model,'password', array('type' => 'password', 'class' => 'login')) ?>
	</div>
	<div class="rememberme group">
        <?php echo CHtml::activeCheckBox($model,'rememberMe'); ?>
        <?php echo CHtml::activeLabelEx($model,'rememberMe'); ?>
	</div>
	<div class="button-bar group">
		<?php echo CHtml::submitButton(UserModule::t("Login")); ?>
	</div>
    <p class="hint">
    <?php echo CHtml::link(UserModule::t("Register"),Yii::app()->getModule('user')->registrationUrl); ?> | <?php echo CHtml::link(UserModule::t("Lost Password?"),Yii::app()->getModule('user')->recoveryUrl); ?>
    </p>
    <div class="note centered"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></div>
    </fieldset>	
<?php echo CHtml::endForm(); ?>
</div>
<?php
$form = new CForm(array(
    'elements'=>array(
        'username'=>array(
            'type'=>'text',
            'maxlength'=>32,
        ),
        'password'=>array(
            'type'=>'password',
            'maxlength'=>32,
        ),
        'rememberMe'=>array(
            'type'=>'checkbox',
        )
    ),

    'buttons'=>array(
        'login'=>array(
            'type'=>'submit',
            'label'=>'Login',
        ),
    ),
), $model);
?>