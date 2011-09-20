<?php
$nonwatcherslist = $model->nonWatchersList;
if (null != $nonwatcherslist) {
echo 'Add watcher:'.PHP_EOL;
echo CHTML::beginForm('#').PHP_EOL;
echo CHTML::dropDownList('add_watcher', '', $nonwatcherslist).PHP_EOL;
echo CHTML::ajaxSubmitButton('Add',$this->createUrl('addwatcher', array('issue_id' => $model->id)),
        array('update' => '#watchers',
            'type' => "post")
        ).PHP_EOL;
echo CHTML::endForm().PHP_EOL;
}
$watcherslist = $model->watchersList;
if (null != $watcherslist) {
echo 'Remove watcher:'.PHP_EOL;
echo CHTML::beginForm('#').PHP_EOL;
echo CHTML::dropDownList('remove_watcher', '', $watcherslist).PHP_EOL;
echo CHTML::ajaxSubmitButton('Remove',$this->createUrl('removewatcher', array('issue_id' => $model->id)),
        array('update' => '#watchers',
            'type' => "post")
        ).PHP_EOL;
echo CHTML::endForm().PHP_EOL;
}
?>