<?php

/**
 * From Phira's blog: http://phiras.wordpress.com/2010/02/25/xss-safe-content-in-yii/
 */

class CSafeContentBehavior extends CActiveRecordBehavior
{
   public $attributes =array();
   protected $purifier;

   function __construct(){
      $this->purifier = new CHtmlPurifier;
   }

   public function beforeSave($event)
   {
      foreach($this->attributes as $attribute){
         $this->getOwner()->{$attribute} = $this->purifier->purify($this->getOwner()->{$attribute});
      }
   }
}
