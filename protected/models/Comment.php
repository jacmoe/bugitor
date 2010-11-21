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

/**
 * This is the model class for table "{{comment}}".
 *
 * The followings are the available columns in table '{{comment}}':
 * @property integer $id
 * @property string $content
 * @property integer $issue_id
 * @property string $created
 * @property integer $create_user_id
 * @property string $modified
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property Users $createUser
 * @property Issue $issue
 * @property CommentDetail $details
 */
class Comment extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Comment the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{comment}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content', 'required'),
            array('issue_id, create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
            array('created, modified, content', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, content, issue_id, created, create_user_id, modified, update_user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'author' => array(self::BELONGS_TO, 'User', 'create_user_id'),
            'issue' => array(self::BELONGS_TO, 'Issue', 'issue_id'),
            'details' => array(self::HAS_MANY, 'CommentDetail', 'comment_id'),
        );
    }

    public static function findRecentComments($limit=10, $projectId=null) {
        if ($projectId != null) {
            return self::model()->with(array('issue, details' => array('condition' => 'project_id='.$projectId)))->findAll(array(
                'order' => 't.created DESC',
                'limit' => $limit,
            ));
        } else {
            //get all comments across all projects
            return self::model()->with('issue, details')->findAll(array(
                'order' => 't.created DESC',
                'limit' => $limit,
            ));
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'content' => 'Content',
            'issue_id' => 'Issue',
            'created' => 'Created',
            'create_user_id' => 'Author',
            'modified' => 'Modified',
            'update_user_id' => 'Update User',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('issue_id', $this->issue_id);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('create_user_id', $this->create_user_id);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('update_user_id', $this->update_user_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Prepares create_time, create_user_id, update_time and update_user_id attributes before performing validation.
     */
    protected function beforeValidate() {
        //parent::beforeValidate();

        if ($this->isNewRecord) {
            // set the create date, last updated date and the user doing the creating
            $this->created = $this->modified = date("Y-m-d\TH:i:s\Z", time());//new CDbExpression('UTC_TIMESTAMP()');
            $this->create_user_id = $this->update_user_id = Yii::app()->user->id;
        } else {
            //not a new record, so just set the last updated time and last updated user id
            $this->modified = date("Y-m-d\TH:i:s\Z", time());//new CDbExpression('UTC_TIMESTAMP()');
            $this->update_user_id = Yii::app()->user->id;
        }

        return parent::beforeValidate();
        //return true;
    }
    public function behaviors() {
        return array(
            'CSafeContentBehavior' => array(
                'class' => 'application.behaviors.CSafeContentBehavior',
                'attributes' => array('content'),
            ),
        );
    }

}