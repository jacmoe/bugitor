<?php

/**
 * This is the model class for table "{{project}}".
 *
 * The followings are the available columns in table '{{project}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $homepage
 * @property integer $public
 * @property string $created
 * @property string $modified
 * @property string $identifier
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Issue[] $issues
 * @property Tracker[] $bugTrackers
 * @property Repository[] $repositories
 * @property Version[] $versions
 */
class Project extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Project the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{project}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('public, status', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 30),
            array('homepage', 'length', 'max' => 255),
            array('identifier', 'length', 'max' => 20),
            array('description, created, modified', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, description, homepage, public, created, modified, identifier, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'issues' => array(self::HAS_MANY, 'Issue', 'project_id'),
            'bugTrackers' => array(self::MANY_MANY, 'Tracker', '{{project_tracker}}(project_id, tracker_id)'),
            'repositories' => array(self::HAS_MANY, 'Repository', 'project_id'),
            'versions' => array(self::HAS_MANY, 'Version', 'project_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'homepage' => 'Homepage',
            'public' => 'Public',
            'created' => 'Created',
            'modified' => 'Modified',
            'identifier' => 'Identifier',
            'status' => 'Status',
        );
    }

    public function behaviors() {
        return array(
            'CActiveRecordLogableBehavior' =>
            array('class' => 'application.behaviors.CActiveRecordLogableBehavior'),
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
            )
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('homepage', $this->homepage, true);
        $criteria->compare('public', $this->public);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('identifier', $this->identifier, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}