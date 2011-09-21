<?php
$nonwatcherslist = $model->nonWatchersList;
if (null != $nonwatcherslist) {
echo 'Add watcher:'.PHP_EOL;
echo CHtml::beginForm('#').PHP_EOL;
$this->widget('ext.combobox.EJuiComboBox', array(
    'name' => 'add_watcher',
    'data' => $nonwatcherslist,
));
echo CHtml::ajaxSubmitButton('Add',$this->createUrl('addwatcher', array('issue_id' => $model->id)),
        array('update' => '#watchers',
            'type' => "post",
            'complete' => 'function(data,status){
               $("#add_watcher").text("");
           }'
), array('id' => 'add-watcher-button')
        ).PHP_EOL;
echo CHtml::endForm().PHP_EOL;
}
$watcherslist = $model->watchersList;
if (null != $watcherslist) {
echo 'Remove watcher:'.PHP_EOL;
echo CHtml::beginForm('#').PHP_EOL;
$this->widget('ext.combobox.EJuiComboBox', array(
    'name' => 'remove_watcher',
    'data' => $watcherslist,
));
echo CHtml::ajaxSubmitButton('Remove',$this->createUrl('removewatcher', array('issue_id' => $model->id)),
        array('update' => '#watchers',
            'type' => "post"), array('id' => 'remove-watcher-button')
        ).PHP_EOL;
echo CHtml::endForm().PHP_EOL;
}
?>