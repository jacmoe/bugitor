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
 * This is the model class for table "{{milestone}}".
 *
 * The followings are the available columns in table '{{milestone}}':
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $effective_date
 * @property string $created
 * @property string $modified
 * @property Issue[] $issues
 * @property Project $project
 */
class Milestone extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Milestone the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{milestone}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, title, description', 'required'),
            array('project_id', 'numerical', 'integerOnly' => true),
            array('name, title', 'length', 'max' => 255),
            array('name', 'isinproject'),
            array('effective_date, created, modified', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, project_id, name, description, effective_date, created, modified', 'safe', 'on' => 'search'),
        );
    }

    public static function getOpenMilestoneCount($project_id) {
        //FIXME: this should be cached
        $criteria = new CDbCriteria();
        $criteria->condition = 'project_id = :project_id';
        $criteria->params = array('project_id' => $project_id);
        $milestones = Milestone::model()->with(array('issueCountOpen'))->findAll($criteria);
        $count = 0;
        foreach ($milestones as $milestone) {
            if (strtotime($milestone->effective_date) >= strtotime(date("Y-m-d"))) {
                $count++;
            } elseif (strtotime($milestone->effective_date) < strtotime(date("Y-m-d"))) {
                if($milestone->issueCountOpen > 0) {
                    $count++;
                }
            }
        }
        return $count;
    }
    
    public function isinproject($attribute, $params) {
            if($this->isNewRecord) {
                $criteria = new CDbCriteria;
                $criteria->compare('project_id', $this->project_id, true);
                $criteria->compare('name', $this->name, true);
                $results = Milestone::model()->findAll($criteria);
                if($results)
                    $this->addError($attribute, 'There is already a milestone of that name in this project.');
            }
        }
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'issues' => array(self::HAS_MANY, 'Issue', 'milestone_id'),
            'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
            'issueCount' => array(self::STAT, 'Issue', 'milestone_id'),
            'issueCountClosed' => array(self::STAT, 'Issue', 'milestone_id', 'condition' => 'closed=1'),
            'issueCountOpen' => array(self::STAT, 'Issue', 'milestone_id', 'condition' => 'closed=0'),
            'issueCountDone' => array(self::STAT, 'Issue', 'milestone_id', 'condition' => 'closed=0', 'select' => 'AVG(done_ratio)'),
            'issueCountResolved' => array(self::STAT, 'Issue', 'milestone_id', 'condition' => 'status="swIssue/resolved" AND closed=1'),
            'issueCountRejected' => array(self::STAT, 'Issue', 'milestone_id', 'condition' => 'status="swIssue/rejected" AND closed=1'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'project_id' => 'Project',
            'name' => 'Name',
            'title' => 'Title',
            'description' => 'Description',
            'effective_date' => 'Effective Date',
            'created' => 'Created',
            'modified' => 'Modified',
        );
    }

    public function behaviors() {
        return array(
            'BugitorTimestampBehavior' => array(
                'class' => 'application.behaviors.BugitorTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
            ),
//            'CSafeContentBehavior' => array(
//                'class' => 'application.behaviors.CSafeContentBehavior',
//                'attributes' => array('description', 'name'),
//            ),
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
        $criteria->compare('project_id', $this->project_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('effective_date', $this->effective_date, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}