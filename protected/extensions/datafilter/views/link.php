<?php 
    //$options = array('id' => $filter->name.$filter->linkValue."_link");
    $options = array('id' => $filter->getFormName());
    if (
          ($filter->hasValue() && $filter->getValue() === $filter->linkValue)
       || ( $filter->isDefault && !$this->getFilters()->hasValues()) 
    ) {
        $options['class']='active';
    }
    echo CHtml::link($filter->label, array($this->formAction, $filter->getFormName()=>$filter->linkValue), $options);
    //echo $filter->getValue(). ' link = '. $filter->linkValue;
?>

<?php
if ($this->getAjaxMode()) {

$filterName = $filter->getFormName(); //$filter->name.$filter->linkValue."_link";
$update = $this->getUpdateSelector();
$beforeUpate = $this->getBeforeUpdateCode();
$script = <<<EOD

jQuery('#$filterName').click(
    function(){
        $beforeUpate
        jQuery.ajax(
        {'type':'GET','url':$(this).attr('href'),'cache':false,
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