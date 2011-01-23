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

/**
 * ConfigForm class.
 * ConfigForm is the data structure for keeping
 * config form data. It is used by the 'settings' action of 'admin/DefaultController'.
 */
class ConfigForm extends CFormModel
{
	public $pagesize;
        public $hg_executable;
        public $python_path;
        public $default_scm;
        public $default_timezone;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('pagesize, hg_executable, python_path, default_scm, default_timezone', 'required'),
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
                'pagesize'=>'Page Size',
                'hg_exectutable' => 'Path to hg exectuable',
                'python_path' => 'Python path environment variable',
                'default_scm' => 'Default Source Control Provider',
                'default_timezone' => 'Default Timezone',
            );
	}
        public function save() {
            Yii::app()->config->set('hg_executable', $this->hg_executable);
            Yii::app()->config->set('defaultPagesize', $this->pagesize);
            Yii::app()->config->set('python_path', $this->python_path);
            Yii::app()->config->set('default_timezone', $this->default_timezone);
            Yii::app()->config->set('default_scm', $this->default_scm);
            return true;
        }
}