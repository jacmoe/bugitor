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
 * Copyright (C) 2009 - 2011 Bugitor Team
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
