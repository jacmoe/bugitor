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
 * Copyright (C) 2009 - 2012 Bugitor Team
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

/**
 * ConfigForm class.
 * ConfigForm is the data structure for keeping
 * config form data. It is used by the 'settings' action of 'admin/DefaultController'.
 */
class EmailForm extends CFormModel
{
	public $subject = 'This is an email test';
        public $message = 'Just testing that email works';

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('subject, message', 'required'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
            return array(
                'subject'=>'Subject',
                'message' => 'Message',
            );
	}
        public function save() {
            $message = new YiiMailMessage;
            $message->setSubject($this->subject);
            $message->setBody($this->message, 'text/html');
            if($_SERVER['SERVER_NAME'] == 'localhost') {
                $message->setSender(array('jacmoe@mail.dk' => 'Jacob Moen'));
                $message->setFrom(array('jacmoe@mail.dk' => 'Jacob Moen'));
            } else {
                $message->setSender(array('ticket@tracker.ogitor.org' => 'Bugitor Issue Tracker'));
                $message->setFrom(array('ticket@tracker.ogitor.org' => 'Bugitor Issue Tracker'));
            }
            $message->addTo('jacmoe@mail.dk');
            Yii::app()->mail->send($message);
            return true;
        }
}