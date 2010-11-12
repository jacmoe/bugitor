<?php

class TimeZoneKeeper extends CComponent{

        public $userTimezone;
        public $serverTimezone;


        public function init(){
                //settiamo il server di default a UTC per rendere l'implementazione  indipendente
                //dal settaggio del server.
                Yii::app()->setTimeZone("UTC");
                $this->serverTimezone = new DateTimeZone(Yii::app()->getTimeZone());
        }

        public function serverToUser($timestamp){
                $this->userTimezone = new DateTimeZone(Yii::app()->getModule('user')->user()->profile->getAttribute('timezone'));
                $serverDateTime = new DateTime("@".$timestamp);
                $offset = $this->userTimezone->getOffset($serverDateTime);
                return ($serverDateTime->format('U') + $offset);
        }

        public function userToServer($timestamp){
                $this->userTimezone = new DateTimeZone(Yii::app()->getModule('user')->user()->profile->getAttribute('timezone'));
                $userDateTime = new DateTime("@".$timestamp);
                $offset = $this->serverTimezone->getOffset($userDateTime);
                return ($userDateTime->format('U') - $offset);
        }

}
