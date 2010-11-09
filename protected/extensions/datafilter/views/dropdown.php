<div class='dropDown'> 
<label for='searchfield'>
<?php echo $filter->displayName; ?>
</label><br />
<?php 
$params['id'] = $filter->name;
echo CHtml::dropDownList($filter->formName, 
            $filter->getValue(),
            $data,
            $params) 
?>
</div>

<?php

if ($this->getAjaxMode() && $filter->autoSubmit) {

//$url = Yii::app()->getRequest()->getUrl();
$url = $this->formAction;
$filterName = $filter->name;
$update = $this->getUpdateSelector();
$beforeUpate = $this->getBeforeUpdateCode();
$script = <<<EOD

jQuery('#$filterName').change(
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