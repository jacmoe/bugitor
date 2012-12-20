<?php

/**
 * This is the model class for table "{{pending_changeset}}".
 *
 * The followings are the available columns in table '{{pending_changeset}}':
 * @property integer $id
 * @property integer $changeset_id
 *
 * The followings are the available model relations:
 * @property Changeset $changeset
 */
class PendingChangeset extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return PendingChangeset the static model class
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
        return '{{pending_changeset}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('changeset_id', 'required'),
            array('changeset_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, changeset_id', 'safe', 'on'=>'search'),
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
            'changeset' => array(self::BELONGS_TO, 'Changeset', 'changeset_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'changeset_id' => 'Changeset',
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
        $criteria->compare('changeset_id',$this->changeset_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        ));
    }
}
