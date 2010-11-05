<?php

class TimeZoneKeeper extends CComponent{

        public $userTimezone;
        public $serverTimezone;


        public function init(){
                //settiamo il server di default a UTC per rendere l'implementazione  indipendente
                //dal settaggio del server.
                Yii::app()->setTimeZone("UTC");
                $this->serverTimeZone = new DateTimeZone(Yii::app()->getTimeZone());
        }

        public function serverToUser($timestamp){
                $this->userTimeZone = new DateTimeZone('EUROPE'/*Yii::app()->user->tz*/);
                $serverDateTime = new DateTime("@".$timestamp);
                $offset = $this->userTimezone->getOffset($serverDateTime);
                return ($serverDateTime->format('U') + $offset);
        }

        public function userToServer($timestamp){
                $this->userTimeZone = new DateTimeZone('EUROPE'/*Yii::app()->user->tz*/);
                $userDateTime = new DateTime("@".$timestamp);
                $offset = $this->userTimezone->getOffset($userDateTime);
                return ($userDateTime->format('U') - $offset);
        }

}
