<?php

/**
 * This is the model class for table "{{comment_detail}}".
 *
 * The followings are the available columns in table '{{comment_detail}}':
 * @property integer $id
 * @property integer $comment_id
 * @property string $change
 *
 * The followings are the available model relations:
 * @property Comment $comment
 */
class CommentDetail extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CommentDetail the static model class
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
		return '{{comment_detail}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('comment_id', 'required'),
			array('comment_id', 'numerical', 'integerOnly'=>true),
			array('change, comment_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, comment_id, change', 'safe', 'on'=>'search'),
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
			'comment' => array(self::BELONGS_TO, 'Comment', 'comment_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'comment_id' => 'Comment',
			'change' => 'Change',
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
		$criteria->compare('comment_id',$this->comment_id);
		$criteria->compare('change',$this->change,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}