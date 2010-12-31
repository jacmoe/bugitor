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