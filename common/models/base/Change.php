<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "change".
 *
 * @property integer $id
 * @property integer $changeset_id
 * @property string $action
 * @property string $path
 * @property resource $diff
 *
 * @property Changeset $changeset
 */
class Change extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'change';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['changeset_id', 'action', 'path'], 'required'],
            [['changeset_id'], 'integer'],
            [['diff'], 'string'],
            [['action', 'path'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'changeset_id' => Yii::t('app', 'Changeset ID'),
            'action' => Yii::t('app', 'Action'),
            'path' => Yii::t('app', 'Path'),
            'diff' => Yii::t('app', 'Diff'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangeset()
    {
        return $this->hasOne(\common\models\Changeset::className(), ['id' => 'changeset_id']);
    }
}
