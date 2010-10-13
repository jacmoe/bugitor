<?php

/**
 * This is the model class for table "{{related_issue}}".
 *
 * The followings are the available columns in table '{{related_issue}}':
 * @property integer $issue_from
 * @property integer $issue_to
 * @property integer $relation_type_id
 *
 * The followings are the available model relations:
 * @property Issue $issueFrom0
 * @property Issue $issueTo0
 * @property RelationType $relationType
 */
class RelatedIssue extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return RelatedIssue the static model class
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
		return '{{related_issue}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('issue_from, issue_to, relation_type_id', 'required'),
			array('issue_from, issue_to, relation_type_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('issue_from, issue_to, relation_type_id', 'safe', 'on'=>'search'),
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
			'issueFrom0' => array(self::BELONGS_TO, 'Issue', 'issue_from'),
			'issueTo0' => array(self::BELONGS_TO, 'Issue', 'issue_to'),
			'relationType' => array(self::BELONGS_TO, 'RelationType', 'relation_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'issue_from' => 'Issue From',
			'issue_to' => 'Issue To',
			'relation_type_id' => 'Relation Type',
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

		$criteria->compare('issue_from',$this->issue_from);
		$criteria->compare('issue_to',$this->issue_to);
		$criteria->compare('relation_type_id',$this->relation_type_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}