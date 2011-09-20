<?php
echo 'Add watcher:'.PHP_EOL;
echo CHTML::beginForm('#').PHP_EOL;
echo CHTML::dropDownList('add_watcher', '', $model->getNonWatchersList()).PHP_EOL; //$model->getNonWatchersList();
echo CHTML::ajaxSubmitButton('Add',$this->createUrl('addwatcher', array('issue_id' => $model->id)),
        array('update' => '#watchers',
            'type' => "post")
        ).PHP_EOL;
echo CHTML::endForm().PHP_EOL;
?>