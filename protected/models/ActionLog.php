<?php

/**
 * This is the model class for table "{{action_log}}".
 *
 * The followings are the available columns in table '{{action_log}}':
 * @property string $id
 * @property string $new_value
 * @property string $old_value
 * @property string $action
 * @property string $model
 * @property string $idModel
 * @property string $field
 * @property string $creationdate
 * @property string $userid
 *
 * The followings are the available model relations:
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
			array('creationdate', 'required'),
			array('old_value', 'length', 'max'=>255),
			array('new_value', 'length', 'max'=>255),
			array('action', 'length', 'max'=>20),
			array('model, field, userid', 'length', 'max'=>45),
			array('idModel', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, description, action, model, idModel, field, creationdate, userid', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'old_value' => 'Old Value',
			'new_value' => 'New Value',
			'action' => 'Action',
			'model' => 'Model',
			'idModel' => 'Id Model',
			'field' => 'Field',
			'creationdate' => 'Creationdate',
			'userid' => 'Userid',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('new_value',$this->new_value,true);
		$criteria->compare('old_value',$this->old_value,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('idModel',$this->idModel,true);
		$criteria->compare('field',$this->field,true);
		$criteria->compare('creationdate',$this->creationdate,true);
		$criteria->compare('userid',$this->userid,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}