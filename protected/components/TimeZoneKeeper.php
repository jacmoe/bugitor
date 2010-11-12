<?php

class TimeZoneKeeper extends CComponent{

        public $userTimezone;
        public $serverTimezone;


        public function init(){
                //settiamo il server di default a UTC per rendere l'implementazione  indipendente
                //dal settaggio del server.
                Yii::app()->setTimeZone("Europe/London");
                $this->serverTimezone = new DateTimeZone(Yii::app()->getTimeZone());
        }

        public function serverToUser($timestamp){
//                $this->userTimezone = new DateTimeZone('Europe/London');
                if((!Yii::app()->user->isGuest)) {
                    $userProfileTimezone = Yii::app()->getModule('user')->user()->profile->getAttribute('timezone');
                    if((!Yii::app()->user->isGuest)&&(($userProfileTimezone))) {
                        $this->userTimezone = new DateTimeZone($userProfileTimezone);
                    } else {
                        $this->userTimezone = new DateTimeZone('Europe/London');
                    }
                } else {
                    $this->userTimezone = new DateTimeZone('Europe/London');
                }
                $serverDateTime = new DateTime("@".$timestamp);
                $offset = $this->userTimezone->getOffset($serverDateTime);
                return ($serverDateTime->format('U') + $offset);
        }

        public function userToServer($timestamp){
                $this->userTimezone = new DateTimeZone('Europe/London');
                $userDateTime = new DateTime("@".$timestamp);
                $offset = $this->serverTimezone->getOffset($userDateTime);
                return ($userDateTime->format('U') - $offset);
        }

}
