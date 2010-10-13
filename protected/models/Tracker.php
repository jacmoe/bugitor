<?php

/**
 * This is the model class for table "{{tracker}}".
 *
 * The followings are the available columns in table '{{tracker}}':
 * @property integer $id
 * @property string $name
 * @property integer $is_in_chlog
 * @property integer $is_in_roadmap
 * @property integer $position
 *
 * The followings are the available model relations:
 * @property Issue[] $issues
 * @property Project[] $bugProjects
 */
class Tracker extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Tracker the static model class
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
		return '{{tracker}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('is_in_chlog, is_in_roadmap, position', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, is_in_chlog, is_in_roadmap, position', 'safe', 'on'=>'search'),
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
			'issues' => array(self::HAS_MANY, 'Issue', 'tracker_id'),
			'bugProjects' => array(self::MANY_MANY, 'Project', '{{project_tracker}}(tracker_id, project_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'is_in_chlog' => 'Is In Chlog',
			'is_in_roadmap' => 'Is In Roadmap',
			'position' => 'Position',
		);
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('is_in_chlog',$this->is_in_chlog);
		$criteria->compare('is_in_roadmap',$this->is_in_roadmap);
		$criteria->compare('position',$this->position);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}