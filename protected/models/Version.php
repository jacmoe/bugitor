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
 * This is the model class for table "{{version}}".
 *
 * The followings are the available columns in table '{{version}}':
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property string $description
 * @property string $effective_date
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Issue[] $issues
 * @property Project $project
 */
class Version extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Version the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{version}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('project_id', 'numerical', 'integerOnly' => true),
            array('name, description', 'length', 'max' => 255),
            array('name', 'isinproject'),
            array('effective_date, created, modified', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, project_id, name, description, effective_date, created, modified', 'safe', 'on' => 'search'),
        );
    }

        public function isinproject($attribute, $params) {
            if($this->isNewRecord) {
                $criteria = new CDbCriteria;
                $criteria->compare('project_id', $this->project_id, true);
                $criteria->compare('name', $this->name, true);
                $results = Version::model()->findAll($criteria);
                if($results)
                    $this->addError($attribute, 'There is already a version of that name in this project.');
            }
        }
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'issues' => array(self::HAS_MANY, 'Issue', 'version_id'),
            'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
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
            'description' => 'Description',
            'effective_date' => 'Effective Date',
            'created' => 'Created',
            'modified' => 'Modified',
        );
    }

    public function behaviors() {
        return array(
            'CActiveRecordLogableBehavior' =>
            array('class' => 'application.behaviors.CActiveRecordLogableBehavior'),
            'BugitorTimestampBehavior' => array(
                'class' => 'application.behaviors.BugitorTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
            ),
            'CSafeContentBehavior' => array(
                'class' => 'application.behaviors.CSafeContentBehavior',
                'attributes' => array('description', 'name'),
            ),
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