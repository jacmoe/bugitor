<?php

/**
 * This is the model class for table "{{attachment}}".
 *
 * The followings are the available columns in table '{{attachment}}':
 * @property integer $id
 * @property integer $issue_id
 * @property integer $user_id
 * @property string $name
 * @property integer $size
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Issue $issue
 * @property User $user
 */
class Attachment extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return Attachment the static model class
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
        return '{{attachment}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('issue_id, user_id, name, size, created', 'required'),
            array('issue_id, user_id, size', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, issue_id, user_id, name, size, created', 'safe', 'on'=>'search'),
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
            'issue' => array(self::BELONGS_TO, 'Issue', 'issue_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'issue_id' => 'Issue',
            'user_id' => 'User',
            'name' => 'Name',
            'size' => 'Size',
            'created' => 'Created',
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
        $criteria->compare('issue_id',$this->issue_id);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('size',$this->size);
        $criteria->compare('created',$this->created,true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        ));
    }
}
