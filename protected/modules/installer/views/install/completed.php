<?php
echo $event->sender->menu->run();
echo CHtml::tag('p', array(), 'Data collected is:');
foreach ($event->data as $step=>$data):
        $modelName = ucfirst($step);
	$model = new $modelName();
	echo CHtml::tag('h2', array(), $event->sender->getStepLabel($step));
	echo ('<ul>');
	foreach ($data as $k=>$v)
		echo '<li>'.$model->getAttributeLabel($k).": $v</li>";
	echo ('</ul>');
endforeach;
echo CHtml::link('Run installer', 'installation');

