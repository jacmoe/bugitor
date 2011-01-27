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
 * This is the model class for table "{{action_log}}".
 *
 * The followings are the available columns in table '{{action_log}}':
 * @property integer $id
 * @property string $type
 * @property integer $author_id
 * @property string $when
 * @property string $url
 * @property integer $project_id
 * @property string $subject
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Project $project
 * @property User $author
 */
class ActionLog extends CActiveRecord
{
	public $theday;

        /**
	 * Returns the static model of the specified AR class.
	 * @return ActionLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{action_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, author_id, when, url, project_id, subject, description', 'required'),
			array('author_id, project_id', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>32),
			array('url', 'length', 'max'=>100),
                        array('when', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, author_id, when, url, project_id, subject, description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'author_id' => 'Author',
			'when' => 'When',
			'url' => 'Url',
			'project_id' => 'Project',
			'subject' => 'Subject',
			'description' => 'Description',
		);
	}

        public function findRecentEntries($number = 30, $projectId = null) {
            $criteria2 = new CDbCriteria;
            $criteria2->select = array('id', 'type', 'author_id', 't.when', 'url', 'project_id', 'subject', 'description', 'DATE(t.when) as theday');
            if(null !== $projectId) {
                $criteria2->condition = 'project_id = :project_id';
                $criteria2->params = array('project_id' => $projectId);
            }
            $criteria2->order = 't.when DESC';
//            $criteria2->order = 'theday DESC, t.when DESC';
//            $criteria2->group = 'theday, t.when';
            $criteria2->limit = $number;
            return self::model()->findAll($criteria2);
        }

        /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('when',$this->when,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
