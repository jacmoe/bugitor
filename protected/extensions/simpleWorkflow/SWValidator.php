<?php
/**
 * This validator should be used to validate the 'status' attribute for an active record
 * object, before it is saved. It tests if the transition that may be about to occur is valid. 
 */
class SWValidator extends CValidator
{
    protected function validateAttribute($model,$attribute)
    {
    	Yii::trace(__CLASS__.'.'.__FUNCTION__,SWActiveRecordBehavior::SW_LOG_CATEGORY);
    	$value=$model->$attribute;
    	$model->swValidate($attribute,$value);
    } 
}
?>
