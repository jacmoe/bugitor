<?php

/**
 * This is the model class for table "{{changeset}}".
 *
 * The followings are the available columns in table '{{changeset}}':
 * @property integer $id
 * @property string $revision
 * @property integer $user_id
 * @property integer $scm_id
 * @property string $commit_date
 * @property string $message
 * @property integer $short_rev
 * @property string $parent
 * @property string $branch
 * @property string $tags
 * @property integer $add
 * @property integer $edit
 * @property integer $del
 *
 * The followings are the available model relations:
 * @property Repository $scm
 * @property Users $user
 */
class Changeset extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Changeset the static model class
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
		return '{{changeset}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('revision, commit_date, message', 'required'),
			array('user_id, scm_id, short_rev, add, edit, del', 'numerical', 'integerOnly'=>true),
			array('revision, parent, branch, tags', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, revision, user_id, scm_id, commit_date, message, short_rev, parent, branch, tags, add, edit, del', 'safe', 'on'=>'search'),
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
			'scm' => array(self::BELONGS_TO, 'Repository', 'scm_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'revision' => 'Revision',
			'user_id' => 'User',
			'scm_id' => 'Scm',
			'commit_date' => 'Commit Date',
			'message' => 'Message',
			'short_rev' => 'Short Rev',
			'parent' => 'Parent',
			'branch' => 'Branch',
			'tags' => 'Tags',
			'add' => 'Add',
			'edit' => 'Edit',
			'del' => 'Del',
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
		$criteria->compare('revision',$this->revision,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('scm_id',$this->scm_id);
		$criteria->compare('commit_date',$this->commit_date,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('short_rev',$this->short_rev);
		$criteria->compare('parent',$this->parent,true);
		$criteria->compare('branch',$this->branch,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('add',$this->add);
		$criteria->compare('edit',$this->edit);
		$criteria->compare('del',$this->del);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}