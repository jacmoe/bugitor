<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2010 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
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
                
                if (($value != $old)&&($name !== 'modified')) {
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