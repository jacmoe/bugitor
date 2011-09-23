<?php
$nonwatcherslist = $model->nonWatchersList;
echo 'Add watcher:'.PHP_EOL;
echo CHtml::beginForm('#').PHP_EOL;
$this->widget('ext.combobox.EJuiComboBox', array(
    'name' => 'add_watcher',
    'data' => $nonwatcherslist,
));
echo CHtml::ajaxSubmitButton('Add',$this->createUrl('addwatcher', array('issue_id' => $model->id)),
        array('update' => '#watchers',
            'type' => "post",
            'complete' => 'js:function(data,status){
               $("#remove_watcher_select").append($("#add_watcher_select :selected"));
               $("#add_watcher_select :selected").remove();
               $("#add_watcher").val("");
                // Loop for each select element on the page.
                var count = $("#remove_watcher_select").children().length;
                if(count > 2) {
                $("#remove_watcher_select").each(function() {
                    // Keep track of the selected option.
                    var selectedValue = $(this).val();

                    // Sort all the options by text. I could easily sort these by val.
                    $(this).html($("option", $(this)).sort(function(a, b) {
                        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1
                    }));

                    // Select one option.
                    $(this).val(selectedValue);
                });
                }
           }'
), array('id' => 'add-watcher-button')
        ).PHP_EOL;
echo CHtml::endForm().PHP_EOL;

$watcherslist = $model->watchersList;

echo 'Remove watcher:'.PHP_EOL;
echo CHtml::beginForm('#').PHP_EOL;
$this->widget('ext.combobox.EJuiComboBox', array(
    'name' => 'remove_watcher',
    'data' => $watcherslist,
));
echo CHtml::ajaxSubmitButton('Remove',$this->createUrl('removewatcher', array('issue_id' => $model->id)),
        array('update' => '#watchers',
            'type' => "post",
            'complete' => 'js:function(data,status){
               $("#add_watcher_select").append($("#remove_watcher_select :selected"));
               $("#remove_watcher_select :selected").remove();
               $("#remove_watcher").val("");
                // Loop for each select element on the page.
                var count = $("#add_watcher_select").children().length;
                if(count > 2) {
                $("#add_watcher_select").each(function() {
                    // Keep track of the selected option.
                    var selectedValue = $(this).val();

                    // Sort all the options by text. I could easily sort these by val.
                    $(this).html($("option", $(this)).sort(function(a, b) {
                        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1
                    }));

                    // Select one option.
                    $(this).val(selectedValue);
                });
                }
           }'
        ), array('id' => 'remove-watcher-button')
        ).PHP_EOL;
echo CHtml::endForm().PHP_EOL;

?>