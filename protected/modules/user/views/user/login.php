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

<div id="page group" class="row-fluid">
    <div class="span4 offset4">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type'=>'inline',
        'enableClientValidation' => true,
        'id' => 'login-form',
        'clientOptions'=>array('validateOnSubmit'=>true),
        //'errorMessageCssClass'=>'error',
        'htmlOptions'=>array('class'=>'well'),
        )); ?>
        <fieldset>
            <legend>Login</legend>
        </fieldset>
    <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <div class="span12">
                <?php echo $form->error($model,'username'); ?>
                <?php echo $form->textFieldRow($model, 'username', array('class'=>'span12')); ?>
                <br/><br/>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php echo $form->error($model,'password'); ?>
                <?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span12')); ?>
                <br/><br/>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php echo $form->checkboxRow($model, 'rememberMe'); ?>
                <br/><br/>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Login')); ?><br/>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <hr/>
                <span class="hint">
                <?php echo CHtml::link(UserModule::t("Register"),Yii::app()->getModule('user')->registrationUrl); ?> | <?php echo CHtml::link(UserModule::t("Lost Password?"),Yii::app()->getModule('user')->recoveryUrl); ?>
                </span>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>