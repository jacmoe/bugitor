<?php
echo $event->sender->menu->run();
echo '<div>Step '.$event->sender->currentStep.' of '.$event->sender->stepCount;
echo '<h3>'.$event->sender->getStepLabel($event->step).'</h3>';
echo 'Checking permissions..<br/><br/>';
echo $message;
if($failed) {
Yii::app()->clientScript->registerScript('disableInstallButton',<<<EOD
$("#installButton").attr('disabled', 'disabled');
EOD
,CClientScript::POS_READY);
echo '<br/>The installation cannot continue..<br/><br/>';
}
echo CHtml::tag('div',array('class'=>'form'),$form);
