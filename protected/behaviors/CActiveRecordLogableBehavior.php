<?php

class CActiveRecordLogableBehavior extends CActiveRecordBehavior
{
    private $_oldattributes = array();
 
    public function afterSave($event)
    {
        if (!$this->Owner->isNewRecord) {
 
            // new attributes
            $newattributes = $this->Owner->getAttributes();
            $oldattributes = $this->getOldAttributes();
 
            // compare old and new
            foreach ($newattributes as $name => $value) {
                if (!empty($oldattributes)) {
                    $old = $oldattributes[$name];
                } else {
                    $old = '';
                }
                if($name === 'modified')
                    return;
                
                if ($value != $old) {
                    //$changes = $name . ' ('.$old.') => ('.$value.'), ';
 
                    $log=new ActionLog;
                    $log->action=       'CHANGE';
                    $log->model=        get_class($this->Owner);
                    $log->idModel=      $this->Owner->getPrimaryKey();
                    $log->field=        $name;
                    $log->old_value=    $old;
                    $log->new_value=    $value;
                    $log->creationdate= date("Y-m-d\TH:i:s\Z", time());//new CDbExpression('UTC_TIMESTAMP()');
                    $log->userid=       Yii::app()->user->id;
                    $log->save();
                }
            }
        } else {
            $log=new ActionLog;
            $log->action=       'CREATE';
            $log->model=        get_class($this->Owner);
            $log->idModel=      $this->Owner->getPrimaryKey();
            $log->field=        '';
            $log->creationdate= date("Y-m-d\TH:i:s\Z", time());//new CDbExpression('UTC_TIMESTAMP()');
            $log->userid=       Yii::app()->user->id;
            $log->save();
        }
    }
 
    public function afterDelete($event)
    {
        $log=new ActionLog;
        $log->action=       'DELETE';
        $log->model=        get_class($this->Owner);
        $log->idModel=      $this->Owner->getPrimaryKey();
        $log->field=        '';
        $log->creationdate= date("Y-m-d\TH:i:s\Z", time());//new CDbExpression('UTC_TIMESTAMP()');
        $log->userid=       Yii::app()->user->id;
        $log->save();
    }
 
    public function afterFind($event)
    {
        // Save old values
        $this->setOldAttributes($this->Owner->getAttributes());
    }
 
    public function getOldAttributes()
    {
        return $this->_oldattributes;
    }
 
    public function setOldAttributes($value)
    {
        $this->_oldattributes=$value;
    }
}