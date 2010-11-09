<div class='searchdiv'>
<div class = 'fileds'>
    <label for='searchfield'>
        <?php echo Yii::t('datafilter', 'Field'); ?>
    </label><br />
    <?php echo  CHtml::dropDownList($filter->formName,
                 $filter->getValue(),   
                 $data);
    ?>
</div>
<div class = 'value'>
    <label for='searchfield'><?php echo Yii::t('datafilter', 'Contains'); ?></label><br />
    <?php  
    echo CHtml::textField($filter->getTextFieldFormName(),
            $filter->getTextFieldValue(),
            array('size'=>'12'));

    if (!$this->getAjaxMode()) {
        echo CHtml::submitButton(Yii::t('datafilter', 'Search'), array('class'=>'submit', 'size'=>'5'));
    } else {
        echo CHtml::button(Yii::t('datafilter', 'Search'), array('class'=>'submit', 'id'=>$filter->name."_submit", 'size'=>'5'));
    }
    ?>
</div>
</div>

<?php

if ($this->getAjaxMode()) {

//$url = Yii::app()->getRequest()->getUrl();
$url = $this->formAction;
$filterName = $filter->name."_submit";
$update = $this->getUpdateSelector();
$beforeUpate = $this->getBeforeUpdateCode();
$script = <<<EOD

jQuery('#$filterName').click(
    function(){
        $beforeUpate
        jQuery.ajax(
        {'type':'GET','url':'$url','cache':false,
            'data':jQuery(this).parents("form").serialize(),
            'success':function(html) {
                jQuery("$update").html(html)
            }
        }
        );
        return false;
    }
);

EOD;

echo CHtml::script($script);   

}
?>