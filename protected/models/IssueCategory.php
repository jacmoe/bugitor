<?php

/**
 * This is the model class for table "{{issue_category}}".
 *
 * The followings are the available columns in table '{{issue_category}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $project_id
 *
 * The followings are the available model relations:
 * @property Issue[] $issues
 */
class IssueCategory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return IssueCategory the static model class
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
		return '{{issue_category}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description', 'required'),
			array('name', 'length', 'max'=>45),
			array('description', 'length', 'max'=>255),
			array('name', 'isinproject'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, project_id', 'safe', 'on'=>'search'),
		);
	}

        public function isinproject($attribute, $params) {
            if($this->isNewRecord) {
                $criteria = new CDbCriteria;
                $criteria->compare('project_id', $this->project_id, true);
                $criteria->compare('name', $this->name, true);
                $results = IssueCategory::model()->findAll($criteria);
                if($results)
                    $this->addError($attribute, 'There is already a category of that name in this project.');
            }
        }
        /**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'issues' => array(self::HAS_MANY, 'Issue', 'issue_category_id'),
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
			'description' => 'Description',
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
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}